<?php

class KnjigaModel
{
    private $konekcija;
    private $baza;

    public function __construct($konekcija, $baza)
    {
        $this->konekcija = $konekcija;
        $this->baza = $baza;
    }

    public function DajSveKnjigeZaNabavku()
    {
        $upit = "SELECT SifraIgre, Naziv, Cena 
                 FROM `".$this->baza."`.`drustvena_igra` 
                 ORDER BY Naziv ASC";

        return mysqli_query($this->konekcija, $upit);
    }
}

?>
