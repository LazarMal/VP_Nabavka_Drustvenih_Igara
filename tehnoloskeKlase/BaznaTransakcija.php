<?php
class Transakcija{

// atributi
private $OtvorenaKonekcija;
private $VerzijaMySQLNaredbi;

// metode

// ------- konstruktor
public function __construct($NovaOtvorenaKonekcija)
{
	$this->OtvorenaKonekcija=$NovaOtvorenaKonekcija;
	$this->VerzijaMySQLNaredbi=$NovaOtvorenaKonekcija->VerzijaMYSQLNaredbi;
}

public function DajVerzijuMySQL()
{
	return $this->VerzijaMySQLNaredbi;
}

public function ZapocniTransakciju()
{
	if ($this->VerzijaMySQLNaredbi=="mysqli")
	{
		mysqli_query($this->OtvorenaKonekcija->konekcijaDB,"SET AUTOCOMMIT=0");
		mysqli_query($this->OtvorenaKonekcija->konekcijaDB,"START TRANSACTION");
	}
	else
	{
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
	}
	
} // zatvaranje procedure

public function ProveriGresku()
{
	if ($this->VerzijaMySQLNaredbi=="mysqli")
	{
		$greska= mysqli_error($this->OtvorenaKonekcija->konekcijaDB);	
		
	}
	else
	{
		$greska= mysql_error();	
		
	}
	
	return $greska;
	
} // zatvaranje procedure

public function PonistiTransakciju()
// samo ako poslednje izvrsavanje aktivnog SQL upita ima gresku
//onda radi rollback
{
	if ($this->VerzijaMySQLNaredbi=="mysqli")
	{
	    mysqli_query($this->OtvorenaKonekcija->konekcijaDB,"ROLLBACK");
	}
	else
	{
		mysql_query("ROLLBACK");
	}
	
} // zatvaranje procedure


public function ZavrsiTransakciju($UtvrdjenaGreska)
// samo ako poslednje izvrsavanje aktivnog SQL upita ima gresku
//onda radi rollback
{
	if ($this->VerzijaMySQLNaredbi=="mysqli")
	{
		if (empty($UtvrdjenaGreska)) 
			{
				mysqli_query($this->OtvorenaKonekcija->konekcijaDB,"COMMIT");
			} 
			else 
			{
			    mysqli_query($this->OtvorenaKonekcija->konekcijaDB,"ROLLBACK");
			}
	}
	else
	{
		if (empty($UtvrdjenaGreska)) 
			{
			mysql_query("COMMIT");
			} 
		else 
			{
		    mysql_query("ROLLBACK");
			}
	}
	
} // zatvaranje procedure
	
}  // zatvaranje klase
		
		

?>