<?php

require_once __DIR__ . '/../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . "/../model/entiteti/StavkaReklamacijeEntitet.php";

class DBStavkaReklamacije extends Tabela
{
    public function DajStavkeZaPrikaz($IDReklamacije)
    {
        $SQL = "SELECT
                    stavka_reklamacije.IDStavkeReklamacije AS IDStavkeReklamacije,
                    stavka_reklamacije.SifraIgre AS SifraIgre,
                    drustvena_igra.Naziv AS Naziv,
                    stavka_reklamacije.Kolicina AS Kolicina,
                    stavka_reklamacije.Cena AS Cena,
                    stavka_reklamacije.RazlogReklamacije AS RazlogReklamacije,
                    (stavka_reklamacije.Kolicina * stavka_reklamacije.Cena) AS Ukupno
                FROM `stavka_reklamacije`
                INNER JOIN `drustvena_igra`
                ON stavka_reklamacije.SifraIgre = drustvena_igra.SifraIgre
                WHERE stavka_reklamacije.IDReklamacije = '" . $IDReklamacije . "'
                ORDER BY stavka_reklamacije.IDStavkeReklamacije ASC";

        $this->UcitajSvePoUpitu($SQL);

        return $this->Kolekcija;
    }

    public function StavkaPripadaReklamaciji($IDStavkeReklamacije, $IDReklamacije)
    {
        $SQL = "SELECT IDStavkeReklamacije
                FROM `stavka_reklamacije`
                WHERE IDStavkeReklamacije = '" . $IDStavkeReklamacije . "'
                AND IDReklamacije = '" . $IDReklamacije . "'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        return $this->BrojZapisa > 0;
    }

    public function DodajStavkuReklamacije($IDReklamacije, $SifraIgre, $Kolicina, $Cena, $RazlogReklamacije)
    {
        $SQL = "INSERT INTO `stavka_reklamacije`
                (IDReklamacije, SifraIgre, Kolicina, Cena, RazlogReklamacije)
                VALUES
                ('".$IDReklamacije."', '".$SifraIgre."', '".$Kolicina."', '".$Cena."', '".$RazlogReklamacije."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function IzmeniStavkuReklamacije($IDStavkeReklamacije, $IDReklamacije, $SifraIgre, $Kolicina, $Cena, $RazlogReklamacije)
    {
        $SQL = "UPDATE `stavka_reklamacije`
                SET SifraIgre = '".$SifraIgre."',
                    Kolicina = '".$Kolicina."',
                    Cena = '".$Cena."',
                    RazlogReklamacije = '".$RazlogReklamacije."'
                WHERE IDStavkeReklamacije = '".$IDStavkeReklamacije."'
                AND IDReklamacije = '".$IDReklamacije."'";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function ObrisiStavkuReklamacije($IDStavkeReklamacije, $IDReklamacije)
    {
        $SQL = "DELETE FROM `stavka_reklamacije`
                WHERE IDStavkeReklamacije = '".$IDStavkeReklamacije."'
                AND IDReklamacije = '".$IDReklamacije."'";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajIDStavkiZaReklamaciju($IDReklamacije)
    {
        $SQL = "SELECT IDStavkeReklamacije
                FROM `stavka_reklamacije`
                WHERE IDReklamacije = '".$IDReklamacije."'";

        $this->UcitajSvePoUpitu($SQL);

        $ids = array();

        for ($i = 0; $i < $this->BrojZapisa; $i++) {
            $ids[] = $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0);
        }

        return $ids;
    }

    public function DajStavkeKaoModele($IDReklamacije)
    {
        $SQL = "SELECT
                    s.IDStavkeReklamacije,
                    s.IDReklamacije,
                    s.SifraIgre,
                    s.Kolicina,
                    s.Cena,
                    s.RazlogReklamacije,
                    k.Naziv,
                    k.Proizvodjac,
                    k.OznakaKategorije,
                    k.NazivFajlaSlike
                FROM `stavka_reklamacije` s
                INNER JOIN `drustvena_igra` k ON s.SifraIgre = k.SifraIgre
                WHERE s.IDReklamacije = '".$IDReklamacije."'";

        $this->UcitajSvePoUpitu($SQL);

        $stavke = array();

        for ($i = 0; $i < $this->BrojZapisa; $i++) {
            $red = array(
                "IDStavkeReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0),
                "IDReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 1),
                "SifraIgre" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 2),
                "Kolicina" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 3),
                "Cena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 4),
                "RazlogReklamacije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 5),
                "Naziv" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 6),
                "Proizvodjac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 7),
                "OznakaKategorije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 8),
                "NazivFajlaSlike" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 9)
            );

            $stavke[] = StavkaReklamacijeEntitet::IzRedaBaze($red);
        }

        return $stavke;
    }
}

?>
