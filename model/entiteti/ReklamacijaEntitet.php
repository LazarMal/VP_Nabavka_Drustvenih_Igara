<?php

require_once __DIR__ . "/StavkaReklamacijeEntitet.php";

class ReklamacijaEntitet
{
    public $IDReklamacije;
    public $BrojReklamacije;
    public $DatumReklamacije;
    public $Dobavljac;
    public $Napomena;
    public $ReklamacijuEvidentirao;
    public $DatumEvidentiranja;
    public $ListaStavki;

    public function __construct($BrojReklamacije = "", $DatumReklamacije = "", $Dobavljac = "", $Napomena = "", $ReklamacijuEvidentirao = "", $DatumEvidentiranja = "", $IDReklamacije = null)
    {
        $this->IDReklamacije = $IDReklamacije;
        $this->BrojReklamacije = $BrojReklamacije;
        $this->DatumReklamacije = $DatumReklamacije;
        $this->Dobavljac = $Dobavljac;
        $this->Napomena = $Napomena;
        $this->ReklamacijuEvidentirao = $ReklamacijuEvidentirao;
        $this->DatumEvidentiranja = $DatumEvidentiranja;
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
        return new ReklamacijaEntitet(
            isset($red["BrojReklamacije"]) ? $red["BrojReklamacije"] : "",
            isset($red["DatumReklamacije"]) ? $red["DatumReklamacije"] : "",
            isset($red["Dobavljac"]) ? $red["Dobavljac"] : "",
            isset($red["Napomena"]) ? $red["Napomena"] : "",
            isset($red["ReklamacijuEvidentirao"]) ? $red["ReklamacijuEvidentirao"] : "",
            isset($red["DatumEvidentiranja"]) ? $red["DatumEvidentiranja"] : "",
            isset($red["IDReklamacije"]) ? $red["IDReklamacije"] : null
        );
    }
}

?>
