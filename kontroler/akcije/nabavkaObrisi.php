<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=nabavke';

if (!isset($_POST['IDNabavke']) || trim($_POST['IDNabavke']) === "") {
    die("Грешка: Nije izabran nalog za brisanje.<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
}

$IDNabavke = trim($_POST['IDNabavke']);

require_once __DIR__ . '/../../kontroler/stranice/NabavkeController.php';

$NabavkeController = new NabavkeController();

if (!$NabavkeController->DajKonekcijaObject()->konekcijaDB) {
    $NabavkeController->ZatvoriKonekciju();
    die("Nije uspostavljena konekcija ka bazi podataka.<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
}

if ($NabavkeController->DajNabavkuPoID($IDNabavke) == null) {
    $NabavkeController->ZatvoriKonekciju();
    die("Грешка: Nalog ne postoji.<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>");
}

$UtvrdjenaGreska = $NabavkeController->ObrisiNabavku($IDNabavke);

$NabavkeController->ZatvoriKonekciju();

if ($UtvrdjenaGreska != "") {
    echo "Грешка: " . $UtvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>ПОВРАТАК</a>";
} else {
    header('Location:' . $povratakUrl);
    exit();
}
?>
