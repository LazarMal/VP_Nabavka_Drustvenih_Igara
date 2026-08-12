<?php
class DBKnjigaSP extends Tabela 
{
// rad sa stored procedurom za snimanje novog zapisa (knjige)
// ATRIBUTI
private $bazapodataka;
private $UspehKonekcijeNaDBMS;
public $ISBN;
public $Naziv;
public $Autor;
public $OznakaZanra;
public $NazivFajlaSlike;

public function DodajNovuKnjigu()
{
    $GreskarezultatPar1 = $this->IzvrsiAktivanSQLUpit ("SET @ISBNParametar='".$this->ISBN."'");
    $GreskarezultatPar2 = $this->IzvrsiAktivanSQLUpit ("SET @NazivParametar='".$this->Naziv."'");
    $GreskarezultatPar3 = $this->IzvrsiAktivanSQLUpit ("SET @AutorParametar='".$this->Autor."'");
    $GreskarezultatPar4 = $this->IzvrsiAktivanSQLUpit ("SET @OznakaZanraParametar='".$this->OznakaZanra."'");
    $GreskarezultatPar5 = $this->IzvrsiAktivanSQLUpit ("SET @NazivFajlaSlikeParametar='".$this->NazivFajlaSlike."'");

    $GreskarezultatCall = $this->IzvrsiAktivanSQLUpit ("CALL `DodajKnjigu`(@ISBNParametar,@NazivParametar,@AutorParametar,@OznakaZanraParametar,@NazivFajlaSlikeParametar);");

    $greska=$GreskarezultatPar1.$GreskarezultatPar2.$GreskarezultatPar3.$GreskarezultatPar4.$GreskarezultatPar5.$GreskarezultatCall;
    return $greska;
}


}
?>
