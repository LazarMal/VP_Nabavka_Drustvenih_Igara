<?php

require_once __DIR__ . "/../model/entiteti/KnjigaEntitet.php";

class DBKnjiga extends Tabela 
{
    public $ISBN;
    public $Naziv;
    public $Autor;
    public $OznakaZanra;
    public $NazivFajlaSlike;

    public function DajKolekcijuSvihKnjiga()
    {
        $SQL = "select * from `knjiga` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
        return $this->Kolekcija;
    }

    public function UcitajKnjiguPoISBN($ISBNParametar)
    {
        $SQL = "select * from `knjiga` where `ISBN`='".$ISBNParametar."'";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function DajOznakuZanraKnjige($ISBNParametar)
    {
        $SQL = "select `OznakaZanra` from `knjiga` where `ISBN`='".$ISBNParametar."'";
        $this->UcitajSvePoUpitu($SQL);
        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }

    public function DodajNovuKnjigu()
    {
        $SQL = "INSERT INTO `knjiga`
        (ISBN, Naziv, Autor, OznakaZanra, NazivFajlaSlike)
        VALUES
        ('$this->ISBN', '$this->Naziv', '$this->Autor', '$this->OznakaZanra', '$this->NazivFajlaSlike')";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function ObrisiKnjigu($IdZaBrisanje)
    {
        $SQL = "DELETE FROM `knjiga` WHERE ISBN='".$IdZaBrisanje."'";
        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function IzmeniKnjigu($StariISBN, $ISBN, $Naziv, $Autor, $OznakaZanra, $NazivFajlaSlike)
    {
        $SQL = "UPDATE `knjiga`
        SET ISBN='".$ISBN."',
            Naziv='".$Naziv."',
            Autor='".$Autor."',
            OznakaZanra='".$OznakaZanra."',
            NazivFajlaSlike='".$NazivFajlaSlike."'
        WHERE ISBN='".$StariISBN."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }
    public function DajSveKnjigeKaoModele()
{
    $SQL = "SELECT * FROM `".$this->NazivBazePodataka."`.`knjiga` ORDER BY Naziv ASC";

    $this->UcitajSvePoUpitu($SQL);

    $knjige = array();

    for ($i = 0; $i < $this->BrojZapisa; $i++) {
        $red = array(
            "ISBN" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0),
            "Naziv" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 1),
            "Autor" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 2),
            "OznakaZanra" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 3),
            "NazivFajlaSlike" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 4)
        );

        $knjige[] = KnjigaEntitet::IzRedaBaze($red);
    }

    return $knjige;
}
}
?>