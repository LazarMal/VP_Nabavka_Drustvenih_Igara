<?php

require_once __DIR__ . "/DrustvenaIgraEntitet.php";

class StavkaReklamacijeEntitet
{
    public $IDStavkeReklamacije;
    public $IDReklamacije;
    public $DrustvenaIgra;
    public $Kolicina;
    public $Cena;
    public $RazlogReklamacije;

    public function __construct($DrustvenaIgra = null, $Kolicina = 0, $Cena = 0, $RazlogReklamacije = "", $IDStavkeReklamacije = null, $IDReklamacije = null)
    {
        $this->IDStavkeReklamacije = $IDStavkeReklamacije;
        $this->IDReklamacije = $IDReklamacije;
        $this->DrustvenaIgra = $DrustvenaIgra;
        $this->Kolicina = $Kolicina;
        $this->Cena = $Cena;
        $this->RazlogReklamacije = $RazlogReklamacije;
    }

    public function DajUkupno()
    {
        return $this->Kolicina * $this->Cena;
    }

    public static function IzRedaBaze($red)
    {
        $igra = new DrustvenaIgraEntitet(
            isset($red["SifraIgre"]) ? $red["SifraIgre"] : "",
            isset($red["Naziv"]) ? $red["Naziv"] : "",
            isset($red["Proizvodjac"]) ? $red["Proizvodjac"] : "",
            isset($red["OznakaKategorije"]) ? $red["OznakaKategorije"] : "",
            isset($red["NazivFajlaSlike"]) ? $red["NazivFajlaSlike"] : ""
        );

        return new StavkaReklamacijeEntitet(
            $igra,
            isset($red["Kolicina"]) ? $red["Kolicina"] : 0,
            isset($red["Cena"]) ? $red["Cena"] : 0,
            isset($red["RazlogReklamacije"]) ? $red["RazlogReklamacije"] : "",
            isset($red["IDStavkeReklamacije"]) ? $red["IDStavkeReklamacije"] : null,
            isset($red["IDReklamacije"]) ? $red["IDReklamacije"] : null
        );
    }
}

?>
