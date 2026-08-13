<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$povratakUrl = '../../Ruter.php?stranica=prijava';

if (!isset($_POST['korisnickoIme']) || !isset($_POST['sifra'])) {
    die("Greška: Korisničko ime i lozinka su obavezna.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

$loginUserName = trim($_POST['korisnickoIme']);
$loginPassword = trim($_POST['sifra']);

if ($loginUserName === "" || $loginPassword === "") {
    die("Greška: Korisničko ime i lozinka moraju biti popunjeni.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

if (strlen($loginUserName) > 30 || strlen($loginPassword) > 30) {
    die("Greška: Korisničko ime i lozinka ne smeju biti duži od 30 karaktera.<br><br><a href=\"" . $povratakUrl . "\">POVRATAK</a>");
}

require __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require __DIR__ . '/../../repozitorijumi/DBKorisnik.php';

$objKonekcija = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$objKonekcija->connect();

if ($objKonekcija->konekcijaDB) {
    $objKorisnik = new DBKorisnik($objKonekcija, 'korisnik');
    $postojiKorisnik = $objKorisnik->DaLiPostojiKorisnik($loginUserName, $loginPassword);

    if ($postojiKorisnik == "DA") {
        $_SESSION["prez"] = $objKorisnik->DajPrezimePrijavljenogKorisnika($loginUserName, $loginPassword);
        $_SESSION["ime"] = $objKorisnik->DajImePrijavljenogKorisnika($loginUserName, $loginPassword);
        $_SESSION["idkorisnika"] = $objKorisnik->DajIDPrijavljenogKorisnika($loginUserName, $loginPassword);
        $_SESSION["korisnik"] = $objKorisnik->DajImePrezimePrijavljenogKorisnika($loginUserName, $loginPassword);
        header('Location:../../Ruter.php?stranica=welcome');
        exit();
    }

    $_SESSION['login_greska'] = 'Pogrešno korisničko ime ili lozinka.';
    header('Location:../../Ruter.php?stranica=prijava');
    exit();
}

echo "Neuspeh konekcije na bazu podataka!";
?>
