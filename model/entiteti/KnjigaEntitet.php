<?php

class KnjigaEntitet
{
    public $ISBN;
    public $Naziv;
    public $Autor;
    public $OznakaZanra;
    public $NazivFajlaSlike;

    public function __construct($ISBN = "", $Naziv = "", $Autor = "", $OznakaZanra = "", $NazivFajlaSlike = "")
    {
        $this->ISBN = $ISBN;
        $this->Naziv = $Naziv;
        $this->Autor = $Autor;
        $this->OznakaZanra = $OznakaZanra;
        $this->NazivFajlaSlike = $NazivFajlaSlike;
    }

    public static function IzRedaBaze($red)
    {
        return new KnjigaEntitet(
            isset($red["ISBN"]) ? $red["ISBN"] : "",
            isset($red["Naziv"]) ? $red["Naziv"] : "",
            isset($red["Autor"]) ? $red["Autor"] : "",
            isset($red["OznakaZanra"]) ? $red["OznakaZanra"] : "",
            isset($red["NazivFajlaSlike"]) ? $red["NazivFajlaSlike"] : ""
        );
    }
}

?>