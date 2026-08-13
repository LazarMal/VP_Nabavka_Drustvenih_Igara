<?php

require_once __DIR__ . "/StavkaNabavkeEntitet.php";

class NabavkaEntitet
{
    public $IDNabavke;
    public $BrojNaloga;
    public $DatumNabavke;
    public $Dobavljac;
    public $Napomena;
    public $NalogEvidentirao;
    public $ListaStavki;

    public function __construct($BrojNaloga = "", $DatumNabavke = "", $Dobavljac = "", $Napomena = "", $NalogEvidentirao = "", $IDNabavke = null)
    {
        $this->IDNabavke = $IDNabavke;
        $this->BrojNaloga = $BrojNaloga;
        $this->DatumNabavke = $DatumNabavke;
        $this->Dobavljac = $Dobavljac;
        $this->Napomena = $Napomena;
        $this->NalogEvidentirao = $NalogEvidentirao;
        $this->ListaStavki = array();
    }

    public function DodajStavku($Stavka)
    {
        $this->ListaStavki[] = $Stavka;
    }

    public function DajUkupnuVrednost()
    {
        $ukupno = 0;

        foreach ($this->ListaStavki as $stavka) {
            $ukupno += $stavka->DajUkupno();
        }

        return $ukupno;
    }

    public static function IzRedaBaze($red)
    {
        return new NabavkaEntitet(
            isset($red["BrojNaloga"]) ? $red["BrojNaloga"] : "",
            isset($red["DatumNabavke"]) ? $red["DatumNabavke"] : "",
            isset($red["Dobavljac"]) ? $red["Dobavljac"] : "",
            isset($red["Napomena"]) ? $red["Napomena"] : "",
            isset($red["NalogEvidentirao"]) ? $red["NalogEvidentirao"] : "",
            isset($red["IDNabavke"]) ? $red["IDNabavke"] : null
        );
    }
}

?>
