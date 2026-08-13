<?php

require_once __DIR__ . "/../model/entiteti/NabavkaEntitet.php";
require_once __DIR__ . "/DBStavkaNabavke.php";

class DBNabavka extends Tabela
{
    public function PostojiBrojNaloga($BrojNaloga)
    {
        $SQL = "SELECT IDNabavke 
                FROM `nabavka`
                WHERE BrojNaloga = '".$BrojNaloga."'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        return $this->BrojZapisa > 0;
    }

    public function DodajNabavku($BrojNaloga, $DatumNabavke, $Dobavljac, $Napomena, $NalogEvidentirao)
    {
        $SQL = "INSERT INTO `nabavka`
                (BrojNaloga, DatumNabavke, Dobavljac, Napomena, NalogEvidentirao)
                VALUES
                ('".$BrojNaloga."', '".$DatumNabavke."', '".$Dobavljac."', '".$Napomena."', '".$NalogEvidentirao."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajPoslednjiID()
    {
        $SQL = "SELECT LAST_INSERT_ID()";

        $this->UcitajSvePoUpitu($SQL);

        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }

    public function DajNabavkuKaoModel($IDNabavke)
    {
        $SQL = "SELECT IDNabavke, BrojNaloga, DatumNabavke, Dobavljac, Napomena, NalogEvidentirao
                FROM `nabavka`
                WHERE IDNabavke = '".$IDNabavke."'";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa == 0) {
            return null;
        }

        $red = array(
            "IDNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0),
            "BrojNaloga" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 1),
            "DatumNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 2),
            "Dobavljac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 3),
            "Napomena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 4),
            "NalogEvidentirao" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 5)
        );

        $nabavka = NabavkaEntitet::IzRedaBaze($red);

        $StavkaRepo = new DBStavkaNabavke($this->OtvorenaKonekcija, "stavka_nabavke");
        $stavke = $StavkaRepo->DajStavkeKaoModele($IDNabavke);

        foreach ($stavke as $stavka) {
            $nabavka->DodajStavku($stavka);
        }

        return $nabavka;
    }

    public function ObrisiNabavku($IDNabavke)
    {
        $SQL = "DELETE FROM `nabavka` WHERE IDNabavke = '".$IDNabavke."'";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }
}

?>
