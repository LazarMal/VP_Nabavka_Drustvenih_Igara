<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=unos';

if (!isset($_POST['sifraIgre']) || trim($_POST['sifraIgre']) === "") {
    die("Greška: Šifra igre nije prosleđena.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$SifraIgre = trim($_POST['sifraIgre']);
$Naziv = isset($_POST['naziv']) ? trim($_POST['naziv']) : "";
$Proizvodjac = isset($_POST['proizvodjac']) ? trim($_POST['proizvodjac']) : "";
$OznakaKategorije = isset($_POST['oznakaKategorije']) ? trim($_POST['oznakaKategorije']) : "";

if ($SifraIgre == "" || $Naziv == "" || $Proizvodjac == "" || $OznakaKategorije == "") {
    die("Greška: Sva obavezna polja moraju biti popunjena.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

if (!preg_match('/^[A-Za-z0-9]{1,13}$/', $SifraIgre)) {
    die("Greška: Šifra igre mora biti alfanumerička i imati od 1 do 13 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

if (strlen($Naziv) > 100) {
    die("Greška: Naziv ne sme biti duži od 100 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

if (strlen($Proizvodjac) > 100) {
    die("Greška: Proizvođač ne sme biti duži od 100 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

if (strlen($OznakaKategorije) > 2) {
    die("Greška: Oznaka kategorije ne sme biti duža od 2 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$nazivFajlaSlike = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];
    $ekstenzija = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $dozvoljeneEkstenzije = array("jpg", "jpeg", "png");

    if (!in_array($ekstenzija, $dozvoljeneEkstenzije)) {
        die("Greška: Dozvoljene su samo JPG, JPEG i PNG slike.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
    }

    if (strlen($name) > 100) {
        die("Greška: Naziv fajla slike ne sme biti duži od 100 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
    }

    if (!empty($name)) {
        $uploadDir = __DIR__ . '/../../SlikeIgara/';
        if (!move_uploaded_file($tmp_name, $uploadDir . $name)) {
            die("Greška: Upload slike nije uspeo.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
        }
        $nazivFajlaSlike = $name;
    }
}

require_once __DIR__ . '/../../kontroler/stranice/DrustveneIgreController.php';

$Controller = new DrustveneIgreController();

if ($Controller->PostojiSifraIgre($SifraIgre)) {
    $Controller->ZatvoriKonekciju();
    die("Greška: Igra sa tom šifrom već postoji.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$greska = $Controller->SnimiNovuDrustvenuIgru($SifraIgre, $Naziv, $Proizvodjac, $OznakaKategorije, $nazivFajlaSlike);
$Controller->ZatvoriKonekciju();

if ($greska === "") {
    header('Location:../../Ruter.php?stranica=drustveneIgre');
    exit();
}

echo "Greška prilikom snimanja igre!";
echo "<br>" . htmlspecialchars($greska, ENT_QUOTES, 'UTF-8');
echo "<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>";
?>
