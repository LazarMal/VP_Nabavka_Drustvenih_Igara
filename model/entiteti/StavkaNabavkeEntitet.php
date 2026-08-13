<?php

require_once __DIR__ . "/KnjigaEntitet.php";

class StavkaNabavkeEntitet
{
    public $IDStavke;
    public $IDNabavke;
    public $DrustvenaIgra;
    public $Kolicina;
    public $Cena;

    public function __construct($DrustvenaIgra = null, $Kolicina = 0, $Cena = 0, $IDStavke = null, $IDNabavke = null)
    {
        $this->IDStavke = $IDStavke;
        $this->IDNabavke = $IDNabavke;
        $this->DrustvenaIgra = $DrustvenaIgra;
        $this->Kolicina = $Kolicina;
        $this->Cena = $Cena;
    }

    public function DajUkupno()
    {
        return $this->Kolicina * $this->Cena;
    }

    public static function IzRedaBaze($red)
    {
        $igra = new KnjigaEntitet(
            isset($red["SifraIgre"]) ? $red["SifraIgre"] : "",
            isset($red["Naziv"]) ? $red["Naziv"] : "",
            isset($red["Proizvodjac"]) ? $red["Proizvodjac"] : "",
            isset($red["OznakaKategorije"]) ? $red["OznakaKategorije"] : "",
            isset($red["NazivFajlaSlike"]) ? $red["NazivFajlaSlike"] : ""
        );

        return new StavkaNabavkeEntitet(
            $igra,
            isset($red["Kolicina"]) ? $red["Kolicina"] : 0,
            isset($red["Cena"]) ? $red["Cena"] : 0,
            isset($red["IDStavke"]) ? $red["IDStavke"] : null,
            isset($red["IDNabavke"]) ? $red["IDNabavke"] : null
        );
    }
}

?>
