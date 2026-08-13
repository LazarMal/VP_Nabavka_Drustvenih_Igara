<?php

class DrustvenaIgraEntitet
{
    public $SifraIgre;
    public $Naziv;
    public $Proizvodjac;
    public $OznakaKategorije;
    public $NazivFajlaSlike;

    public function __construct($SifraIgre = "", $Naziv = "", $Proizvodjac = "", $OznakaKategorije = "", $NazivFajlaSlike = "")
    {
        $this->SifraIgre = $SifraIgre;
        $this->Naziv = $Naziv;
        $this->Proizvodjac = $Proizvodjac;
        $this->OznakaKategorije = $OznakaKategorije;
        $this->NazivFajlaSlike = $NazivFajlaSlike;
    }

    public static function IzRedaBaze($red)
    {
        return new DrustvenaIgraEntitet(
            isset($red["SifraIgre"]) ? $red["SifraIgre"] : "",
            isset($red["Naziv"]) ? $red["Naziv"] : "",
            isset($red["Proizvodjac"]) ? $red["Proizvodjac"] : "",
            isset($red["OznakaKategorije"]) ? $red["OznakaKategorije"] : "",
            isset($red["NazivFajlaSlike"]) ? $red["NazivFajlaSlike"] : ""
        );
    }
}

?>
