<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$povratakUrl = '../../Ruter.php?stranica=nabavke';

if (!isset($_POST['IDNabavke']) || trim($_POST['IDNabavke']) === "") {
    die("Greska: Nije izabran nalog za brisanje.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$IDNabavke = trim($_POST['IDNabavke']);

require_once __DIR__ . '/../../kontroler/stranice/NabavkeController.php';

$NabavkeController = new NabavkeController();

if (!$NabavkeController->DajKonekcijaObject()->konekcijaDB) {
    $NabavkeController->ZatvoriKonekciju();
    die("Nije uspostavljena konekcija ka bazi podataka.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

if ($NabavkeController->DajNabavkuPoID($IDNabavke) == null) {
    $NabavkeController->ZatvoriKonekciju();
    die("Greska: Nalog ne postoji.<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>");
}

$UtvrdjenaGreska = $NabavkeController->ObrisiNabavku($IDNabavke);

$NabavkeController->ZatvoriKonekciju();

if ($UtvrdjenaGreska != "") {
    echo "Greska: " . $UtvrdjenaGreska;
    echo "<br><br><a href='" . $povratakUrl . "'>POVRATAK</a>";
} else {
    header('Location:' . $povratakUrl);
    exit();
}
?>
