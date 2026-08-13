<?php
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../tehnoloskeKlase/BaznaKonekcija.php';

$KonekcijaObject = new Konekcija(__DIR__ . '/../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    http_response_code(500);
    echo json_encode(array("greska" => "Nije uspela konekcija sa bazom"), JSON_UNESCAPED_UNICODE);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$upit = "SELECT SifraIgre, Naziv, Proizvodjac, NazivKategorije
         FROM `$baza`.`svipodacioidrutvenimigrama`
         ORDER BY Naziv ASC";

$rezultat = mysqli_query($konekcija, $upit);

$igre = array();

if ($rezultat) {
    while ($red = mysqli_fetch_assoc($rezultat)) {
        $igre[] = $red;
    }
}

http_response_code(200);
echo json_encode($igre, JSON_UNESCAPED_UNICODE);

$KonekcijaObject->disconnect();
?>
