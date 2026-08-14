<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

function prekiniSaGreskom($poruka, $povratakUrl)
{
    die($poruka . "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$IDReklamacije = isset($_POST['IDReklamacije']) ? trim($_POST['IDReklamacije']) : "";
$brojReklamacije = isset($_POST['brojReklamacije']) ? trim($_POST['brojReklamacije']) : "";
$datumReklamacije = isset($_POST['datumReklamacije']) ? trim($_POST['datumReklamacije']) : "";
$dobavljac = isset($_POST['dobavljac']) ? trim($_POST['dobavljac']) : "";
$napomena = isset($_POST['napomena']) ? trim($_POST['napomena']) : "";

$idStavkeReklamacijeNiz = isset($_POST['idStavkeReklamacije']) ? $_POST['idStavkeReklamacije'] : array();
$sifraIgreNiz = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : array();
$kolicinaNiz = isset($_POST['kolicina']) ? $_POST['kolicina'] : array();
$cenaNiz = isset($_POST['cena']) ? $_POST['cena'] : array();
$razlogReklamacijeNiz = isset($_POST['razlogReklamacije']) ? $_POST['razlogReklamacije'] : array();

$povratakUrl = '../../Ruter.php?stranica=reklamacijaIzmeniForm&id=' . urlencode($IDReklamacije);

if ($IDReklamacije == "") {
    prekiniSaGreskom("Greska: Nije izabrana reklamacija za izmenu.", '../../Ruter.php?stranica=reklamacije');
}

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

if (!is_array($sifraIgreNiz) || count($sifraIgreNiz) == 0) {
    prekiniSaGreskom("Greska: Reklamacija mora imati najmanje jednu stavku.", $povratakUrl);
}

if (count($sifraIgreNiz) != count($kolicinaNiz) || count($sifraIgreNiz) != count($cenaNiz) || count($sifraIgreNiz) != count($razlogReklamacijeNiz) || count($sifraIgreNiz) != count($idStavkeReklamacijeNiz)) {
    prekiniSaGreskom("Greska: Podaci o stavkama reklamacije nisu ispravno prosledjeni.", $povratakUrl);
}

require_once __DIR__ . '/../../kontroler/stranice/ReklamacijeController.php';

$ReklamacijeController = new ReklamacijeController();

if (!$ReklamacijeController->DajKonekcijaObject()->konekcijaDB) {
    $ReklamacijeController->ZatvoriKonekciju();
    prekiniSaGreskom("Nije uspostavljena konekcija ka bazi podataka.", $povratakUrl);
}

if ($ReklamacijeController->DajReklamacijuPoID($IDReklamacije) == null) {
    $ReklamacijeController->ZatvoriKonekciju();
    prekiniSaGreskom("Greska: Reklamacija ne postoji.", '../../Ruter.php?stranica=reklamacije');
}

if ($ReklamacijeController->PostojiBrojReklamacijeOsim($brojReklamacije, $IDReklamacije)) {
    $ReklamacijeController->ZatvoriKonekciju();
    prekiniSaGreskom("Greska: Reklamacija sa tim brojem vec postoji.", $povratakUrl);
}

$stavkeZaSnimanje = array();

for ($i = 0; $i < count($sifraIgreNiz); $i++) {
    $idStavkeReklamacije = trim($idStavkeReklamacijeNiz[$i]);
    $sifraIgre = trim($sifraIgreNiz[$i]);
    $kolicina = trim($kolicinaNiz[$i]);
    $cena = trim($cenaNiz[$i]);
    $razlogReklamacije = trim($razlogReklamacijeNiz[$i]);

    if ($sifraIgre == "" || $kolicina === "" || $cena === "" || $razlogReklamacije === "") {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Sva polja u stavkama reklamacije moraju biti popunjena.", $povratakUrl);
    }

    if (strlen($razlogReklamacije) > 255) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Razlog reklamacije ne sme biti duzi od 255 karaktera.", $povratakUrl);
    }

    if (!is_numeric($kolicina) || (string)(int)$kolicina !== $kolicina || (int)$kolicina <= 0) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Kolicina mora biti ceo broj veci od 0.", $povratakUrl);
    }

    if (filter_var($cena, FILTER_VALIDATE_FLOAT) === false || (float)$cena <= 0) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Cena mora biti pozitivna decimalna vrednost veca od 0.", $povratakUrl);
    }

    if (!$ReklamacijeController->IgraPostojiUKatalogu($sifraIgre)) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Sifra igre '" . htmlspecialchars($sifraIgre) . "' ne postoji u katalogu.", $povratakUrl);
    }

    if ($idStavkeReklamacije != "" && !$ReklamacijeController->StavkaPripadaReklamaciji($idStavkeReklamacije, $IDReklamacije)) {
        $ReklamacijeController->ZatvoriKonekciju();
        prekiniSaGreskom("Greska: Stavka ne pripada izabranoj reklamaciji.", $povratakUrl);
    }

    $stavkeZaSnimanje[] = array(
        "IDStavkeReklamacije" => $idStavkeReklamacije,
        "SifraIgre" => $sifraIgre,
        "Kolicina" => (int)$kolicina,
        "Cena" => $cena,
        "RazlogReklamacije" => $razlogReklamacije
    );
}

$rezultatSnimanja = $ReklamacijeController->IzmeniReklamaciju(
    $IDReklamacije,
    $brojReklamacije,
    $datumReklamacije,
    $dobavljac,
    $napomena,
    $stavkeZaSnimanje
);

if (!$rezultatSnimanja['uspeh']) {
    echo "Greska pri snimanju izmena reklamacije.";
    echo "<br>";
    echo $rezultatSnimanja['greska'];
    echo "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>";
    $ReklamacijeController->ZatvoriKonekciju();
} else {
    $ReklamacijeController->ZatvoriKonekciju();
    header("Location:../../Ruter.php?stranica=reklamacijaDetalj&id=" . urlencode($IDReklamacije));
    exit();
}
?>
