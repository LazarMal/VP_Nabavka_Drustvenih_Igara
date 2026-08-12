<?php

require_once __DIR__ . "/StavkaNabavkeEntitet.php";

class NabavkaEntitet
{
    public $IDNabavke;
    public $DatumNabavke;
    public $Dobavljac;
    public $Napomena;
    public $ListaStavki;

    public function __construct($DatumNabavke = "", $Dobavljac = "", $Napomena = "", $IDNabavke = null)
    {
        $this->IDNabavke = $IDNabavke;
        $this->DatumNabavke = $DatumNabavke;
        $this->Dobavljac = $Dobavljac;
        $this->Napomena = $Napomena;
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
            isset($red["DatumNabavke"]) ? $red["DatumNabavke"] : "",
            isset($red["Dobavljac"]) ? $red["Dobavljac"] : "",
            isset($red["Napomena"]) ? $red["Napomena"] : "",
            isset($red["IDNabavke"]) ? $red["IDNabavke"] : null
        );
    }
}

?>