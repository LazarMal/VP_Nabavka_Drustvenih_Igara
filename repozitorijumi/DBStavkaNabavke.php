<?php

require_once __DIR__ . "/../model/entiteti/StavkaNabavkeEntitet.php";

class DBStavkaNabavke extends Tabela
{
    public function DodajStavkuNabavke($IDNabavke, $SifraIgre, $Kolicina, $Cena)
    {
        $SQL = "INSERT INTO `stavka_nabavke`
                (IDNabavke, SifraIgre, Kolicina, Cena)
                VALUES
                ('".$IDNabavke."', '".$SifraIgre."', '".$Kolicina."', '".$Cena."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajStavkeKaoModele($IDNabavke)
    {
        $SQL = "SELECT 
                    s.IDStavke,
                    s.IDNabavke,
                    s.SifraIgre,
                    s.Kolicina,
                    s.Cena,
                    k.Naziv,
                    k.Proizvodjac,
                    k.OznakaKategorije,
                    k.NazivFajlaSlike
                FROM `stavka_nabavke` s
                INNER JOIN `drustvena_igra` k ON s.SifraIgre = k.SifraIgre
                WHERE s.IDNabavke = '".$IDNabavke."'";

        $this->UcitajSvePoUpitu($SQL);

        $stavke = array();

        for ($i = 0; $i < $this->BrojZapisa; $i++) {
            $red = array(
                "IDStavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0),
                "IDNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 1),
                "SifraIgre" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 2),
                "Kolicina" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 3),
                "Cena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 4),
                "Naziv" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 5),
                "Proizvodjac" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 6),
                "OznakaKategorije" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 7),
                "NazivFajlaSlike" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 8)
            );

            $stavke[] = StavkaNabavkeEntitet::IzRedaBaze($red);
        }

        return $stavke;
    }
}

?>
