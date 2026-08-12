<?php

require_once __DIR__ . "/../model/entiteti/StavkaNabavkeEntitet.php";

class DBStavkaNabavke extends Tabela
{
    public function DodajStavkuNabavke($IDNabavke, $ISBN, $Kolicina, $Cena)
    {
        $SQL = "INSERT INTO `stavka_nabavke`
                (IDNabavke, ISBN, Kolicina, Cena)
                VALUES
                ('".$IDNabavke."', '".$ISBN."', '".$Kolicina."', '".$Cena."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajStavkeKaoModele($IDNabavke)
    {
        $SQL = "SELECT 
                    s.IDStavke,
                    s.IDNabavke,
                    s.ISBN,
                    s.Kolicina,
                    s.Cena,
                    k.Naziv,
                    k.Autor,
                    k.OznakaZanra,
                    k.NazivFajlaSlike
                FROM `stavka_nabavke` s
                INNER JOIN `knjiga` k ON s.ISBN = k.ISBN
                WHERE s.IDNabavke = '".$IDNabavke."'";

        $this->UcitajSvePoUpitu($SQL);

        $stavke = array();

        for ($i = 0; $i < $this->BrojZapisa; $i++) {
            $red = array(
                "IDStavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 0),
                "IDNabavke" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 1),
                "ISBN" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 2),
                "Kolicina" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 3),
                "Cena" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 4),
                "Naziv" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 5),
                "Autor" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 6),
                "OznakaZanra" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 7),
                "NazivFajlaSlike" => $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, $i, 8)
            );

            $stavke[] = StavkaNabavkeEntitet::IzRedaBaze($red);
        }

        return $stavke;
    }
}

?>