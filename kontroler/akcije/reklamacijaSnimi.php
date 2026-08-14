<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=novaReklamacija';

function prekiniSaGreskom($poruka, $povratakUrl)
{
    die($poruka . "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$brojReklamacije = isset($_POST['brojReklamacije']) ? trim($_POST['brojReklamacije']) : "";
$datumReklamacije = isset($_POST['datumReklamacije']) ? trim($_POST['datumReklamacije']) : "";
$dobavljac = isset($_POST['dobavljac']) ? trim($_POST['dobavljac']) : "";
$napomena = isset($_POST['napomena']) ? trim($_POST['napomena']) : "";
$reklamacijuEvidentirao = $korisnik;

$sifraIgreNiz = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : array();
$kolicinaNiz = isset($_POST['kolicina']) ? $_POST['kolicina'] : array();
$cenaNiz = isset($_POST['cena']) ? $_POST['cena'] : array();
$razlogReklamacijeNiz = isset($_POST['razlogReklamacije']) ? $_POST['razlogReklamacije'] : array();

if ($brojReklamacije == "" || $datumReklamacije == "" || $dobavljac == "") {
    prekiniSaGreskom("Greska: Sva obavezna polja o reklamaciji moraju biti popunjena.", $povratakUrl);
}

if (strlen($brojReklamacije) > 50) {
    prekiniSaGreskom("Greska: Broj reklamacije ne sme biti duzi od 50 karaktera.", $povratakUrl);
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumReklamacije);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumReklamacije) {
    prekiniSaGreskom("Greska: Datum reklamacije nije ispravan.", $povratakUrl);
}

if (strlen($dobavljac) > 100) {
    prekiniSaGreskom("Greska: Dobavljac ne sme biti duzi od 100 karaktera.", $povratakUrl);
}

if (strlen($napomena) > 255) {
    prekiniSaGreskom("Greska: Napomena ne sme biti duza od 255 karaktera.", $povratakUrl);
}

if ($reklamacijuEvidentirao == "") {
    prekiniSaGreskom("Greska: Reklamaciju evidentirao nije dostupan iz sesije.", $povratakUrl);
}

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Greska: Reklamacija mora imati najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz) || count($sifraIgreNiz) != count($razlogReklamacijeNiz)) {
    prekiniSaGreskom("Greska: Podaci o stavkama reklamacije nisu ispravno prosledjeni.", $povratakUrl);
}

require_once __DIR__ . '/../../model/entiteti/DrustvenaIgraEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaReklamacijeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/ReklamacijaEntitet.php';
require_once __DIR__ . '/../../kontroler/stranice/ReklamacijeController.php';

$ReklamacijaEntitet = new ReklamacijaEntitet($brojReklamacije, $datumReklamacije, $dobavljac, $napomena, $reklamacijuEvidentirao);

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);
    $razlogReklamacije = trim($razlogReklamacijeNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "" || $razlogReklamacije === "") {
        prekiniSaGreskom("Greska: Sva polja u stavkama reklamacije moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($razlogReklamacije) > 255) {
        prekiniSaGreskom("Greska: Razlog reklamacije ne sme biti duzi od 255 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        prekiniSaGreskom("Greska: Kolicina mora biti ceo broj veci od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        prekiniSaGreskom("Greska: Cena mora biti pozitivna decimalna vrednost veca od 0.", $povratakUrl);
    }

    $DrustvenaIgraEntitet = new DrustvenaIgraEntitet($sifraIgre);
    $StavkaEntitet = new StavkaReklamacijeEntitet($DrustvenaIgraEntitet, (int)$kolicina, $cena, $razlogReklamacije);
    $ReklamacijaEntitet->DodajStavku($StavkaEntitet);
}

$ReklamacijeController = new ReklamacijeController();

if (!$ReklamacijeController->DajKonekcijaObject()->konekcijaDB) {
    $ReklamacijeController->ZatvoriKonekciju();
    prekiniSaGreskom("Nije uspostavljena konekcija ka bazi podataka.", $povratakUrl);
}

if ($ReklamacijeController->PostojiBrojReklamacije($brojReklamacije)) {
    $ReklamacijeController->ZatvoriKonekciju();
    prekiniSaGreskom("Greska: Reklamacija sa tim brojem vec postoji.", $povratakUrl);
}

foreach ($ReklamacijaEntitet->ListaStavki as $stavka) {
    if (!$ReklamacijeController->IgraPostojiUKatalogu($stavka->DrustvenaIgra->SifraIgre)) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Sifra igre '" . htmlspecialchars($stavka->DrustvenaIgra->SifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }
}

$rezultatSnimanja = $ReklamacijeController->SnimiNovuReklamaciju($ReklamacijaEntitet);

if (!$rezultatSnimanja['uspeh']) {
    echo "Greska pri snimanju reklamacije.";
    echo "<br>";
    echo $rezultatSnimanja['greska'];
    echo "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>";
    $ReklamacijeController->ZatvoriKonekciju();
} else {
    $ReklamacijeController->ZatvoriKonekciju();
    header("Location:../../Ruter.php?stranica=reklamacije");
    exit();
}
?>
