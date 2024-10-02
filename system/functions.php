<?php
$userId = "";
$currentDate = getCurrentDate();


function cleanInput($input, $connect, $type = 'general')
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars_decode($data);

    $data = mysqli_real_escape_string($connect, $data);

    switch ($type) {
        case 'email':
            if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                return ''; // or an error
            }
            break;
        case 'phone':

            if (!preg_match('/^[0-9\s\-\+]+$/', $data)) {
                return ''; // or an error
            }
            break;
        case 'date':

            $d = DateTime::createFromFormat('Y-m-d', $data);
            if ($d && $d->format('Y-m-d') === $data) {
                return $data;
            }
            return ''; // or an error
        case 'time':

            if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/', $data)) {
                return ''; // or an error
            }
            break;
        case 'number':

            if (!is_numeric($data)) {
                return ''; // or an error
            }
            break;
        case 'general':
        default:

            break;
    }

    return $data;
}



function getCurrentDate()
{
    return date('Y-m-d');
}
function formatEventDate($date)
{
    return date('F j, Y', strtotime($date));
}


function getFirstImage($imageField)
{
    $images = explode(",", $imageField);
    $firstImage = trim($images[0]);
    return (!empty($firstImage) && file_exists("assets/event-images/" . $firstImage)) ? $firstImage : 'placeholder.jpg';
}

function getEventImages($imageString)
{
    $images = explode(",", $imageString);
    return array_slice($images, 1);
}


function getDefaultEvents()
{
    return [
        'Monday' => ['name' => 'Metal Night', 'description' => 'Make Mondays bearable with metal music!', 'image' => 'metal-monday.jpg'],
        'Tuesday' => ['name' => 'Pub Quiz', 'description' => 'Prove your knowledge and win prizes.', 'image' => 'quiz-tuesday.jpg'],
        'Wednesday' => ['name' => 'Stammgäste', 'description' => 'Wednesday is an event-free day.', 'image' => 'wednesday.jpg'],
        'Thursday' => ['name' => 'Karaoke Night', 'description' => 'Sing your heart out at Karaoke Night!', 'image' => 'thursday-karaoke.jpg'],
        'Friday' => ['name' => 'Open Mic', 'description' => 'Join us for Open Mic night with live music.', 'image' => 'friday-open.jpg'],
        'Saturday' => ['name' => 'Bands Night', 'description' => 'Enjoy live performances by talented bands.', 'image' => 'regular-saturday.png'],
        'Sunday' => ['name' => 'Closed', 'description' => 'The Church is closed on Sundays.', 'image' => 'sunday.jpg']
    ];
}


function getNextEvent($connect)
{
    $currentDate = getCurrentDate();
    $sql = "SELECT * FROM events WHERE date >= '$currentDate' ORDER BY date ASC LIMIT 1";
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result);
}


function getUpcomingEvents($events, $numDays = 7)
{
    $defaultEvents = getDefaultEvents();
    $upcomingEvents = [];
    $today = new DateTime();

    for ($i = 0; $i < $numDays; $i++) {
        $currentDay = clone $today;
        $currentDay->modify("+$i day");
        $dayOfWeek = $currentDay->format('l');
        $dateFormatted = $currentDay->format('d.m.');
        $dbDate = $currentDay->format('Y-m-d');

        $customEvent = null;
        foreach ($events as $event) {
            if ($event['date'] == $dbDate) {
                $customEvent = $event;
                break;
            }
        }

        if ($customEvent) {
            $firstImage = getFirstImage($customEvent['image']);
            $imagePath = 'assets/event-images/' . $firstImage;

            $upcomingEvents[] = [
                'name' => $customEvent['name'],
                'description' => $customEvent['description'],
                'image' => $imagePath,
                'date' => $dateFormatted,
                'db_date' => $dbDate,
                'event_id' => $customEvent['id'],
                'day_of_week' => $dayOfWeek
            ];
        } else {
            $upcomingEvents[] = [
                'name' => $defaultEvents[$dayOfWeek]['name'],
                'description' => $defaultEvents[$dayOfWeek]['description'],
                'image' => 'assets/images/' . $defaultEvents[$dayOfWeek]['image'],
                'date' => $dateFormatted,
                'db_date' => $dbDate,
                'event_id' => null,
                'day_of_week' => $dayOfWeek
            ];
        }
    }

    return $upcomingEvents;
}


function isPastEvent($eventDate)
{
    return $eventDate < getCurrentDate();
}


function getPastEventsWithImages($events)
{
    $pastEventImages = [];

    foreach ($events as $event) {
        if (isPastEvent($event['date'])) {
            $eventImages = getEventImages($event['image']);
            if (!empty($eventImages)) {
                $pastEventImages[] = [
                    'event_name' => $event['name'],
                    'event_date' => $event['date'],
                    'images' => $eventImages,
                    'event_id' => $event['id']
                ];
            }
        }
    }

    return $pastEventImages;
}


function getAll($tableName)
{
    global $connect;
    $sql = "SELECT * FROM $tableName";
    $result = mysqli_query($connect, $sql);

    return (mysqli_num_rows($result) == 0) ? [] : mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function showDetailsPage($tableName, $id)
{
    global $connect;
    $sql = "SELECT * FROM $tableName WHERE id = $id";
    $result = mysqli_query($connect, $sql);

    return (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
}


function insertRecord($table, $data)
{
    global $connect;
    $fields = implode(", ", array_keys($data));
    $values = implode(", ", array_map(function ($value) use ($connect) {
        return "'" . mysqli_real_escape_string($connect, $value) . "'";
    }, array_values($data)));

    $sql = "INSERT INTO $table ($fields) VALUES ($values)";
    return mysqli_query($connect, $sql);
}

function updateRecord($table, $data, $id)
{
    global $connect;
    $set = [];
    foreach ($data as $key => $value) {
        $set[] = "$key = '" . mysqli_real_escape_string($connect, $value) . "'";
    }
    $setString = implode(", ", $set);

    $sql = "UPDATE $table SET $setString WHERE id = $id";
    return mysqli_query($connect, $sql);
}

function deleteRecord($table, $id)
{
    global $connect;
    $sql = "DELETE FROM $table WHERE id = " . intval($id);
    return mysqli_query($connect, $sql);
}
