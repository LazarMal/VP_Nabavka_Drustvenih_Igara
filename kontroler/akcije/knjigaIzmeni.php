<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$SifraIgre = isset($_POST['sifraIgre']) ? trim($_POST['sifraIgre']) : "";
$StaraSifraIgre = isset($_POST['StaraSifraIgre']) ? trim($_POST['StaraSifraIgre']) : "";
$Naziv = isset($_POST['naziv']) ? trim($_POST['naziv']) : "";
$Proizvodjac = isset($_POST['proizvodjac']) ? trim($_POST['proizvodjac']) : "";
$OznakaKategorije = isset($_POST['oznakaKategorije']) ? trim($_POST['oznakaKategorije']) : "";
$StariNazivFajlaSlike = isset($_POST['StariNazivFajlaSlike']) ? trim($_POST['StariNazivFajlaSlike']) : "";

if ($SifraIgre == "" || $StaraSifraIgre == "" || $Naziv == "" || $Proizvodjac == "" || $OznakaKategorije == "") {
    die("Грешка: Сва обавезна поља морају бити попуњена.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (strlen($SifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $SifraIgre)) {
    die("Грешка: Шифра игре мора бити алфанумеричка и до 13 карактера.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (strlen($Naziv) > 100) {
    die("Грешка: Назив игре не сме бити дужи од 100 карактера.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (strlen($Proizvodjac) > 100) {
    die("Грешка: Произвођач не сме бити дужи од 100 карактера.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

$NazivFajlaSlike = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Грешка: Дозвољене су само JPG, JPEG и PNG слике.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
    }

    if (!empty($name)) {
        $location = '../SlikeKnjiga/';
        move_uploaded_file($tmp_name, $location.$name);
        $NazivFajlaSlike = $name;
    }
}

if ($NazivFajlaSlike == "") {
    $NazivFajlaSlike = $StariNazivFajlaSlike;
}

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . '/../../repozitorijumi/DBKnjiga.php';

$KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    die("Није успостављена конекција ка бази података!");
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$SifraIgre = mysqli_real_escape_string($konekcija, $SifraIgre);
$StaraSifraIgre = mysqli_real_escape_string($konekcija, $StaraSifraIgre);
$Naziv = mysqli_real_escape_string($konekcija, $Naziv);
$Proizvodjac = mysqli_real_escape_string($konekcija, $Proizvodjac);
$OznakaKategorije = mysqli_real_escape_string($konekcija, $OznakaKategorije);
$NazivFajlaSlike = mysqli_real_escape_string($konekcija, $NazivFajlaSlike);

$proveraStara = mysqli_query($konekcija, "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$StaraSifraIgre' LIMIT 1");

if (mysqli_num_rows($proveraStara) == 0) {
    die("Грешка: Игра за izmenu ne postoji u katalogu.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

$proveraKat = mysqli_query($konekcija, "SELECT Oznaka FROM `$baza`.`kategorija_igre` WHERE Oznaka='$OznakaKategorije' LIMIT 1");

if (mysqli_num_rows($proveraKat) == 0) {
    die("Грешка: Изабрана категорија није у дозвољеном домену вредности.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if ($SifraIgre != $StaraSifraIgre) {
    $provera = mysqli_query($konekcija, "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$SifraIgre'");

    if (mysqli_num_rows($provera) > 0) {
        die("Грешка: Игра са том шифром већ постоји.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
    }
}

$KnjigaObject = new DBKnjiga($KonekcijaObject, 'drustvena_igra');

$greska = $KnjigaObject->IzmeniKnjigu(
    $StaraSifraIgre,
    $SifraIgre,
    $Naziv,
    $Proizvodjac,
    $OznakaKategorije,
    $NazivFajlaSlike
);

$KonekcijaObject->disconnect();

if (isset($greska) && !empty($greska)) {
    echo "ГРЕШКА:";
    echo "<br><br>"; 
    echo $greska;
    echo "<br><br>";
    echo "<a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>";    
} else {
    header('Location:../../Ruter.php?stranica=knjige');
    exit();
}
?>
