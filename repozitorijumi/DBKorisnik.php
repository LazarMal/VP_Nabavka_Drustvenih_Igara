<?php
class DBKorisnik extends Tabela{

// ATRIBUTI
public $IDKorisnika; // auto increment u bazi podataka
public $Prezime;
public $Ime;
public $Email;
public $KorisnickoIme;
public $Sifra;
public $Stari_IDKorisnika; // potrebno zbog izmene

// metode

// ------- konstruktor - uzima se iz klase roditelja - Tabela

// ------- preostale metode

public function UcitajSveKorisnike()
{
		$SQL = "select * from korisnik";
		$this->UcitajSvePoUpitu($SQL);
} // kraj metode

public function DaLiPostojiKorisnik($loginusername,$loginpassword)
{
	$postoji="";
	$SQLKorisnik = "SELECT * FROM `".$this->OtvorenaKonekcija->KompletanNazivBazePodataka."`.`korisnik` WHERE KORISNICKOIME='".$loginusername."' AND SIFRA='".$loginpassword."'";
    $this->UcitajSvePoUpitu($SQLKorisnik);
	// raspolazemo sa kolekcijom i brojem zapisa nakon ucitaj sve po upitu
	
	// NEPOTREBNO - $this->PrebaciKolekcijuUListu($this->Kolekcija);
	if ($this->BrojZapisa>0)
	{
		$postoji="DA";
	}  			
	else 
	{
		$postoji="NE";
	}
	return $postoji;
}

public function DajImePrijavljenogKorisnika($loginusername,$loginpassword)
{
	$korisnik="";
	$SQLKorisnik = "SELECT * FROM `".$this->OtvorenaKonekcija->KompletanNazivBazePodataka."`.`KORISNIK` WHERE KORISNICKOIME='".$loginusername."' AND SIFRA='".$loginpassword."'";
    $this->UcitajSvePoUpitu($SQLKorisnik);
	$this->PrebaciKolekcijuUListu($this->Kolekcija);
	if ($this->BrojZapisa>0)
	{
		// postoji zapis
		foreach ($this->ListaZapisa as $VrednostCvoraListe)
		{
			$ime=$VrednostCvoraListe[2];
			
		}
	}  			
	else 
	{
		$ime='NEPOZNAT KORISNIK';
	}
	return $ime;
}

public function DajPrezimePrijavljenogKorisnika($loginusername,$loginpassword)
{
	$korisnik="";
	$SQLKorisnik = "SELECT * FROM `".$this->OtvorenaKonekcija->KompletanNazivBazePodataka."`.`KORISNIK` WHERE KORISNICKOIME='".$loginusername."' AND SIFRA='".$loginpassword."'";
    $this->UcitajSvePoUpitu($SQLKorisnik);
	$this->PrebaciKolekcijuUListu($this->Kolekcija);
	if ($this->BrojZapisa>0)
	{
		// postoji zapis
		foreach ($this->ListaZapisa as $VrednostCvoraListe)
		{
			$prez=$VrednostCvoraListe[1];
			
		}
	}  			
	else 
	{
		$prez='NEPOZNAT KORISNIK';
	}
	return $prez;
}

public function DajImePrezimePrijavljenogKorisnika($loginusername,$loginpassword)
{
	$korisnik="";
	$SQLKorisnik = "SELECT * FROM `".$this->OtvorenaKonekcija->KompletanNazivBazePodataka."`.`KORISNIK` WHERE KORISNICKOIME='".$loginusername."' AND SIFRA='".$loginpassword."'";
    $this->UcitajSvePoUpitu($SQLKorisnik);
	$this->PrebaciKolekcijuUListu($this->Kolekcija);
	if ($this->BrojZapisa>0)
	{
		// postoji zapis
		foreach ($this->ListaZapisa as $VrednostCvoraListe)
		{
			$prez=$VrednostCvoraListe[1];
			$ime=$VrednostCvoraListe[2];
			$korisnik=$prez.' '.$ime;
		}
	}  			
	else 
	{
		$korisnik='NEPOZNAT KORISNIK';
	}
	return $korisnik;
}

public function DajIDPrijavljenogKorisnika($loginusername,$loginpassword)
{
	$id=0;
	$SQLKorisnik = "SELECT * FROM `".$this->OtvorenaKonekcija->KompletanNazivBazePodataka."`.`KORISNIK` WHERE KORISNICKOIME='".$loginusername."' AND SIFRA='".$loginpassword."'";
    $this->UcitajSvePoUpitu($SQLKorisnik);
	$this->PrebaciKolekcijuUListu($this->Kolekcija);
	if ($this->BrojZapisa>0)
	{
		// postoji zapis
		foreach ($this->ListaZapisa as $VrednostCvoraListe)
		{
			$id=$VrednostCvoraListe[0];
		}
	} 
	// else - ostaje 0

	return $id;
}


public function SnimiNovo()
{
	$AktivanSQLUpit = "";
	$this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
}

// brisanje 
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

	// transformisemo datum u formu pogodnu za insert into 
    //	$DatumskaVrednost=date_create($this->Datum_PoslednjePromene);
    //	$DatumUnosa=date_format($DatumskaVrednost,"Y-m-d");  

	// konacan upit
	$AktivanSQLUpit = "UPDATE  SET " ;
	$this->IzvrsiAktivanSQLUpit($AktivanSQLUpit);
} // kraj metode
} // kraj klase
?>