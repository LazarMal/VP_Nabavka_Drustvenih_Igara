<?php
class DBKnjigaSP extends Tabela 
{
private $bazapodataka;
private $UspehKonekcijeNaDBMS;
public $SifraIgre;
public $Naziv;
public $Proizvodjac;
public $OznakaKategorije;
public $NazivFajlaSlike;

public function DodajNovuKnjigu()
{
    $GreskarezultatPar1 = $this->IzvrsiAktivanSQLUpit ("SET @SifraIgreParametar='".$this->SifraIgre."'");
    $GreskarezultatPar2 = $this->IzvrsiAktivanSQLUpit ("SET @NazivParametar='".$this->Naziv."'");
    $GreskarezultatPar3 = $this->IzvrsiAktivanSQLUpit ("SET @ProizvodjacParametar='".$this->Proizvodjac."'");
    $GreskarezultatPar4 = $this->IzvrsiAktivanSQLUpit ("SET @OznakaKategorijeParametar='".$this->OznakaKategorije."'");
    $GreskarezultatPar5 = $this->IzvrsiAktivanSQLUpit ("SET @NazivFajlaSlikeParametar='".$this->NazivFajlaSlike."'");

    $GreskarezultatCall = $this->IzvrsiAktivanSQLUpit ("CALL `DodajDrustvenuIgru`(@SifraIgreParametar,@NazivParametar,@ProizvodjacParametar,@OznakaKategorijeParametar,@NazivFajlaSlikeParametar);");

    $greska=$GreskarezultatPar1.$GreskarezultatPar2.$GreskarezultatPar3.$GreskarezultatPar4.$GreskarezultatPar5.$GreskarezultatCall;
    return $greska;
}


}
?>
