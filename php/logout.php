<?php
session_start();

unset($_SESSION['iniciada']);
session_unset();
session_destroy();

header("location:../index.php");
