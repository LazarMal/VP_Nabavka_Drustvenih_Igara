<?php

require_once __DIR__ . '/../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . "/../model/entiteti/ReklamacijaEntitet.php";
require_once __DIR__ . "/DBStavkaReklamacije.php";

class DBReklamacija extends Tabela
{
    public function DajSveReklamacije()
    {
        $SQL = "SELECT * FROM `reklamacija`
                ORDER BY DatumReklamacije DESC, IDReklamacije DESC";

        $this->UcitajSvePoUpitu($SQL);

        return $this->Kolekcija;
    }

    public function DajReklamacijePoFilteru($uslovi)
    {
        $SQL = "SELECT * FROM `reklamacija`";

        if (count($uslovi) > 0) {
            $SQL .= " WHERE " . implode(" AND ", $uslovi);
        }

        $SQL .= " ORDER BY DatumReklamacije DESC, IDReklamacije DESC";

        $this->UcitajSvePoUpitu($SQL);

        return $this->Kolekcija;
    }

    public function DajReklamacijuPoID($IDReklamacije)
    {
        $SQL = "SELECT * FROM `reklamacija`
                WHERE IDReklamacije = '" . $IDReklamacije . "'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa == 0) {
            return null;
        }

        return mysqli_fetch_assoc($this->Kolekcija);
    }

    public function DajReklamacijuPoBrojuReklamacije($BrojReklamacije)
    {
        $SQL = "SELECT * FROM `reklamacija`
                WHERE BrojReklamacije = '" . $BrojReklamacije . "'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa == 0) {
            return null;
        }

        return mysqli_fetch_assoc($this->Kolekcija);
    }

    public function PostojiBrojReklamacije($BrojReklamacije)
    {
        $SQL = "SELECT IDReklamacije
                FROM `reklamacija`
                WHERE BrojReklamacije = '".$BrojReklamacije."'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        return $this->BrojZapisa > 0;
    }

    public function PostojiBrojReklamacijeOsim($BrojReklamacije, $IDReklamacije)
    {
        $SQL = "SELECT IDReklamacije
                FROM `reklamacija`
                WHERE BrojReklamacije = '".$BrojReklamacije."'
                AND IDReklamacije <> '".$IDReklamacije."'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        return $this->BrojZapisa > 0;
    }

    public function IzmeniReklamaciju($IDReklamacije, $BrojReklamacije, $DatumReklamacije, $Dobavljac, $Napomena)
    {
        $SQL = "UPDATE `reklamacija`
                SET BrojReklamacije = '".$BrojReklamacije."',
                    DatumReklamacije = '".$DatumReklamacije."',
                    Dobavljac = '".$Dobavljac."',
                    Napomena = '".$Napomena."'
                WHERE IDReklamacije = '".$IDReklamacije."'";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DodajReklamaciju($BrojReklamacije, $DatumReklamacije, $Dobavljac, $Napomena, $ReklamacijuEvidentirao, $DatumEvidentiranja)
    {
        $SQL = "INSERT INTO `reklamacija`
                (BrojReklamacije, DatumReklamacije, Dobavljac, Napomena, ReklamacijuEvidentirao, DatumEvidentiranja)
                VALUES
                ('".$BrojReklamacije."', '".$DatumReklamacije."', '".$Dobavljac."', '".$Napomena."', '".$ReklamacijuEvidentirao."', '".$DatumEvidentiranja."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajPoslednjiID()
    {
        $SQL = "SELECT LAST_INSERT_ID()";

        $this->UcitajSvePoUpitu($SQL);

        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }

    public function DajReklamacijuKaoModel($IDReklamacije)
    {
        $SQL = "SELECT IDReklamacije, BrojReklamacije, DatumReklamacije, Dobavljac, Napomena, ReklamacijuEvidentirao, DatumEvidentiranja
                FROM `reklamacija`
                WHERE IDReklamacije = '".$IDReklamacije."'";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa == 0) {
            return null;
        }

        $red = array(
            "IDReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0),
            "BrojReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 1),
            "DatumReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 2),
            "Dobavljac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 3),
            "Napomena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 4),
            "ReklamacijuEvidentirao" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 5),
            "DatumEvidentiranja" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 6)
        );

        $reklamacija = ReklamacijaEntitet::IzRedaBaze($red);

        $StavkaRepo = new DBStavkaReklamacije($this->OtvorenaKonekcija, "stavka_reklamacije");
        $stavke = $StavkaRepo->DajStavkeKaoModele($IDReklamacije);

        foreach ($stavke as $stavka) {
            $reklamacija->DodajStavku($stavka);
        }

        return $reklamacija;
    }

    public function ObrisiReklamaciju($IDReklamacije)
    {
        $SQL = "DELETE FROM `reklamacija` WHERE IDReklamacije = '".$IDReklamacije."'";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }
}

?>
