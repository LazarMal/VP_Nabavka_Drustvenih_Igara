<?php
header("Content-Type: application/json; charset=UTF-8");

require "../tehnoloskeKlase/BaznaKonekcija.php";

$KonekcijaObject = new Konekcija('../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    echo json_encode(array("greska" => "Nije uspela konekcija sa bazom"), JSON_UNESCAPED_UNICODE);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$upit = "SELECT SifraIgre, Naziv, Proizvodjac, NazivKategorije, NazivFajlaSlike
         FROM `$baza`.`svipodacioidrutvenimigramasaslikom`
         ORDER BY Naziv ASC";

$rezultat = mysqli_query($konekcija, $upit);

$igre = array();

if ($rezultat) {
    while ($red = mysqli_fetch_assoc($rezultat)) {
        $igre[] = $red;
    }
}

echo json_encode($igre, JSON_UNESCAPED_UNICODE);

$KonekcijaObject->disconnect();
?>
