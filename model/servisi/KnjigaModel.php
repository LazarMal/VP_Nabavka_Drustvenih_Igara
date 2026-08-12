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
        $upit = "SELECT ISBN, Naziv, Cena 
                 FROM `".$this->baza."`.`knjiga` 
                 ORDER BY Naziv ASC";

        return mysqli_query($this->konekcija, $upit);
    }
}

?>