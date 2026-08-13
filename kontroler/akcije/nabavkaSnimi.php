<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$povratakUrl = '../Ruter.php?stranica=novaNabavka';

function prekiniSaGreskom($poruka, $povratakUrl)
{
    die($poruka . "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
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
    prekiniSaGreskom("Грешка: Сва обавезна поља о налогу морају бити попуњена.", $povratakUrl);
}

if (strlen($brojNaloga) > 50) {
    prekiniSaGreskom("Грешка: Број naloga не сме бити дужи од 50 карактера.", $povratakUrl);
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumNabavke);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumNabavke) {
    prekiniSaGreskom("Грешка: Датум nabavke није исправан.", $povratakUrl);
}

if (strlen($dobavljac) > 100) {
    prekiniSaGreskom("Грешка: Добављач не сме бити дужи од 100 карактера.", $povratakUrl);
}

if (strlen($napomena) > 255) {
    prekiniSaGreskom("Грешка: Напомена не сме бити дужа од 255 карактера.", $povratakUrl);
}

if ($nalogEvidentirao == "") {
    prekiniSaGreskom("Грешка: Нalog evidentirao nije dostupan iz sesije.", $povratakUrl);
}

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Грешка: Nalog mora imati najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz)) {
    prekiniSaGreskom("Грешка: Podaci o stavkama naloga nisu ispravno prosleđeni.", $povratakUrl);
}

require_once __DIR__ . '/../../model/entiteti/KnjigaEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaNabavkeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/NabavkaEntitet.php';
require_once __DIR__ . '/../../kontroler/stranice/NabavkeController.php';

$NabavkaEntitet = new NabavkaEntitet($brojNaloga, $datumNabavke, $dobavljac, $napomena, $nalogEvidentirao);

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "") {
        prekiniSaGreskom("Грешка: Sva polja u stavkama naloga moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
        prekiniSaGreskom("Грешка: Šifra igre mora biti alfanumerička i do 13 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        prekiniSaGreskom("Грешка: Količina mora biti ceo broj veći od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        prekiniSaGreskom("Грешка: Cena mora biti pozitivna decimalna vrednost veća od 0.", $povratakUrl);
    }

    $KnjigaEntitet = new KnjigaEntitet($sifraIgre);
    $StavkaEntitet = new StavkaNabavkeEntitet($KnjigaEntitet, (int)$kolicina, $cena);
    $NabavkaEntitet->DodajStavku($StavkaEntitet);
}

$NabavkeController = new NabavkeController();

if (!$NabavkeController->DajKonekcijaObject()->konekcijaDB) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Nije uspostavljena konekcija ka bazi podataka.", $povratakUrl);
}

if ($NabavkeController->PostojiBrojNaloga($brojNaloga)) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Грешка: Nalog sa tim brojem već postoji.", $povratakUrl);
}

foreach ($NabavkaEntitet->ListaStavki as $stavka) {
    if (!$NabavkeController->IgraPostojiUKatalogu($stavka->DrustvenaIgra->SifraIgre)) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Šifra igre '" . htmlspecialchars($stavka->DrustvenaIgra->SifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }
}

$rezultatSnimanja = $NabavkeController->SnimiNovuNabavku($NabavkaEntitet);

if (!$rezultatSnimanja['uspeh']) {
    echo "Грешка pri snimanju naloga.";
    echo "<br>";
    echo $rezultatSnimanja['greska'];
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
    $NabavkeController->ZatvoriKonekciju();
} else {
    $NabavkeController->ZatvoriKonekciju();
    header("Location:../../Ruter.php?stranica=nabavke");
    exit();
}
?>
