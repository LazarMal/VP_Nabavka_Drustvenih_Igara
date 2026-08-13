<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=novaNabavka';

function prekiniSaGreskom($poruka, $povratakUrl)
{
    die($poruka . "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$brojNaloga = isset($_POST['brojNaloga']) ? trim($_POST['brojNaloga']) : "";
$datumNabavke = isset($_POST['datumNabavke']) ? trim($_POST['datumNabavke']) : "";
$dobavljac = isset($_POST['dobavljac']) ? trim($_POST['dobavljac']) : "";
$napomena = isset($_POST['napomena']) ? trim($_POST['napomena']) : "";
$nalogEvidentirao = $korisnik;

$sifraIgreNiz = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : array();
$kolicinaNiz = isset($_POST['kolicina']) ? $_POST['kolicina'] : array();
$cenaNiz = isset($_POST['cena']) ? $_POST['cena'] : array();

if ($brojNaloga == "" || $datumNabavke == "" || $dobavljac == "") {
    prekiniSaGreskom("Greška: Sva obavezna polja o nalogu moraju biti popunjena.", $povratakUrl);
}

if (strlen($brojNaloga) > 50) {
    prekiniSaGreskom("Greška: Broj naloga ne sme biti duzi od 50 karaktera.", $povratakUrl);
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumNabavke);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumNabavke) {
    prekiniSaGreskom("Greška: Datum nabavke nije ispravan.", $povratakUrl);
}

if (strlen($dobavljac) > 100) {
    prekiniSaGreskom("Greška: Dobavljac ne sme biti duzi od 100 karaktera.", $povratakUrl);
}

if (strlen($napomena) > 255) {
    prekiniSaGreskom("Greška: Napomena ne sme biti duza od 255 karaktera.", $povratakUrl);
}

if ($nalogEvidentirao == "") {
    prekiniSaGreskom("Greška: Nalog evidentirao nije dostupan iz sesije.", $povratakUrl);
}

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Greška: Nalog mora imati najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz)) {
    prekiniSaGreskom("Greška: Podaci o stavkama naloga nisu ispravno prosledjeni.", $povratakUrl);
}

require_once __DIR__ . '/../../model/entiteti/DrustvenaIgraEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaNabavkeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/NabavkaEntitet.php';
require_once __DIR__ . '/../../kontroler/stranice/NabavkeController.php';

$NabavkaEntitet = new NabavkaEntitet($brojNaloga, $datumNabavke, $dobavljac, $napomena, $nalogEvidentirao);

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "") {
        prekiniSaGreskom("Greška: Sva polja u stavkama naloga moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
        prekiniSaGreskom("Greška: Sifra igre mora biti alfanumericka i do 13 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        prekiniSaGreskom("Greška: Kolicina mora biti ceo broj veci od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        prekiniSaGreskom("Greška: Cena mora biti pozitivna decimalna vrednost veca od 0.", $povratakUrl);
    }

    $DrustvenaIgraEntitet = new DrustvenaIgraEntitet($sifraIgre);
    $StavkaEntitet = new StavkaNabavkeEntitet($DrustvenaIgraEntitet, (int)$kolicina, $cena);
    $NabavkaEntitet->DodajStavku($StavkaEntitet);
}

$NabavkeController = new NabavkeController();

if (!$NabavkeController->DajKonekcijaObject()->konekcijaDB) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Nije uspostavljena konekcija ka bazi podataka.", $povratakUrl);
}

if ($NabavkeController->PostojiBrojNaloga($brojNaloga)) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Greška: Nalog sa tim brojem već postoji.", $povratakUrl);
}

foreach ($NabavkaEntitet->ListaStavki as $stavka) {
    if (!$NabavkeController->IgraPostojiUKatalogu($stavka->DrustvenaIgra->SifraIgre)) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greška: Sifra igre '" . htmlspecialchars($stavka->DrustvenaIgra->SifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }
}

$rezultatSnimanja = $NabavkeController->SnimiNovuNabavku($NabavkaEntitet);

if (!$rezultatSnimanja['uspeh']) {
    echo "Greška pri snimanju naloga.";
    echo "<br>";
    echo $rezultatSnimanja['greska'];
    echo "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>";
    $NabavkeController->ZatvoriKonekciju();
} else {
    $NabavkeController->ZatvoriKonekciju();
    header("Location:../../Ruter.php?stranica=nabavke");
    exit();
}
?>
