<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=drustveneIgre';

if (!isset($_POST['sifraIgre']) || trim($_POST['sifraIgre']) === "") {
    die("Greška: Šifra igre nije prosleđena.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$sifraIgre = trim($_POST['sifraIgre']);

if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
    die("Greška: Šifra igre mora biti alfanumerička i do 13 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

require_once __DIR__ . '/../../kontroler/stranice/DrustveneIgreController.php';

$Controller = new DrustveneIgreController();

if (!$Controller->PostojiSifraIgre($sifraIgre)) {
    $Controller->ZatvoriKonekciju();
    die("Greška: Igra za brisanje ne postoji u katalogu.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$greska = $Controller->ObrisiDrustvenuIgru($sifraIgre);
$Controller->ZatvoriKonekciju();

if ($greska !== "") {
    echo "Greška: " . htmlspecialchars($greska, ENT_QUOTES, 'UTF-8');
    echo "<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>";
} else {
    header('Location:../../Ruter.php?stranica=drustveneIgre');
    exit();
}
?>
