<?php
class Konekcija{

// ATRIBUTI
public $konekcijaMYSQL;   //$db_handle, konekcija na DBMS;
public $konekcijaDB;      //$db_selected, konekcija na konkretnu bazu;
public $KompletanNazivBazePodataka; // prefiks zbog hostinga i naziv baze
public $VerzijaMYSQLNaredbi; //mysql ili mysqli
//
private $PutanjaNazivFajlaXMLParametriKonekcije;
// parametri konekcije
private $host;
private $korisnik;
private $sifra;
private $prefiks_baze_podataka;
private $naziv_baze_podataka ;


// METODE
// *************************************************************
private function UcitajVerzijuMYSQLNaredbi()
{
	$VerzijaPHP = phpversion();

	if ($VerzijaPHP<'7.0.0') // $VerzijaPHP>='5.5.0'
	{
		$this->VerzijaMYSQLNaredbi="mysql";
	}
	else
	{
		$this->VerzijaMYSQLNaredbi="mysqli";
	}
}
// *************************************************************
private function UcitajParametreKonekcije($PutanjaNazivFajlaXMLParametriKonekcije)
{
// preuzimanje naziva baze podataka iz parametra ucitava se iz XML
//NE VAZI: $xml=simplexml_load_file("../parametri/konektovanje.xml")
//NE VAZI: $xml=simplexml_load_file("parametri/konektovanje.xml")

// novo, jer su parametri konekcije u istom folderu kao i klasa:

$xml=simplexml_load_file($PutanjaNazivFajlaXMLParametriKonekcije) or die("Greska: Ne postoji fajl BaznaParametriKonekcije.xml");
// ocitavanje elemenata XML fajla u promenljive
$this->host=$xml->host;
$this->korisnik=$xml->korisnik;
$this->sifra=$xml->sifra;

// PRILAGODJAVANJE HOSTINGU SISTEMA, PREFIKS BAZE PODATAKA SADRZI PODATKE SA LOGOVANJA NA HOSTING SERVER
$this->prefiks_baze_podataka = $xml->prefiks_baze_podataka;
$this->naziv_baze_podataka = $xml->naziv_baze_podataka;
$this->KompletanNazivBazePodataka =$this->prefiks_baze_podataka.$this->naziv_baze_podataka;
}

// *************************************************************
// ------- konstruktor
public function __construct($NovaPutanjaNazivFajlaXMLParametriKonekcije)
{
	$this->PutanjaNazivFajlaXMLParametriKonekcije=$NovaPutanjaNazivFajlaXMLParametriKonekcije; 
	//
	$this->UcitajVerzijuMYSQLNaredbi();
	$this->UcitajParametreKonekcije($NovaPutanjaNazivFajlaXMLParametriKonekcije);
}

// *************************************************************
public function connect()
{

if ($this->VerzijaMYSQLNaredbi=="mysqli")
	{
		$this->konekcijaDB = mysqli_connect($this->host, $this->korisnik, $this->sifra, $this->KompletanNazivBazePodataka);
	}
	else // mysql
	{
		// ostvarivanje konekcije ka DBMS-u MYSQL
		$this->konekcijaMYSQL = mysql_connect($this->host, $this->korisnik, $this->sifra);
	
		// ostvarivanje konekcije ka bazi podataka
		$this->konekcijaDB = mysql_select_db($this->KompletanNazivBazePodataka, $this->konekcijaMYSQL);
	}
	
if ($this->konekcijaDB)
	{
       // dodatak da moze da radi sa UTF8
	   if ($this->VerzijaMYSQLNaredbi=="mysqli")
		{
		   mysqli_set_charset($this->konekcijaDB,"utf8");
		}
		else //mysql
		{
			mysql_query('SET NAMES "utf8"',$this->konekcijaMYSQL);
		} // zatvaranje if

	} // ako je uspostavljena konekcija $this->konekcijaDB
	
} // zatvaranje procedure

// *************************************************************
public function disconnect()
{
    if ($this->konekcijaDB) {
        mysqli_close($this->konekcijaDB);
        $this->konekcijaDB = null;
    }
}
	
}  // zatvaranje klase
		
		

?>