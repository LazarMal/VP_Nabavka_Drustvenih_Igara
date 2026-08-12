<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$ISBN = isset($_POST['isbn']) ? trim($_POST['isbn']) : "";
$Naziv = isset($_POST['naziv']) ? trim($_POST['naziv']) : "";
$Autor = isset($_POST['autor']) ? trim($_POST['autor']) : "";
$OznakaZanra = isset($_POST['oznakaZanra']) ? trim($_POST['oznakaZanra']) : "";

if ($ISBN == "" || $Naziv == "" || $Autor == "" || $OznakaZanra == "") {
    die("Грешка: Сва обавезна поља морају бити попуњена.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

if (!preg_match('/^[0-9]{13}$/', $ISBN)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

if (strlen($Naziv) > 100) {
    die("Грешка: Назив књиге не сме бити дужи од 100 карактера.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

if (strlen($Autor) > 100) {
    die("Грешка: Аутор не сме бити дужи од 100 карактера.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

$dozvoljeniZanrovi = array("RM", "DR", "IS", "NA", "PR");

if (!in_array($OznakaZanra, $dozvoljeniZanrovi)) {
    die("Грешка: Изабрани жанр није у дозвољеном домену вредности.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

$name = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Грешка: Дозвољене су само JPG, JPEG и PNG слике.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
    }

    if (!empty($name)) {
        $location = '../SlikeKnjiga/';
        move_uploaded_file($tmp_name, $location.$name);
    }
}

$NazivFajlaSlike = $name;

require __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';

$KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    die("Грешка: Није успостављена конекција са базом података.");
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$ISBN = mysqli_real_escape_string($konekcija, $ISBN);
$Naziv = mysqli_real_escape_string($konekcija, $Naziv);
$Autor = mysqli_real_escape_string($konekcija, $Autor);
$OznakaZanra = mysqli_real_escape_string($konekcija, $OznakaZanra);
$NazivFajlaSlike = mysqli_real_escape_string($konekcija, $NazivFajlaSlike);

$provera = mysqli_query($konekcija, "SELECT ISBN FROM `$baza`.`knjiga` WHERE ISBN='$ISBN'");

if (mysqli_num_rows($provera) > 0) {
    die("Грешка: Књига са тим ISBN бројем већ постоји.<br><br><a href=\"../ruter.php?stranica=unosSP\">ПОВРАТАК</a>");
}

$upit = "CALL DodajKnjigu('$ISBN', '$Naziv', '$Autor', '$OznakaZanra', '$NazivFajlaSlike')";
$rezultat = mysqli_query($konekcija, $upit);

if ($rezultat) {
    header('Location:../../ruter.php?stranica=knjige');
    exit();
} else {
    echo "Грешка приликом снимања књиге преко stored procedure!";
    echo "<br>";
    echo mysqli_error($konekcija);
    echo "<br><br>";
    echo "<a href=\"../../ruter.php?stranica=knjige\">ПОВРАТАК</a>";
}

$KonekcijaObject->disconnect();
?>