<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";

$events = getAll('events');

$event_id = isset($_GET['event_id']) ? cleanInput($_GET['event_id'], $connect) : '';
$event_date = isset($_GET['date']) ? cleanInput($_GET['date'], $connect) : '';

?>

<?php include "components/header.php"; ?>

<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body class="body">

    <div class="container">
        <h2>Reserve a Table</h2>

        <form action="/admin/crud/create_record.php" method="POST" class="crud-form" onsubmit="return validateReservationForm()">
            <input type="hidden" name="event_id" value="<?= $event_id ?>">
            <input type="date" name="date" id="reservation_date" value="<?= $event_date ?>" required onchange="updateEventName()">
            <input type="text" id="event_name" value="-" readonly>
            <input type="text" name="customer_name" placeholder="Customer Name" required>
            <input type="email" name="customer_email" placeholder="Customer Email" required>
            <input type="tel" name="phone_number" placeholder="Phone Number" required>
            <input type="time" name="time" required>
            <input type="number" name="number_of_people" placeholder="Number of People" required>
            <input type="text" name="special_requests" placeholder="Special Requests">
            <input type="hidden" name="table" value="reservation">
            <input type="submit" value="Reserve Now" name="create_reservation">
        </form>
    </div>

    <script>
        const events = <?= json_encode($events) ?>;
        const defaultEvents = <?= json_encode(getDefaultEvents()) ?>;

        function updateEventName() {
            const dateInput = document.getElementById('reservation_date').value;
            const eventNameInput = document.getElementById('event_name');

            if (!dateInput) {
                eventNameInput.value = '-';
                return;
            }

            const selectedDate = new Date(dateInput);
            const dayOfWeek = selectedDate.toLocaleDateString('en-US', {
                weekday: 'long'
            });

            let customEvent = null;
            for (let i = 0; i < events.length; i++) {
                if (events[i]['date'] === dateInput) {
                    customEvent = events[i];
                    break;
                }
            }

            if (dayOfWeek === 'Sunday') {
                eventNameInput.value = 'Closed';
                alert('The Church is closed on Sundays');
                return;
            }

            if (customEvent) {
                eventNameInput.value = customEvent.name;
            } else if (dayOfWeek === 'Wednesday') {
                eventNameInput.value = `No Event on ${dayOfWeek}`;
            } else {
                eventNameInput.value = defaultEvents[dayOfWeek]?.name || '-';
            }
        }

        window.onload = function() {
            const reservationDateInput = document.getElementById('reservation_date');
            if (reservationDateInput.value) {
                updateEventName();
            }
        };
    </script>
</body>

<?php include "components/footer.php"; ?>

</html>