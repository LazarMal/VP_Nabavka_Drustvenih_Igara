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
    die("Greska: Sva obavezna polja moraju biti popunjena.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

if (strlen($SifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $SifraIgre)) {
    die("Greska: Sifra igre mora biti alfanumericka i do 13 karaktera.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

if (strlen($Naziv) > 100) {
    die("Greska: Naziv igre ne sme biti duzi od 100 karaktera.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

if (strlen($Proizvodjac) > 100) {
    die("Greska: Proizvodjac ne sme biti duzi od 100 karaktera.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

$NazivFajlaSlike = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Greska: Dozvoljene su samo JPG, JPEG i PNG slike.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
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
    die("Nije uspostavljena konekcija ka bazi podataka!");
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
    die("Greska: Igra za izmenu ne postoji u katalogu.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

$proveraKat = mysqli_query($konekcija, "SELECT Oznaka FROM `$baza`.`kategorija_igre` WHERE Oznaka='$OznakaKategorije' LIMIT 1");

if (mysqli_num_rows($proveraKat) == 0) {
    die("Greska: Izabrana kategorija nije u dozvoljenom domenu vrednosti.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
}

if ($SifraIgre != $StaraSifraIgre) {
    $provera = mysqli_query($konekcija, "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$SifraIgre'");

    if (mysqli_num_rows($provera) > 0) {
        die("Greska: Igra sa tom sifrom vec postoji.<br><br><a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>");
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
    echo "GRESKA:";
    echo "<br><br>"; 
    echo $greska;
    echo "<br><br>";
    echo "<a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>";    
} else {
    header('Location:../../Ruter.php?stranica=knjige');
    exit();
}
?>
