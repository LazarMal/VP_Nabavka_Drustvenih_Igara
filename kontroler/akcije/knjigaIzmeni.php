<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$ISBN = isset($_POST['isbn']) ? trim($_POST['isbn']) : "";
$StariISBN = isset($_POST['StariISBN']) ? trim($_POST['StariISBN']) : "";
$Naziv = isset($_POST['naziv']) ? trim($_POST['naziv']) : "";
$Autor = isset($_POST['autor']) ? trim($_POST['autor']) : "";
$OznakaZanra = isset($_POST['oznakaZanra']) ? trim($_POST['oznakaZanra']) : "";
$StariNazivFajlaSlike = isset($_POST['StariNazivFajlaSlike']) ? trim($_POST['StariNazivFajlaSlike']) : "";

if ($ISBN == "" || $StariISBN == "" || $Naziv == "" || $Autor == "" || $OznakaZanra == "") {
    die("Грешка: Сва обавезна поља морају бити попуњена.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (!preg_match('/^[0-9]{13}$/', $ISBN)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (strlen($Naziv) > 100) {
    die("Грешка: Назив књиге не сме бити дужи од 100 карактера.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

if (strlen($Autor) > 100) {
    die("Грешка: Аутор не сме бити дужи од 100 карактера.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

$dozvoljeniZanrovi = array("RM", "DR", "IS", "NA", "PR");

if (!in_array($OznakaZanra, $dozvoljeniZanrovi)) {
    die("Грешка: Изабрани жанр није у дозвољеном домену вредности.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
}

$NazivFajlaSlike = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Грешка: Дозвољене су само JPG, JPEG и PNG слике.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
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

$ISBN = mysqli_real_escape_string($konekcija, $ISBN);
$StariISBN = mysqli_real_escape_string($konekcija, $StariISBN);
$Naziv = mysqli_real_escape_string($konekcija, $Naziv);
$Autor = mysqli_real_escape_string($konekcija, $Autor);
$OznakaZanra = mysqli_real_escape_string($konekcija, $OznakaZanra);
$NazivFajlaSlike = mysqli_real_escape_string($konekcija, $NazivFajlaSlike);

if ($ISBN != $StariISBN) {
    $provera = mysqli_query($konekcija, "SELECT ISBN FROM `$baza`.`knjiga` WHERE ISBN='$ISBN'");

    if (mysqli_num_rows($provera) > 0) {
        die("Грешка: Књига са тим ISBN бројем већ постоји.<br><br><a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>");
    }
}

$KnjigaObject = new DBKnjiga($KonekcijaObject, 'knjiga');

$greska = $KnjigaObject->IzmeniKnjigu(
    $StariISBN,
    $ISBN,
    $Naziv,
    $Autor,
    $OznakaZanra,
    $NazivFajlaSlike
);

$KonekcijaObject->disconnect();

if (isset($greska) && !empty($greska)) {
    echo "ГРЕШКА:";
    echo "<br><br>"; 
    echo $greska;
    echo "<br><br>";
    echo "<a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>";    
} else {
    header('Location:../../ruter.php?stranica=knjige');
    exit();
}
?>