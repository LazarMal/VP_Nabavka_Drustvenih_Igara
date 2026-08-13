<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

$IdZaBrisanje = $_POST['sifraIgre'];

require __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
require __DIR__ . '/../../repozitorijumi/DBKnjiga.php';
require __DIR__ . '/../../repozitorijumi/DBZanr.php';

$KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

$UtvrdjenaGreska = "";

if ($KonekcijaObject->konekcijaDB) {  
    
    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $IdZaBrisanje = mysqli_real_escape_string($konekcija, $IdZaBrisanje);

    $provera = mysqli_query(
        $konekcija,
        "SELECT COUNT(*) AS broj FROM `$baza`.`stavka_nabavke` WHERE SifraIgre='$IdZaBrisanje'"
    );

    $red = mysqli_fetch_assoc($provera);

    if ($red['broj'] > 0) {
        $KonekcijaObject->disconnect();

        die("Грешка: Игра се не може обрисати јер постоји у евидентираним налозima.<br><br><a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>");
    }

    $TransakcijaObject = new Transakcija($KonekcijaObject);
    $TransakcijaObject->ZapocniTransakciju();

    $KnjigaObject = new DBKnjiga($KonekcijaObject, 'drustvena_igra');

    $OznakaKategorije = $KnjigaObject->DajOznakuZanraKnjige($IdZaBrisanje);
    $greska1 = $KnjigaObject->ObrisiKnjigu($IdZaBrisanje);

    $ZanrObject = new DBZanr($KonekcijaObject, 'kategorija_igre');
    $greska2 = $ZanrObject->DekrementirajBrojKnjiga($OznakaKategorije);

    $UtvrdjenaGreska = $greska1 . $greska2;

    $TransakcijaObject->ZavrsiTransakciju($UtvrdjenaGreska);
}

$KonekcijaObject->disconnect();

if ($UtvrdjenaGreska) {
    echo "Грешка: $UtvrdjenaGreska";
    echo "<br><br>";
    echo "<a href=\"../../Ruter.php?stranica=knjige\">ПОВРАТАК</a>";        
} else {
    header('Location:../../Ruter.php?stranica=knjige');
    exit();
}
?>
