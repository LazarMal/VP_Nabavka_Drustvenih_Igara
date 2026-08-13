<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$SifraIgre = isset($_POST['sifraIgre']) ? trim($_POST['sifraIgre']) : "";
$Naziv = isset($_POST['naziv']) ? trim($_POST['naziv']) : "";
$Proizvodjac = isset($_POST['proizvodjac']) ? trim($_POST['proizvodjac']) : "";
$OznakaKategorije = isset($_POST['oznakaKategorije']) ? trim($_POST['oznakaKategorije']) : "";

if ($SifraIgre == "" || $Naziv == "" || $Proizvodjac == "" || $OznakaKategorije == "") {
    die("Greska: Sva obavezna polja moraju biti popunjena.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

if (strlen($SifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $SifraIgre)) {
    die("Greska: Sifra igre mora biti alfanumericka i do 13 karaktera.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

if (strlen($Naziv) > 100) {
    die("Greska: Naziv igre ne sme biti duzi od 100 karaktera.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

if (strlen($Proizvodjac) > 100) {
    die("Greska: Proizvodjac ne sme biti duzi od 100 karaktera.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

$name = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Greska: Dozvoljene su samo JPG, JPEG i PNG slike.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
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
    die("Greska: Nije uspostavljena konekcija sa bazom podataka.");
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$SifraIgre = mysqli_real_escape_string($konekcija, $SifraIgre);
$Naziv = mysqli_real_escape_string($konekcija, $Naziv);
$Proizvodjac = mysqli_real_escape_string($konekcija, $Proizvodjac);
$OznakaKategorije = mysqli_real_escape_string($konekcija, $OznakaKategorije);
$NazivFajlaSlike = mysqli_real_escape_string($konekcija, $NazivFajlaSlike);

$proveraKat = mysqli_query($konekcija, "SELECT Oznaka FROM `$baza`.`kategorija_igre` WHERE Oznaka='$OznakaKategorije' LIMIT 1");

if (mysqli_num_rows($proveraKat) == 0) {
    die("Greska: Izabrana kategorija nije u dozvoljenom domenu vrednosti.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

$provera = mysqli_query($konekcija, "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$SifraIgre'");

if (mysqli_num_rows($provera) > 0) {
    die("Greska: Igra sa tom sifrom vec postoji.<br><br><a href=\"../Ruter.php?stranica=unos\">POVRATAK</a>");
}

mysqli_begin_transaction($konekcija);

$upit1 = "INSERT INTO `$baza`.`drustvena_igra`
(`SifraIgre`, `Naziv`, `Proizvodjac`, `OznakaKategorije`, `NazivFajlaSlike`)
VALUES
('$SifraIgre', '$Naziv', '$Proizvodjac', '$OznakaKategorije', '$NazivFajlaSlike')";

$rezultat1 = mysqli_query($konekcija, $upit1);

$upit2 = "UPDATE `$baza`.`kategorija_igre`
SET `UkupanBrojIgara` = `UkupanBrojIgara` + 1
WHERE `Oznaka` = '$OznakaKategorije'";

$rezultat2 = mysqli_query($konekcija, $upit2);

if ($rezultat1 && $rezultat2) {
    mysqli_commit($konekcija);
    header('Location:../../Ruter.php?stranica=knjige');
    exit();
} else {
    mysqli_rollback($konekcija);
    echo "Greska prilikom snimanja igre!";
    echo "<br>";
    echo mysqli_error($konekcija);
    echo "<br><br>";
    echo "<a href=\"../../Ruter.php?stranica=knjige\">POVRATAK</a>";
}

$KonekcijaObject->disconnect();
?>
