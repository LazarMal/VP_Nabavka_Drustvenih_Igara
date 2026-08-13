<?php
class DBKorisnik extends Tabela{

public $IDKorisnika;
public $Prezime;
public $Ime;
public $Email;
public $KorisnickoIme;
public $Sifra;
public $Stari_IDKorisnika;

private function UcitajKorisnikaPoLoginu($loginusername, $loginpassword)
{
    $konekcija = $this->OtvorenaKonekcija->konekcijaDB;
  $baza = $this->OtvorenaKonekcija->KompletanNazivBazePodataka;
    $stmt = mysqli_prepare($konekcija, "SELECT * FROM `".$baza."`.`KORISNIK` WHERE KORISNICKOIME=? AND SIFRA=? LIMIT 1");

    if (!$stmt) {
        $this->BrojZapisa = 0;
        $this->Kolekcija = array();
        return;
    }

    mysqli_stmt_bind_param($stmt, "ss", $loginusername, $loginpassword);
    mysqli_stmt_execute($stmt);
    $rezultat = mysqli_stmt_get_result($stmt);

    if ($rezultat) {
        $this->Kolekcija = $rezultat;
        $this->BrojZapisa = mysqli_num_rows($rezultat);
    } else {
        $this->Kolekcija = null;
        $this->BrojZapisa = 0;
    }

    mysqli_stmt_close($stmt);
}

public function UcitajSveKorisnike()
{
    $SQL = "select * from korisnik";
    $this->UcitajSvePoUpitu($SQL);
}

public function DaLiPostojiKorisnik($loginusername,$loginpassword)
{
    $this->UcitajKorisnikaPoLoginu($loginusername, $loginpassword);
    return $this->BrojZapisa > 0 ? "DA" : "NE";
}

public function DajImePrijavljenogKorisnika($loginusername,$loginpassword)
{
    $this->UcitajKorisnikaPoLoginu($loginusername, $loginpassword);
    $this->PrebaciKolekcijuUListu($this->Kolekcija);
    if ($this->BrojZapisa>0) {
        foreach ($this->ListaZapisa as $VrednostCvoraListe) {
            $ime=$VrednostCvoraListe[2];
        }
    } else {
        $ime='NEPOZNAT KORISNIK';
    }
    return $ime;
}

public function DajPrezimePrijavljenogKorisnika($loginusername,$loginpassword)
{
    $this->UcitajKorisnikaPoLoginu($loginusername, $loginpassword);
    $this->PrebaciKolekcijuUListu($this->Kolekcija);
    if ($this->BrojZapisa>0) {
        foreach ($this->ListaZapisa as $VrednostCvoraListe) {
            $prez=$VrednostCvoraListe[1];
        }
    } else {
        $prez='NEPOZNAT KORISNIK';
    }
    return $prez;
}

public function DajImePrezimePrijavljenogKorisnika($loginusername,$loginpassword)
{
    $this->UcitajKorisnikaPoLoginu($loginusername, $loginpassword);
    $this->PrebaciKolekcijuUListu($this->Kolekcija);
    $korisnik='NEPOZNAT KORISNIK';
    if ($this->BrojZapisa>0) {
        foreach ($this->ListaZapisa as $VrednostCvoraListe) {
            $prez=$VrednostCvoraListe[1];
            $ime=$VrednostCvoraListe[2];
            $korisnik=$prez.' '.$ime;
        }
    }
    return $korisnik;
}

public function DajIDPrijavljenogKorisnika($loginusername,$loginpassword)
{
    $id=0;
    $this->UcitajKorisnikaPoLoginu($loginusername, $loginpassword);
    $this->PrebaciKolekcijuUListu($this->Kolekcija);
    if ($this->BrojZapisa>0) {
        foreach ($this->ListaZapisa as $VrednostCvoraListe) {
            $id=$VrednostCvoraListe[0];
        }
    }
    return $id;
}

public function SnimiNovo()
{
    $AktivanSQLUpit = "";
    $this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
}

public function Obrisi()
{
    $AktivanSQLUpit = "DELETE from ";
    $this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
}

public function ObrisiSve()
{
    $AktivanSQLUpit = "DELETE from ";
    $this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
}

public function IzmeniVrednostPolja()
{
    $AktivanSQLUpit = "UPDATE  SET " ;
    $this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
}
}
?>
