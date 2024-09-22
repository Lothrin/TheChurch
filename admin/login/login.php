<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";

if (isset($_SESSION['admin'])) {
    header("Location: /admin/dashboard.php");
    exit();
}



$error = false;
$email = $emailError = $passError = "";

if (isset($_POST["login-btn"])) {
    $email = cleanInput($_POST["email"], $connect);
    $password = cleanInput($_POST["password"], $connect);


    if (empty($email)) {
        $error = true;
        $emailError = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address!";
    }


    if (empty($password)) {
        $error = true;
        $passError = "Password is required!";
    }


    if (!$error) {
        $password = hash("sha256", $password);


        $sql = "SELECT * FROM users WHERE email = '$email' AND password_hash = '$password'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_assoc($result);


        if (mysqli_num_rows($result) == 1) {
            if ($row["role"] == "admin") {

                $_SESSION["admin"] = $row["id"];
                header("Location: /admin/dashboard.php");
            } else {

                $_SESSION["staff"] = $row["id"];
                echo $_SESSION;
                header("Location: /home.php");
            }
        } else {
            $error = true;
            $emailError = "Incorrect credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>The Church International</title>
</head>
<?php include "../../components/hero.php" ?>


<body>
    <div class="body-wrapper">
        <div class="login-container">


            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" autocomplete="off">
                <div class="form-group">
                    <label class="form-label" for="email">Email:</label>

                    <input class="form-input" type="email" id="email" name="email" value="<?= $email ?>">
                    <p class="error-message"><?= $emailError ?></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password:</label>

                    <input class="form-input" type="password" id="password" name="password">
                    <p class="error-message"><?= $passError ?></p>
                </div>
                <input type="submit" value="Login" name="login-btn" class="login-btn">
            </form>



        </div>
    </div>

    <script src="main.js"></script>
</body>



<?php include "../../components/footer.php" ?>

</html>