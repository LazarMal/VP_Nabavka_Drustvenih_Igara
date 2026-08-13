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
    prekiniSaGreskom("Грешка: Nije izabran nalog za izmenu.", '../Ruter.php?stranica=nabavke');
}

if ($brojNaloga == "" || $datumNabavke == "" || $dobavljac == "") {
    prekiniSaGreskom("Грешка: Sva obavezna polja o nalogu moraju biti popunjena.", $povratakUrl);
}

if (strlen($brojNaloga) > 50) {
    prekiniSaGreskom("Грешка: Broj naloga ne sme biti duži od 50 karaktera.", $povratakUrl);
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumNabavke);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumNabavke) {
    prekiniSaGreskom("Грешка: Datum nabavke nije ispravan.", $povratakUrl);
}

if (strlen($dobavljac) > 100) {
    prekiniSaGreskom("Грешка: Dobavljač ne sme biti duži od 100 karaktera.", $povratakUrl);
}

if (strlen($napomena) > 255) {
    prekiniSaGreskom("Грешka: Napomena ne sme biti duža od 255 karaktera.", $povratakUrl);
}

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Грешка: Nalog mora imati najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz) || count($sifraIgreNiz) != count($idStavkeNiz)) {
    prekiniSaGreskom("Грешка: Podaci o stavkama naloga nisu ispravno prosleđeni.", $povratakUrl);
}

require_once __DIR__ . '/../../kontroler/stranice/NabavkeController.php';

$NabavkeController = new NabavkeController();

if (!$NabavkeController->DajKonekcijaObject()->konekcijaDB) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Nije uspostavljena konekcija ka bazi podataka.", $povratakUrl);
}

if ($NabavkeController->DajNabavkuPoID($IDNabavke) == null) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Грешка: Nalog ne postoji.", '../Ruter.php?stranica=nabavke');
}

if ($NabavkeController->PostojiBrojNalogaOsim($brojNaloga, $IDNabavke)) {
    $NabavkeController->ZatvoriKonekciju();
    prekiniSaGreskom("Грешка: Nalog sa tim brojem već postoji.", $povratakUrl);
}

$stavkeZaSnimanje = array();

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $idStavke = trim($idStavkeNiz[$i]);
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "") {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Sva polja u stavkama naloga moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($sifraIgre) > 13 || !preg_match('/^[A-Za-z0-9]+$/', $sifraIgre)) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Šifra igre mora biti alfanumerička i do 13 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Količina mora biti ceo broj veći od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Cena mora biti pozitivna decimalna vrednost veća od 0.", $povratakUrl);
    }

    if (!$NabavkeController->IgraPostojiUKatalogu($sifraIgre)) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Šifra igre '" . htmlspecialchars($sifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }

    if ($idStavke != "" && !$NabavkeController->StavkaPripadaNalogu($idStavke, $IDNabavke)) {
        $NabavkeController->ZatvoriKonekciju();
        prekiniSaGreskom("Грешка: Stavka ne pripada izabranom nalogu.", $povratakUrl);
    }

    $stavkeZaSnimanje[] = array(
        "IDStavke" => $idStavke,
        "SifraIgre" => $sifraIgre,
        "Kolicina" => (int)$kolicina,
        "Cena" => $cena
    );
}

$rezultatSnimanja = $NabavkeController->IzmeniNabavku(
    $IDNabavke,
    $brojNaloga,
    $datumNabavke,
    $dobavljac,
    $napomena,
    $stavkeZaSnimanje
);

if (!$rezultatSnimanja['uspeh']) {
    echo "Грешка pri snimanju izmena naloga.";
    echo "<br>";
    echo $rezultatSnimanja['greska'];
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
    $NabavkeController->ZatvoriKonekciju();
} else {
    $NabavkeController->ZatvoriKonekciju();
    header("Location:../../Ruter.php?stranica=nabavkaDetalj&id=" . urlencode($IDNabavke));
    exit();
}
?>
