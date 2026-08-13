<?php
header("Content-Type: application/json; charset=UTF-8");

require "../tehnoloskeKlase/BaznaKonekcija.php";

if (!isset($_GET['sifraIgre']) || trim($_GET['sifraIgre']) == "") {
    echo json_encode(array("greska" => "SifraIgre nije prosleđena"), JSON_UNESCAPED_UNICODE);
    exit();
}

$sifraIgre = trim($_GET['sifraIgre']);

$KonekcijaObject = new Konekcija('../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    echo json_encode(array("greska" => "Nije uspela konekcija sa bazom"), JSON_UNESCAPED_UNICODE);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$sifraIgreEsc = mysqli_real_escape_string($konekcija, $sifraIgre);

$upit = "SELECT SifraIgre, Naziv, Proizvodjac, NazivKategorije, NazivFajlaSlike
         FROM `$baza`.`svipodacioidrutvenimigramasaslikom`
         WHERE SifraIgre = '$sifraIgreEsc'
         LIMIT 1";

$rezultat = mysqli_query($konekcija, $upit);

if (!$rezultat || mysqli_num_rows($rezultat) == 0) {
    echo json_encode(array("poruka" => "Drustvena igra nije pronađena"), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(mysqli_fetch_assoc($rezultat), JSON_UNESCAPED_UNICODE);
}

$KonekcijaObject->disconnect();
?>
