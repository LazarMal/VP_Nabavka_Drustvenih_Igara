<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=reklamacije';

if (!isset($_POST['IDReklamacije']) || trim($_POST['IDReklamacije']) === "") {
    die("Greska: Nije izabrana reklamacija za brisanje.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$IDReklamacije = trim($_POST['IDReklamacije']);

require_once __DIR__ . '/../../kontroler/stranice/ReklamacijeController.php';

$ReklamacijeController = new ReklamacijeController();

if (!$ReklamacijeController->DajKonekcijaObject()->konekcijaDB) {
    $ReklamacijeController->ZatvoriKonekciju();
    die("Nije uspostavljena konekcija ka bazi podataka.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

if ($ReklamacijeController->DajReklamacijuPoID($IDReklamacije) == null) {
    $ReklamacijeController->ZatvoriKonekciju();
    die("Greska: Reklamacija ne postoji.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$UtvrdjenaGreska = $ReklamacijeController->ObrisiReklamaciju($IDReklamacije);

$ReklamacijeController->ZatvoriKonekciju();

if ($UtvrdjenaGreska != "") {
    echo "Greska: " . $UtvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>";
} else {
    header('Location:' . $povratakUrl);
    exit();
}
?>
