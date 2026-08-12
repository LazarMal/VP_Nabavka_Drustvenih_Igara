<?php
header("Content-Type: application/json; charset=UTF-8");

require "../tehnoloskeKlase/BaznaKonekcija.php";

$KonekcijaObject = new Konekcija('../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    echo json_encode(["greska" => "Nije uspela konekcija sa bazom"]);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$upit = "SELECT ISBN, Naziv, Autor, OznakaZanra, NazivFajlaSlike FROM `$baza`.`knjiga` ORDER BY Naziv ASC";
$rezultat = mysqli_query($konekcija, $upit);

$knjige = [];

while ($red = mysqli_fetch_assoc($rezultat)) {
    $knjige[] = $red;
}

echo json_encode($knjige, JSON_UNESCAPED_UNICODE);

$KonekcijaObject->disconnect();
?>