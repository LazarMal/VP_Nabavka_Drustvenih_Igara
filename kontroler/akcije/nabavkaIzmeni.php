<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

function prekiniSaGreskom($poruka, $povratakUrl)
{
    die($poruka . "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
}

$IDNabavke = isset($_POST['IDNabavke']) ? trim($_POST['IDNabavke']) : "";
$brojNaloga = isset($_POST['brojNaloga']) ? trim($_POST['brojNaloga']) : "";
$datumNabavke = isset($_POST['datumNabavke']) ? trim($_POST['datumNabavke']) : "";
$dobavljac = isset($_POST['dobavljac']) ? trim($_POST['dobavljac']) : "";
$napomena = isset($_POST['napomena']) ? trim($_POST['napomena']) : "";

$idStavkeNiz = isset($_POST['idStavke']) ? $_POST['idStavke'] : array();
$sifraIgreNiz = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : array();
$kolicinaNiz = isset($_POST['kolicina']) ? $_POST['kolicina'] : array();
$cenaNiz = isset($_POST['cena']) ? $_POST['cena'] : array();

$povratakUrl = '../Ruter.php?stranica=nabavkaIzmeniForm&id=' . urlencode($IDNabavke);

if ($IDNabavke == "") {
    prekiniSaGreskom("Грешка: Није изабран nalog za izmenu.", '../Ruter.php?stranica=nabavke');
}

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

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Грешка: Нalog мора имати najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz) || count($sifraIgreNiz) != count($idStavkeNiz)) {
    prekiniSaGreskom("Грешка: Подаци o stavkama naloga nisu ispravno prosleđeni.", $povratakUrl);
}

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
require_once __DIR__ . '/../../model/entiteti/KnjigaEntitet.php';
require_once __DIR__ . '/../../model/entiteti/StavkaNabavkeEntitet.php';
require_once __DIR__ . '/../../model/entiteti/NabavkaEntitet.php';
require_once __DIR__ . "/../../repozitorijumi/DBNabavka.php";
require_once __DIR__ . "/../../repozitorijumi/DBStavkaNabavke.php";

$KonekcijaObject = new Konekcija(__DIR__ . "/../../tehnoloskeKlase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    prekiniSaGreskom("Није успостављена конекција ка бази података.", $povratakUrl);
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$IDNabavkeEsc = mysqli_real_escape_string($konekcija, $IDNabavke);
$brojNalogaEsc = mysqli_real_escape_string($konekcija, $brojNaloga);
$datumNabavkeEsc = mysqli_real_escape_string($konekcija, $datumNabavke);
$dobavljacEsc = mysqli_real_escape_string($konekcija, $dobavljac);
$napomenaEsc = mysqli_real_escape_string($konekcija, $napomena);

$NabavkaObject = new DBNabavka($KonekcijaObject, "nabavka");
$StavkaObject = new DBStavkaNabavke($KonekcijaObject, "stavka_nabavke");

$postojecaNabavka = $NabavkaObject->DajNabavkuKaoModel($IDNabavkeEsc);

if ($postojecaNabavka == null) {
    $KonekcijaObject->disconnect();
    prekiniSaGreskom("Грешка: Nalog ne postoji.", '../Ruter.php?stranica=nabavke');
}

if ($NabavkaObject->PostojiBrojNalogaOsim($brojNalogaEsc, $IDNabavkeEsc)) {
    $KonekcijaObject->disconnect();
    prekiniSaGreskom("Грешка: Nalog sa tim brojem već postoji.", $povratakUrl);
}

$stavkeZaSnimanje = array();

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $idStavke = trim($idStavkeNiz[$i]);
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "") {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Сва поља u stavkama naloga moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Шифра igre mora biti alfanumerička i do 13 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Količina mora biti ceo broj veći od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Cena mora biti pozitivna decimalna vrednost veća od 0.", $povratakUrl);
    }

    $sifraProvera = mysqli_real_escape_string($konekcija, $sifraIgre);
    $rezultatIgre = mysqli_query(
        $konekcija,
        "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='$sifraProvera' LIMIT 1"
    );

    if (!$rezultatIgre || mysqli_num_rows($rezultatIgre) == 0) {
        $KonekcijaObject->disconnect();
        prekiniSaGreskom("Грешка: Šifra igre '" . htmlspecialchars($sifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }

    if ($idStavke != "") {
        $idStavkeEsc = mysqli_real_escape_string($konekcija, $idStavke);
        $proveraStavke = mysqli_query(
            $konekcija,
            "SELECT IDStavke FROM `$baza`.`stavka_nabavke` WHERE IDStavke='$idStavkeEsc' AND IDNabavke='$IDNabavkeEsc' LIMIT 1"
        );

        if (!$proveraStavke || mysqli_num_rows($proveraStavke) == 0) {
            $KonekcijaObject->disconnect();
            prekiniSaGreskom("Грешка: Stavka ne pripada izabranom nalogu.", $povratakUrl);
        }
    }

    $stavkeZaSnimanje[] = array(
        "IDStavke" => $idStavke,
        "SifraIgre" => $sifraIgre,
        "Kolicina" => (int)$kolicina,
        "Cena" => $cena
    );
}

$TransakcijaObject = new Transakcija($KonekcijaObject);
$TransakcijaObject->ZapocniTransakciju();

$utvrdjenaGreska = "";

$postojeceStavkeIds = $StavkaObject->DajIDStavkiZaNabavku($IDNabavkeEsc);

$utvrdjenaGreska .= $NabavkaObject->IzmeniNabavku(
    $IDNabavkeEsc,
    $brojNalogaEsc,
    $datumNabavkeEsc,
    $dobavljacEsc,
    $napomenaEsc
);

$poslateStavkeIds = array();

foreach ($stavkeZaSnimanje as $stavka) {
    $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka['SifraIgre']);
    $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka['Kolicina']);
    $cenaEsc = mysqli_real_escape_string($konekcija, $stavka['Cena']);

    if ($stavka['IDStavke'] != "") {
        $idStavkeEsc = mysqli_real_escape_string($konekcija, $stavka['IDStavke']);
        $poslateStavkeIds[] = $idStavkeEsc;
        $utvrdjenaGreska .= $StavkaObject->IzmeniStavkuNabavke(
            $idStavkeEsc,
            $IDNabavkeEsc,
            $sifraIgreEsc,
            $kolicinaEsc,
            $cenaEsc
        );
    } else {
        $utvrdjenaGreska .= $StavkaObject->DodajStavkuNabavke(
            $IDNabavkeEsc,
            $sifraIgreEsc,
            $kolicinaEsc,
            $cenaEsc
        );
    }
}

foreach ($postojeceStavkeIds as $postojeciId) {
    if (!in_array($postojeciId, $poslateStavkeIds)) {
        $idZaBrisanjeEsc = mysqli_real_escape_string($konekcija, $postojeciId);
        $utvrdjenaGreska .= $StavkaObject->ObrisiStavkuNabavke($idZaBrisanjeEsc, $IDNabavkeEsc);
    }
}

$TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

if ($utvrdjenaGreska != "") {
    echo "Грешка pri snimanju izmena naloga.";
    echo "<br>";
    echo $utvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
} else {
    header("Location:../../Ruter.php?stranica=nabavkaDetalj&id=" . urlencode($IDNabavke));
    exit();
}

$KonekcijaObject->disconnect();
?>
