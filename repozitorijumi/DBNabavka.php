<?php

require_once __DIR__ . "/../model/entiteti/NabavkaEntitet.php";
require_once __DIR__ . "/DBStavkaNabavke.php";

class DBNabavka extends Tabela
{
    public function PronadjiNabavku($DatumNabavke, $Dobavljac)
    {
        $SQL = "SELECT IDNabavke 
                FROM `nabavka`
                WHERE DatumNabavke = '".$DatumNabavke."'
                AND Dobavljac = '".$Dobavljac."'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa > 0) {
            return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
        }

        return null;
    }

    public function DodajNabavku($DatumNabavke, $Dobavljac, $Napomena)
    {
        $SQL = "INSERT INTO `nabavka`
                (DatumNabavke, Dobavljac, Napomena)
                VALUES
                ('".$DatumNabavke."', '".$Dobavljac."', '".$Napomena."')";

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
        $SQL = "SELECT IDNabavke, DatumNabavke, Dobavljac, Napomena
                FROM `nabavka`
                WHERE IDNabavke = '".$IDNabavke."'";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa == 0) {
            return null;
        }

        $red = array(
            "IDNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0),
            "DatumNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 1),
            "Dobavljac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 2),
            "Napomena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 3)
        );

        $nabavka = NabavkaEntitet::IzRedaBaze($red);

        $StavkaRepo = new DBStavkaNabavke($this->KonekcijaObject, "stavka_nabavke");
        $stavke = $StavkaRepo->DajStavkeKaoModele($IDNabavke);

        foreach ($stavke as $stavka) {
            $nabavka->DodajStavku($stavka);
        }

        return $nabavka;
    }
}

?>