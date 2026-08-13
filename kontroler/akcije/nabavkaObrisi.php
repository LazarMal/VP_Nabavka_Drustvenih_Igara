<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=nabavke';

if (!isset($_POST['IDNabavke']) || trim($_POST['IDNabavke']) === "") {
    die("Грешка: Није изабран nalog za brisanje.<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
}

$IDNabavke = trim($_POST['IDNabavke']);

require __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require __DIR__ . '/../../repozitorijumi/DBNabavka.php';

$KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

$UtvrdjenaGreska = "";

if ($KonekcijaObject->konekcijaDB) {
    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $IDNabavkeEsc = mysqli_real_escape_string($konekcija, $IDNabavke);

    $provera = mysqli_query(
        $konekcija,
        "SELECT IDNabavke, BrojNaloga FROM `$baza`.`nabavka` WHERE IDNabavke='$IDNabavkeEsc' LIMIT 1"
    );

    if (!$provera || mysqli_num_rows($provera) == 0) {
        $KonekcijaObject->disconnect();
        die("Грешка: Nalog ne postoji.<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
    }

    $NabavkaObject = new DBNabavka($KonekcijaObject, 'nabavka');
    $UtvrdjenaGreska = $NabavkaObject->ObrisiNabavku($IDNabavkeEsc);
}

$KonekcijaObject->disconnect();

if ($UtvrdjenaGreska != "") {
    echo "Грешка: " . $UtvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
} else {
    header('Location:' . $povratakUrl);
    exit();
}
?>
