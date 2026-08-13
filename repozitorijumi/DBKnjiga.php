<?php

require_once __DIR__ . "/../model/entiteti/KnjigaEntitet.php";

class DBKnjiga extends Tabela 
{
    public $SifraIgre;
    public $Naziv;
    public $Proizvodjac;
    public $OznakaKategorije;
    public $NazivFajlaSlike;

    public function DajKolekcijuSvihKnjiga()
    {
        $SQL = "select * from `drustvena_igra` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
        return $this->Kolekcija;
    }

    public function UcitajKnjiguPoISBN($SifraIgreParametar)
    {
        $SQL = "select * from `drustvena_igra` where `SifraIgre`='".$SifraIgreParametar."'";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function DajOznakuZanraKnjige($SifraIgreParametar)
    {
        $SQL = "select `OznakaKategorije` from `drustvena_igra` where `SifraIgre`='".$SifraIgreParametar."'";
        $this->UcitajSvePoUpitu($SQL);
        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }

    public function DodajNovuKnjigu()
    {
        $SQL = "INSERT INTO `drustvena_igra`
        (SifraIgre, Naziv, Proizvodjac, OznakaKategorije, NazivFajlaSlike)
        VALUES
        ('$this->SifraIgre', '$this->Naziv', '$this->Proizvodjac', '$this->OznakaKategorije', '$this->NazivFajlaSlike')";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function ObrisiKnjigu($IdZaBrisanje)
    {
        $SQL = "DELETE FROM `drustvena_igra` WHERE SifraIgre='".$IdZaBrisanje."'";
        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function IzmeniKnjigu($StaraSifraIgre, $SifraIgre, $Naziv, $Proizvodjac, $OznakaKategorije, $NazivFajlaSlike)
    {
        $SQL = "UPDATE `drustvena_igra`
        SET SifraIgre='".$SifraIgre."',
            Naziv='".$Naziv."',
            Proizvodjac='".$Proizvodjac."',
            OznakaKategorije='".$OznakaKategorije."',
            NazivFajlaSlike='".$NazivFajlaSlike."'
        WHERE SifraIgre='".$StaraSifraIgre."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function DajSveKnjigeKaoModele()
    {
        $SQL = "SELECT * FROM `".$this->NazivBazePodataka."`.`drustvena_igra` ORDER BY Naziv ASC";

        $this->UcitajSvePoUpitu($SQL);

        $knjige = array();

        for ($i = 0; $i < $this->BrojZapisa; $i++) {
            $red = array(
                "SifraIgre" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0),
                "Naziv" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 1),
                "Proizvodjac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 2),
                "OznakaKategorije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 3),
                "NazivFajlaSlike" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 4)
            );

            $knjige[] = KnjigaEntitet::IzRedaBaze($red);
        }

        return $knjige;
    }
}
?>
