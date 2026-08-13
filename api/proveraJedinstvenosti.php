<?php
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../tehnoloskeKlase/BaznaKonekcija.php';

$tip = isset($_GET['tip']) ? trim($_GET['tip']) : "";
$vrednost = isset($_GET['vrednost']) ? trim($_GET['vrednost']) : "";
$izuzmiId = isset($_GET['izuzmiId']) ? trim($_GET['izuzmiId']) : "";

if ($tip === "" || $vrednost === "") {
    http_response_code(400);
    echo json_encode(array("greska" => "Parametri tip i vrednost su obavezni"), JSON_UNESCAPED_UNICODE);
    exit();
}

$KonekcijaObject = new Konekcija(__DIR__ . '/../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if (!$KonekcijaObject->konekcijaDB) {
    http_response_code(500);
    echo json_encode(array("greska" => "Nije uspela konekcija sa bazom"), JSON_UNESCAPED_UNICODE);
    exit();
}

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;
$postoji = false;

if ($tip === 'brojNaloga') {
    $vrednostEsc = mysqli_real_escape_string($konekcija, $vrednost);
    $upit = "SELECT BrojNaloga FROM `$baza`.`nabavka` WHERE BrojNaloga='".$vrednostEsc."'";
    if ($izuzmiId !== "") {
        $izuzmiEsc = mysqli_real_escape_string($konekcija, $izuzmiId);
        $upit .= " AND IDNabavke <> '".$izuzmiEsc."'";
    }
    $upit .= " LIMIT 1";
    $rez = mysqli_query($konekcija, $upit);
    $postoji = ($rez && mysqli_num_rows($rez) > 0);
} elseif ($tip === 'sifraIgre') {
    $vrednostEsc = mysqli_real_escape_string($konekcija, $vrednost);
    $upit = "SELECT SifraIgre FROM `$baza`.`drustvena_igra` WHERE SifraIgre='".$vrednostEsc."' LIMIT 1";
    $rez = mysqli_query($konekcija, $upit);
    $postoji = ($rez && mysqli_num_rows($rez) > 0);
} else {
    http_response_code(400);
    echo json_encode(array("greska" => "Nepoznat tip provere"), JSON_UNESCAPED_UNICODE);
    $KonekcijaObject->disconnect();
    exit();
}

http_response_code(200);
echo json_encode(array("postoji" => $postoji), JSON_UNESCAPED_UNICODE);
$KonekcijaObject->disconnect();
?>
