<?php
class Tabela{

// atributi
public $OtvorenaKonekcija;
public $NazivBazePodataka;
public $NazivTabele;
public $TipMYSQL;
//
public $Kolekcija;
public $BrojZapisa;
public $PrviRedZapisa;
public $ListaZapisa;  // = array();


// metode

// ------- konstruktor
public function __construct($NovaOtvorenaKonekcija, $NoviNazivTabele){
// podrazumevamo da je otvorena konekcija, a zatvara se spolja
	$this->OtvorenaKonekcija = $NovaOtvorenaKonekcija;
	$this->NazivBazePodataka = $NovaOtvorenaKonekcija->KompletanNazivBazePodataka;
	$this->NazivTabele = $NoviNazivTabele;
	$this->TipMYSQL = $NovaOtvorenaKonekcija->VerzijaMYSQLNaredbi;
}
// *************************************************************
public function UcitajSve($KriterijumSortiranja)
{
	// nakon iyvrsavanja upita, napunjena je kolekcija i broj zapisa
	
	$SQL = "select * from `".$this->NazivBazePodataka."`.`".$this->NazivTabele."` ORDER BY ".$KriterijumSortiranja;
	
	if ($this->TipMYSQL=="mysqli")
	{
		$this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
		$this->BrojZapisa = mysqli_num_rows($this->Kolekcija); 
	
	}
	else // mysql
	{
		$this->Kolekcija = mysql_query($SQL);
		$this->BrojZapisa = mysql_num_rows($this->Kolekcija);
	}
}
// *************************************************************
public function UcitajSvePoUpitu($Upit)
{
	// nakon iyvrsavanja upita, napunjena je kolekcija i broj zapisa
	
	if ($this->TipMYSQL=="mysqli")
	{
		$this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $Upit);
		$this->BrojZapisa = mysqli_num_rows($this->Kolekcija); 
	
	}
	else // mysql
	{
		$this->Kolekcija = mysql_query($Upit);
		$this->BrojZapisa = mysql_num_rows($this->Kolekcija);
	}
}
// *************************************************************
public function UcitajSvaPoljaFiltrirano($KriterijumFiltriranja, $KriterijumSortiranja)
{
	// nakon iyvrsavanja upita, napunjena je kolekcija i broj zapisa
	
	$SQL = "select * from `".$this->NazivBazePodataka."`.`".$this->NazivTabele."` WHERE ".$KriterijumFiltriranja." ORDER BY ".$KriterijumSortiranja;
	
	if ($this->TipMYSQL=="mysqli")
	{
		$this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
		$this->BrojZapisa = mysqli_num_rows($this->Kolekcija);
	}
	else // mysql
	{
		$this->Kolekcija = mysql_query($SQL);
		$this->BrojZapisa = mysql_num_rows($this->Kolekcija);
	}
}
// *************************************************************
public function UcitajPoljaFiltrirano($Polja, $KriterijumFiltriranja, $KriterijumSortiranja)
{
	// nakon iyvrsavanja upita, napunjena je kolekcija i broj zapisa
	
	$SQL = "select ".$Polja." from `".$this->NazivBazePodataka."`.`".$this->NazivTabele."` WHERE ".$KriterijumFiltriranja." ORDER BY ".$KriterijumSortiranja;
	if ($this->TipMYSQL=="mysqli")
	{
		$this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
		$this->BrojZapisa = mysqli_num_rows($this->Kolekcija);
	}
	else // mysql
	{
		$this->Kolekcija = mysql_query($SQL);
		$this->BrojZapisa = mysql_num_rows($this->Kolekcija);
	}
}
// *************************************************************
public function DajVrednostJednogPoljaPrvogZapisa ($NazivTrazenogPolja, $KriterijumFiltriranja, $KriterijumSortiranja) 
{
	$SQL = "select ".$NazivTrazenogPolja." from `".$this->NazivBazePodataka."`.`".$this->NazivTabele."` WHERE ".$KriterijumFiltriranja." ORDER BY ".$KriterijumSortiranja;
	
	if ($this->TipMYSQL=="mysqli")
	{
		$Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
		$row=mysqli_fetch_array($Kolekcija,MYSQLI_NUM);  // fetch row
		$Vrednost=$row [0];
	}
	else // mysql
	{
		$Kolekcija = mysql_query($SQL);
		$Vrednost=mysql_result($Kolekcija,0,$NazivTrazenogPolja);
	}

    return $Vrednost;
}
// *************************************************************
public function PrebaciKolekcijuUListu($Kolekcija) //kolekcija je result
{
	$ListaZapisa = array();
	if ($this->TipMYSQL=="mysqli")
	{
		while($RedZapisa = mysqli_fetch_array($Kolekcija,MYSQLI_NUM)) 
			{
				$this->ListaZapisa[] = $RedZapisa;
			}
	}
	else // mysql
	{
		while($RedZapisa = mysql_fetch_array($Kolekcija,MYSQLI_NUM)) 
			{
				$this->ListaZapisa[] = $RedZapisa;
			}
	}
		
	return $ListaZapisa; 
}
// *************************************************************
public function DajVrednostPoRednomBrojuZapisaPoRBPolja ($Kolekcija, $RBZapisa, $RBPolja)
{
	if ($this->TipMYSQL=="mysqli")
	{
		$ListaZapisa = array();
		$ListaZapisa= $this->PrebaciKolekcijuUListu($Kolekcija);
		$RedZapisa=$this->ListaZapisa[$RBZapisa];
		$Vrednost=$RedZapisa [$RBPolja];
	}
	else // mysql
	{
		$Vrednost=mysql_result($Kolekcija,$RBZapisa, $RBPolja);   //$NazivPolja);
	}

    return $Vrednost;
}
// *************************************************************
public function PostojiZapis($KriterijumFiltriranja)   // da li postoji, da li je jedinstven itd.
{
	// ne puni kolekciju atribut, vec samo okalnu promenljivu da bi vratio da li postoji zapis
	
	$SQL = "SELECT * FROM `".$this->NazivBazePodataka."`.`".$this->NazivTabele."` WHERE ".$KriterijumFiltriranja;   //PRIMER: KORISNICKOIME='".$korisnickoime."' AND SIFRA='".$sifra."'";
	if ($this->TipMYSQL=="mysqli")
	{
		$KolekcijaLokalna = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
		$BrojZapisaLokalna = mysqli_num_rows($KolekcijaLokalna); 
	
	}
	else // mysql
	{
		$KolekcijaLokalna = mysql_query($SQL);
		$BrojZapisaLokalna = mysql_num_rows($KolekcijaLokalna);
	}
    if ($BrojZapisaLokalna>0) 
	{
		$postoji=true;
	}
	else 
	{
		$postoji=false;
	}
	return $postoji;
}
// *************************************************************
public function IzvrsiAktivanSQLUpit($AktivanSQLUpit) {
	//PRIMER: $AktivanSQLUpit = "INSERT INTO `".$this->bazapodataka."`.`KORISNIK` (IME, PREZIME, KORISNICKOIME, SIFRA, EMAIL, URLSlike, statusucesca, DatumRodjenja) VALUES ('$Ime', '$Prezime', '$KorisnickoIme', '$Sifra', '$Email', '', '$Status', '$DatumRodjenja')";
    // ---------------
	if ($this->TipMYSQL=="mysqli")
	{
		$retval = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $AktivanSQLUpit);
		$Greska = mysqli_error($this->OtvorenaKonekcija->konekcijaDB); 
	}
	else // mysql
	{
		$retval = mysql_query( $AktivanSQLUpit, $this->OtvorenaKonekcija->konekcijaMYSQL);
		$Greska = mysql_error(); 
	}
	return $Greska;
}
// *************************************************************


}

?>