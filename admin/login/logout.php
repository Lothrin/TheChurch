<?php
session_start();
require_once "../../system/db_connect.php";

if (isset($_GET["logout"])) {

    session_unset();
    session_destroy();


    header("Location: login.php");
    exit();
}
