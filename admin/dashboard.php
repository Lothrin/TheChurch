<?php
session_start();
require_once "../system/db_connect.php";
require_once "../system/functions.php";

if (!isset($_SESSION['admin'])) {
    header("Location: /admin/login/login.php");
    exit();
}

$query = "SELECT COUNT(*) AS unread_count FROM contact_messages WHERE is_read = 0";
$result = mysqli_query($connect, $query);
$unreadCount = mysqli_fetch_assoc($result)['unread_count'];

$users = getAll('users');
$events = getAll('events');
$reservations = getAll('reservations');
$menus = getAll('menus');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>The Church International</title>
</head>

<?php include "../components/hero.php"; ?>

<body class="body">
    <div>
        <div class="dashboard-container">

            <div class="nav-block">
                <ul>
                    <li><a href="#" id="nav-users">Users</a></li>
                    <li><a href="#" id="nav-events">Events</a></li>
                    <li><a href="#" id="nav-reservations">Reservations</a></li>
                    <li><a href="#" id="nav-menu">Menu</a></li>
                    <li><a href="#" id="nav-gallery">Gallery</a></li>
                    <li><a href="#" id="nav-reviews">Reviews</a></li>
                </ul>
            </div>

            <div class="content-area">
                <h2>Admin Dashboard</h2>
                <a href='/admin/login/logout.php?logout=true' class='btn'>Logout</a>
                <a href='inbox.php' class='btn'>
                    <i class="fa-regular fa-envelope fa-xl" style="color: #0767b0;"></i></a>
                <?php if ($unreadCount > 0): ?>
                    <span class="unread-bubble"><?= $unreadCount ?></span>
                <?php endif; ?>

                <section id="users-section" class="crud-section">
                    <div class="section-header">
                        <h2>Manage Users</h2>
                        <button class="open-modal-btn" data-modal="user-modal">Create New</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['role'] ?></td>
                                    <td>
                                        <a class="crd-btn-edit" href="crud/edit_record.php?table=users&id=<?= $user['id'] ?>">Edit</a>
                                        <a class="crd-btn-delete" href="crud/delete_record.php?table=users&id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <section id="events-section" class="crud-section hidden">
                    <div class="section-header">
                        <h2>Manage Events</h2>
                        <button class="open-modal-btn" data-modal="event-modal">Create New</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event) : ?>
                                <tr>
                                    <td><?= $event['id'] ?></td>
                                    <td><?= $event['name'] ?></td>
                                    <td><?= $event['description'] ?></td>
                                    <td><?= $event['date'] ?></td>
                                    <td><?= $event['time'] ?></td>
                                    <td>
                                        <a class="crd-btn-edit" href="crud/edit_record.php?table=events&id=<?= $event['id'] ?>">Edit</a>
                                        <a class="crd-btn-delete" href="crud/delete_record.php?table=events&id=<?= $event['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <section id="reservations-section" class="crud-section hidden">
                    <div class="section-header">
                        <h2>Manage Reservations</h2>
                        <button class="open-modal-btn" data-modal="reservation-modal">Create New</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation) : ?>
                                <tr>
                                    <td><?= $reservation['id'] ?></td>
                                    <td><?= $reservation['customer_name'] ?></td>
                                    <td><?= $reservation['customer_email'] ?></td>
                                    <td><?= $reservation['date'] ?></td>
                                    <td><?= $reservation['time'] ?></td>
                                    <td>
                                        <a class="crd-btn-edit" href="crud/edit_record.php?table=reservations&id=<?= $reservation['id'] ?>">Edit</a>
                                        <a class="crd-btn-delete" href="crud/delete_record.php?table=reservations&id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <section id="menu-section" class="crud-section hidden">
                    <div class="section-header">
                        <h2>Manage Menu Items</h2>
                        <button class="open-modal-btn" data-modal="menu-modal">Create New</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu) : ?>
                                <tr>
                                    <td><?= $menu['id'] ?></td>
                                    <td><?= $menu['item_name'] ?></td>
                                    <td><?= $menu['description'] ?></td>
                                    <td><?= $menu['price'] ?></td>
                                    <td><?= $menu['category'] ?></td>
                                    <td>
                                        <a class="crd-btn-edit" href="crud/edit_record.php?table=menus&id=<?= $menu['id'] ?>">Edit</a>
                                        <a class="crd-btn-delete" href="crud/delete_record.php?table=menus&id=<?= $menu['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <section id="gallery-section" class="crud-section hidden">
                    <!-- Gallery management will go here -->
                </section>

                <section id="reviews-section" class="crud-section hidden">
                    <!-- Reviews management will go here -->
                </section>
            </div>
        </div>

        <div id="user-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" data-modal="user-modal">&times;</span>
                <h3>Create New User</h3>
                <form action="crud/create_record.php" method="POST" class="crud-form">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="text" name="role" placeholder="Role (e.g., admin, staff)" required>
                    <input type="hidden" name="table" value="user">
                    <input type="submit" value="Create User" name="create_user">
                </form>
            </div>
        </div>

        <div id="event-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" data-modal="event-modal">&times;</span>
                <h3>Create New Event</h3>
                <form action="crud/create_record.php" method="POST" class="crud-form" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Event Name" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="date" name="date" required>
                    <input type="time" name="time" required>
                    <input type="file" name="event_images[]" accept="image/*" multiple required>
                    <input type="hidden" name="table" value="event">
                    <input type="submit" value="Create Event" name="create_event">
                </form>
            </div>
        </div>

        <div id="reservation-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" data-modal="reservation-modal">&times;</span>
                <h3>Create New Reservation</h3>
                <form action="crud/create_record.php" method="POST" class="crud-form">
                    <input type="text" name="customer_name" placeholder="Customer Name" required>
                    <input type="email" name="customer_email" placeholder="Customer Email" required>
                    <input type="tel" name="phone_number" placeholder="Phone Number" required>
                    <input type="date" name="date" required>
                    <input type="time" name="time" required>
                    <input type="number" name="number_of_people" placeholder="Number of People" required>
                    <input type="text" name="special_requests" placeholder="Special Requests">
                    <input type="hidden" name="table" value="reservation">
                    <input type="submit" value="Create Reservation" name="create_reservation">
                </form>
            </div>
        </div>

        <div id="menu-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" data-modal="menu-modal">&times;</span>
                <h3>Create New Menu Item</h3>
                <form action="crud/create_record.php" method="POST" class="crud-form">
                    <input type="text" name="item_name" placeholder="Item Name" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="number" name="price" step="0.01" placeholder="Price" required>
                    <input type="text" name="category" placeholder="Category (e.g., food, drinks)" required>
                    <input type="hidden" name="table" value="menu">
                    <input type="submit" value="Create Menu Item" name="create_menu">
                </form>
            </div>
        </div>
    </div>
    <script src="../main.js"></script>
</body>
<?php include "../components/footer.php"; ?>

</html>