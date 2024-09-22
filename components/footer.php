<?php

$dashboard = "";
if (isset($_SESSION['admin'])) {
    $dashboard = "<a href='/admin/dashboard.php' class='nav dash-btn'>DashBoard</a>";
}

?>
<?= $dashboard ?>
<footer>
    <div class="address">
        <p>Radetzskystraße 3</p>
        <p>1030, Vienna</p>
        <p>Austria</p>
    </div>


    <div class="links">
        <h3>The Church International Pub</h3>
        <div class="icons">
            <a href="https://www.instagram.com/churchpubofficial/?hl=en" target="_blank"><i class="fa-brands fa-instagram fa-2xl" style="color: #a60dc5;"></i></a>
            <a href="https://www.facebook.com/TheChurchPub/" target="_blank"><i class="fa-brands fa-facebook fa-2xl" style="color: #085b9b;"></i></a>
        </div>
        <!-- <p>Website: <a href="https://github.com/Lothrin">Lothrin</a></p> -->

    </div>

    <div class="contact">
        <p>Gary Scott <a href="/admin/login/login.php"><i class="fa-solid fa-key key" style="color: #4af28a;"></i></a></p>
        <p>+43 650 730 0447</p>
        <p>garyscott@mail.com</p>
    </div>
</footer>