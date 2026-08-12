<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

// PREUZIMANJE PODATAKA SA FORME
$datumNabavke = isset($_POST['datumNabavke']) ? trim($_POST['datumNabavke']) : "";
$dobavljac = isset($_POST['dobavljac']) ? trim($_POST['dobavljac']) : "";
$napomena = isset($_POST['napomena']) ? trim($_POST['napomena']) : "";

$isbnNiz = isset($_POST['isbn']) ? $_POST['isbn'] : array();
$kolicinaNiz = isset($_POST['kolicina']) ? $_POST['kolicina'] : array();
$cenaNiz = isset($_POST['cena']) ? $_POST['cena'] : array();

// SERVER SIDE VALIDACIJE
$dozvoljeniDobavljaci = array(
    "Laguna",
    "Delfi knjižare",
    "Vulkan izdavaštvo",
    "Klett",
    "Zavod za udžbenike",
    "Službeni glasnik"
);

if ($datumNabavke == "" || $dobavljac == "") {
    die("Грешка: Сва обавезна поља о набавци морају бити попуњена.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumNabavke);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumNabavke) {
    die("Грешка: Датум набавке није исправан.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

if (!in_array($dobavljac, $dozvoljeniDobavljaci)) {
    die("Грешка: Изабрани добављач није у дозвољеном домену вредности.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

if (strlen($napomena) > 255) {
    die("Грешка: Напомена не сме бити дужа од 255 карактера.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

if (!is_array($isbnNiz) || count($isbnNiz) == 0) {
    die("Грешка: Набавка мора имати најмање једну ставку.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

if (count($isbnNiz) != count($kolicinaNiz) || count($isbnNiz) != count($cenaNiz)) {
    die("Грешка: Подаци о ставкама набавке нису исправно прослеђени.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

// KLASE
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';

require_once __DIR__ . '/../../model/entiteti/KnjigaEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaNabavkeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/NabavkaEntitet.php';

require_once __DIR__ . "/../../repozitorijumi/DBNabavka.php";
require_once __DIR__ . "/../../repozitorijumi/DBStavkaNabavke.php";

// KREIRANJE ENTITETA - KOMPOZICIJA
$NabavkaEntitet = new NabavkaEntitet($datumNabavke, $dobavljac, $napomena);

$provereniISBN = array();
// VALIDACIJA STAVKI + ASOCIJACIJA
for ($i = 0; $i < count($isbnNiz); $i++) {
    $isbn = trim($isbnNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if (in_array($isbn, $provereniISBN)) {
    die("Грешка: Иста књига не може бити унета више пута у оквиру исте набавке.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
}

$provereniISBN[] = $isbn;

    if ($isbn == "" || $kolicina == "" || $cena == "") {
        die("Грешка: Сва поља у ставкама набавке морају бити попуњена.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
    }

    if (!preg_match('/^[0-9]{13}$/', $isbn)) {
        die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
    }

    if (!is_numeric($kolicina) || $kolicina <= 0 || $kolicina > 100) {
        die("Грешка: Количина мора бити број у опсегу од 1 до 100.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
    }

    if (!is_numeric($cena) || $cena <= 0 || $cena > 100000) {
        die("Грешка: Цена мора бити број у опсегу од 1 до 100000.<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>");
    }

    $KnjigaEntitet = new KnjigaEntitet($isbn);
    $StavkaEntitet = new StavkaNabavkeEntitet($KnjigaEntitet, $kolicina, $cena);

    $NabavkaEntitet->DodajStavku($StavkaEntitet);
}

// KONEKCIJA
$KonekcijaObject = new Konekcija(__DIR__ . "/../../tehnoloskeKlase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    die("Није успостављена конекција ка бази података.");
}

$konekcija = $KonekcijaObject->konekcijaDB;

$datumNabavke = mysqli_real_escape_string($konekcija, $datumNabavke);
$dobavljac = mysqli_real_escape_string($konekcija, $dobavljac);
$napomena = mysqli_real_escape_string($konekcija, $napomena);

$TransakcijaObject = new Transakcija($KonekcijaObject);
$TransakcijaObject->ZapocniTransakciju();

$NabavkaObject = new DBNabavka($KonekcijaObject, "nabavka");
$StavkaObject = new DBStavkaNabavke($KonekcijaObject, "stavka_nabavke");

// proverava da li već postoji nabavka sa istim datumom i dobavljačem
$idNabavke = $NabavkaObject->PronadjiNabavku($datumNabavke, $dobavljac);

$utvrdjenaGreska = "";

if ($idNabavke == null) {
    $utvrdjenaGreska .= $NabavkaObject->DodajNabavku($datumNabavke, $dobavljac, $napomena);
    $idNabavke = $NabavkaObject->DajPoslednjiID();
}

// snimanje svih stavki nabavke u istoj transakciji
foreach ($NabavkaEntitet->ListaStavki as $stavka) {
    $isbn = mysqli_real_escape_string($konekcija, $stavka->Knjiga->ISBN);
    $kolicina = mysqli_real_escape_string($konekcija, $stavka->Kolicina);
    $cena = mysqli_real_escape_string($konekcija, $stavka->Cena);

    $utvrdjenaGreska .= $StavkaObject->DodajStavkuNabavke($idNabavke, $isbn, $kolicina, $cena);
}

$TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

if ($utvrdjenaGreska != "") {
    echo "Грешка приликом снимања набавке.";
    echo "<br>";
    echo $utvrdjenaGreska;
    echo "<br><br><a href='../ruter.php?stranica=novaNabavka'>ПОВРАТАК</a>";
} else {
    header("Location:../../ruter.php?stranica=nabavke");
    exit();
}

$KonekcijaObject->disconnect();
?>