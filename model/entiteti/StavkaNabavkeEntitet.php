<?php

require_once __DIR__ . "/KnjigaEntitet.php";

class StavkaNabavkeEntitet
{
    public $IDStavke;
    public $IDNabavke;
    public $Knjiga;
    public $Kolicina;
    public $Cena;

    public function __construct($Knjiga = null, $Kolicina = 0, $Cena = 0, $IDStavke = null, $IDNabavke = null)
    {
        $this->IDStavke = $IDStavke;
        $this->IDNabavke = $IDNabavke;
        $this->Knjiga = $Knjiga;
        $this->Kolicina = $Kolicina;
        $this->Cena = $Cena;
    }

    public function DajUkupno()
    {
        return $this->Kolicina * $this->Cena;
    }

    public static function IzRedaBaze($red)
    {
        $knjiga = new KnjigaEntitet(
            isset($red["ISBN"]) ? $red["ISBN"] : "",
            isset($red["Naziv"]) ? $red["Naziv"] : "",
            isset($red["Autor"]) ? $red["Autor"] : "",
            isset($red["OznakaZanra"]) ? $red["OznakaZanra"] : "",
            isset($red["NazivFajlaSlike"]) ? $red["NazivFajlaSlike"] : ""
        );

        return new StavkaNabavkeEntitet(
            $knjiga,
            isset($red["Kolicina"]) ? $red["Kolicina"] : 0,
            isset($red["Cena"]) ? $red["Cena"] : 0,
            isset($red["IDStavke"]) ? $red["IDStavke"] : null,
            isset($red["IDNabavke"]) ? $red["IDNabavke"] : null
        );
    }
}

?>