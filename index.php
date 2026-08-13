<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["korisnik"])) {
    header('Location: Ruter.php?stranica=prijava');
    exit();
}

header('Location: Ruter.php?stranica=welcome');
exit();
