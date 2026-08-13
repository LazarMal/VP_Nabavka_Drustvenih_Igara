<?php
class DBZanr extends Tabela 
{
    public $Oznaka;
    public $Naziv; 
    public $UkupanBrojIgara;

    public function UcitajKolekcijuSvihZanrova()
    {
        $SQL = "select * from `".$this->NazivBazePodataka."`.`kategorija_igre` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function InkrementirajBrojKnjiga($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojIgara',
            $KriterijumFiltriranja,
            'UkupanBrojIgara'
        );

        $NovaVrednost = $StaraVrednost + 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`kategorija_igre`
                SET UkupanBrojIgara=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }

    public function DekrementirajBrojKnjiga($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojIgara',
            $KriterijumFiltriranja,
            'UkupanBrojIgara'
        );

        $NovaVrednost = $StaraVrednost - 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`kategorija_igre`
                SET UkupanBrojIgara=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }
}
?>
