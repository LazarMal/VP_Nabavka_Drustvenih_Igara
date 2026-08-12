<?php
header("Content-Type: application/json; charset=UTF-8");

require "../tehnoloskeKlase/BaznaKonekcija.php";

if (!isset($_GET['isbn']) || $_GET['isbn'] == "") {
    echo json_encode(["greska" => "ISBN nije prosleđen"], JSON_UNESCAPED_UNICODE);
    exit();
}

$isbn = $_GET['isbn'];

$KonekcijaObject = new Konekcija('../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    echo json_encode(["greska" => "Nije uspela konekcija sa bazom"], JSON_UNESCAPED_UNICODE);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$isbn = mysqli_real_escape_string($konekcija, $isbn);

$upit = "SELECT ISBN, Naziv, Autor, OznakaZanra, NazivFajlaSlike 
         FROM `$baza`.`knjiga` 
         WHERE ISBN = '$isbn'";

$rezultat = mysqli_query($konekcija, $upit);

if (mysqli_num_rows($rezultat) == 0) {
    echo json_encode(["poruka" => "Knjiga nije pronađena"], JSON_UNESCAPED_UNICODE);
} else {
    $knjiga = mysqli_fetch_assoc($rezultat);
    echo json_encode($knjiga, JSON_UNESCAPED_UNICODE);
}

$KonekcijaObject->disconnect();
?>