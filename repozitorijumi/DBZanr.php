<?php
class DBZanr extends Tabela 
{
    public $Oznaka;
    public $Naziv; 
    public $UkupanBrojKnjiga;

    public function UcitajKolekcijuSvihZanrova()
    {
        $SQL = "select * from `".$this->NazivBazePodataka."`.`zanr` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function InkrementirajBrojKnjiga($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojKnjiga',
            $KriterijumFiltriranja,
            'UkupanBrojKnjiga'
        );

        $NovaVrednost = $StaraVrednost + 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`zanr`
                SET UkupanBrojKnjiga=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }

    public function DekrementirajBrojKnjiga($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojKnjiga',
            $KriterijumFiltriranja,
            'UkupanBrojKnjiga'
        );

        $NovaVrednost = $StaraVrednost - 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`zanr`
                SET UkupanBrojKnjiga=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }
}
?>