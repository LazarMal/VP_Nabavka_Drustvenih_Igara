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
    prekiniSaGreskom("Грешка: Налог evidentirao није доступан из сесије.", $povratakUrl);
}

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Грешка: Налог мора имати најмање једну ставку.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz)) {
    prekiniSaGreskom("Грешка: Подаци о ставкама налога нису исправно прослеђени.", $povratakUrl);
}

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
require_once __DIR__ . '/../../model/entiteti/KnjigaEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaNabavkeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/NabavkaEntitet.php';
require_once __DIR__ . "/../../repozitorijumi/DBNabavka.php";
require_once __DIR__ . "/../../repozitorijumi/DBStavkaNabavke.php";

$NabavkaEntitet = new NabavkaEntitet($brojNaloga, $datumNabavke, $dobavljac, $napomena, $nalogEvidentirao);

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "") {
        prekiniSaGreskom("Грешка: Сва поља у ставкама налога морају бити попуњена.", $povratakUrl);
    }

    if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
        prekiniSaGreskom("Грешка: Шифра игре мора бити алфанумеричка и до 13 карактера.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (int)$kolicina != $kolicina || (int)$kolicina <= 0) {
        prekiniSaGreskom("Грешка: Количина мора бити цео број већи од 0.", $povratakUrl);
    }

    if (!is_numeric($cena) || $cena <= 0) {
        prekiniSaGreskom("Грешка: Цена мора бити број већи од 0.", $povratakUrl);
    }

    $KnjigaEntitet = new KnjigaEntitet($sifraIgre);
    $StavkaEntitet = new StavkaNabavkeEntitet($KnjigaEntitet, (int)$kolicina, $cena);
    $NabavkaEntitet->DodajStavku($StavkaEntitet);
}

$KonekcijaObject = new Konekcija(__DIR__ . "/../../tehnoloskeKlase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    prekiniSaGreskom("Није успостављена конекција ка бази података.", $povratakUrl);
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$brojNalogaEsc = mysqli_real_escape_string($konekcija, $brojNaloga);
$datumNabavkeEsc = mysqli_real_escape_string($konekcija, $datumNabavke);
$dobavljacEsc = mysqli_real_escape_string($konekcija, $dobavljac);
$napomenaEsc = mysqli_real_escape_string($konekcija, $napomena);
$nalogEvidentiraoEsc = mysqli_real_escape_string($konekcija, $nalogEvidentirao);

$NabavkaObject = new DBNabavka($KonekcijaObject, "nabavka");

if ($NabavkaObject->PostojiBrojNaloga($brojNalogaEsc)) {
    $KonekcijaObject->disconnect();
    prekiniSaGreskom("Грешка: Налог са тим бројем већ постоји.", $povratakUrl);
}

foreach ($NabavkaEntitet->ListaStavki as $stavka) {
    $sifraProvera = mysqli_real_escape_string($konekcija, $stavka->DrustvenaIgra->SifraIgre);
    $rezultatIgre = mysqli_query(
        $konekcija,
        "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$sifraProvera' LIMIT 1"
    );

    if (!$rezultatIgre || mysqli_num_rows($rezultatIgre) == 0) {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Шифра игре '" . htmlspecialchars($stavka->DrustvenaIgra->SifraIgre) . "' не постоји у katalogu.", $povratakUrl);
    }
}

$TransakcijaObject = new Transakcija($KonekcijaObject);
$TransakcijaObject->ZapocniTransakciju();

$StavkaObject = new DBStavkaNabavke($KonekcijaObject, "stavka_nabavke");
$utvrdjenaGreska = "";

$utvrdjenaGreska .= $NabavkaObject->DodajNabavku(
    $brojNalogaEsc,
    $datumNabavkeEsc,
    $dobavljacEsc,
    $napomenaEsc,
    $nalogEvidentiraoEsc
);

$idNabavke = $NabavkaObject->DajPoslednjiID();

if ($utvrdjenaGreska != "" || $idNabavke == null || $idNabavke == "") {
    $TransakcijaObject->ZavrsiTransakciju("Грешка при snimanju glavnog dela naloga.");
    $KonekcijaObject->disconnect();
    prekiniSaGreskom("Грешка приликом снимања glavnog dela naloga.", $povratakUrl);
}

foreach ($NabavkaEntitet->ListaStavki as $stavka) {
    $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka->DrustvenaIgra->SifraIgre);
    $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka->Kolicina);
    $cenaEsc = mysqli_real_escape_string($konekcija, $stavka->Cena);

    $utvrdjenaGreska .= $StavkaObject->DodajStavkuNabavke($idNabavke, $sifraIgreEsc, $kolicinaEsc, $cenaEsc);
}

$TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

if ($utvrdjenaGreska != "") {
    echo "Грешка приликом снимања naloga.";
    echo "<br>";
    echo $utvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
} else {
    header("Location:../../Ruter.php?stranica=nabavke");
    exit();
}

$KonekcijaObject->disconnect();
?>
