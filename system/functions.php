<?php
$userId = "";
$currentDate = getCurrentDate();


function cleanInput($input, $connect)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars_decode($data);
    return mysqli_real_escape_string($connect, $data);
}


function getCurrentDate()
{
    return date('Y-m-d');
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


function isPastEvent($eventDate)
{
    return $eventDate < getCurrentDate();
}


function formatEventDate($date)
{
    return date('F j, Y', strtotime($date));
}


function showDetailsPage($tableName, $id)
{
    global $connect;
    $sql = "SELECT * FROM $tableName WHERE id = $id";
    $result = mysqli_query($connect, $sql);

    return (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
}


function getAll($tableName)
{
    global $connect;
    $sql = "SELECT * FROM $tableName";
    $result = mysqli_query($connect, $sql);

    return (mysqli_num_rows($result) == 0) ? [] : mysqli_fetch_all($result, MYSQLI_ASSOC);
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

function createEvent($name, $description, $date, $time, $image)
{
    global $connect;


    $name = cleanInput($name, $connect);
    $description = cleanInput($description, $connect);
    $date = cleanInput($date, $connect);
    $time = cleanInput($time, $connect);
    $image = cleanInput($image, $connect);

    $sql = "INSERT INTO events (name, description, date, time, image, created_at) 
            VALUES ('$name', '$description', '$date', '$time', '$image', NOW())";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteEvent($eventId)
{
    global $connect;

    $eventId = cleanInput($eventId, $connect);
    $sql = "DELETE FROM events WHERE id = $eventId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function updateEvent($eventId, $name, $description, $date, $time, $image)
{
    global $connect;


    $eventId = cleanInput($eventId, $connect);
    $name = cleanInput($name, $connect);
    $description = cleanInput($description, $connect);
    $date = cleanInput($date, $connect);
    $time = cleanInput($time, $connect);
    $image = cleanInput($image, $connect);

    $sql = "UPDATE events 
            SET name = '$name', description = '$description', date = '$date', time = '$time', image = '$image' 
            WHERE id = $eventId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function createMenuItem($item_name, $description, $price, $category)
{
    global $connect;


    $item_name = cleanInput($item_name, $connect);
    $description = cleanInput($description, $connect);
    $price = cleanInput($price, $connect);
    $category = cleanInput($category, $connect);

    $sql = "INSERT INTO menus (item_name, description, price, category, created_at) 
            VALUES ('$item_name', '$description', $price, '$category', NOW())";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteMenuItem($menuId)
{
    global $connect;

    $menuId = cleanInput($menuId, $connect);
    $sql = "DELETE FROM menus WHERE id = $menuId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function updateMenuItem($menuId, $item_name, $description, $price, $category)
{
    global $connect;


    $menuId = cleanInput($menuId, $connect);
    $item_name = cleanInput($item_name, $connect);
    $description = cleanInput($description, $connect);
    $price = cleanInput($price, $connect);
    $category = cleanInput($category, $connect);

    $sql = "UPDATE menus 
            SET item_name = '$item_name', description = '$description', price = $price, category = '$category' 
            WHERE id = $menuId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function createReservation($customer_name, $customer_email, $phone_number, $date, $time, $number_of_people, $event_id, $special_requests)
{
    global $connect;


    $customer_name = cleanInput($customer_name, $connect);
    $customer_email = cleanInput($customer_email, $connect);
    $phone_number = cleanInput($phone_number, $connect);
    $date = cleanInput($date, $connect);
    $time = cleanInput($time, $connect);
    $number_of_people = cleanInput($number_of_people, $connect);
    $special_requests = cleanInput($special_requests, $connect);


    if (empty($event_id)) {
        $event_id = 'NULL';
    } else {
        $event_id = cleanInput($event_id, $connect);
    }


    $sql = "INSERT INTO reservations (customer_name, customer_email, phone_number, date, time, number_of_people, event_id, special_requests, created_at) 
            VALUES ('$customer_name', '$customer_email', '$phone_number', '$date', '$time', $number_of_people, $event_id, '$special_requests', NOW())";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteReservation($reservationId)
{
    global $connect;

    $reservationId = cleanInput($reservationId, $connect);
    $sql = "DELETE FROM reservations WHERE id = $reservationId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function updateReservation($reservationId, $customer_name, $customer_email, $phone_number, $date, $time, $number_of_people, $event_id, $special_requests)
{
    global $connect;


    $reservationId = cleanInput($reservationId, $connect);
    $customer_name = cleanInput($customer_name, $connect);
    $customer_email = cleanInput($customer_email, $connect);
    $phone_number = cleanInput($phone_number, $connect);
    $date = cleanInput($date, $connect);
    $time = cleanInput($time, $connect);
    $number_of_people = cleanInput($number_of_people, $connect);
    $special_requests = cleanInput($special_requests, $connect);


    if (empty($event_id)) {
        $event_id = 'NULL';
    } else {
        $event_id = cleanInput($event_id, $connect);
    }


    $sql = "UPDATE reservations 
            SET customer_name = '$customer_name', customer_email = '$customer_email', phone_number = '$phone_number', 
            date = '$date', time = '$time', number_of_people = $number_of_people, event_id = $event_id, special_requests = '$special_requests' 
            WHERE id = $reservationId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteUser($userId)
{
    global $connect;

    $userId = cleanInput($userId, $connect);
    $sql = "DELETE FROM users WHERE id = $userId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function createUser($username, $email, $password, $role)
{
    global $connect;


    $username = cleanInput($username, $connect);
    $email = cleanInput($email, $connect);
    $password = cleanInput($password, $connect);
    $role = cleanInput($role, $connect);


    $hashed_password = hash("sha256", $password);


    $sql = "INSERT INTO users (username, email, password_hash, role, created_at) 
            VALUES ('$username', '$email', '$hashed_password', '$role', NOW())";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}

function updateUser($userId, $username, $email, $role)
{
    global $connect;


    $userId = cleanInput($userId, $connect);
    $username = cleanInput($username, $connect);
    $email = cleanInput($email, $connect);
    $role = cleanInput($role, $connect);

    $sql = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE id = $userId";

    if (mysqli_query($connect, $sql)) {
        return true;
    } else {
        return false;
    }
}
