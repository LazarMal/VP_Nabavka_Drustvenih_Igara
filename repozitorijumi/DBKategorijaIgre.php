<?php
require_once __DIR__ . '/../tehnoloskeKlase/BaznaTabela.php';

class DBKategorijaIgre extends Tabela 
{
    public $Oznaka;
    public $Naziv; 
    public $UkupanBrojIgara;

    public function UcitajKolekcijuSvihKategorijaIgre()
    {
        $SQL = "select * from `".$this->NazivBazePodataka."`.`kategorija_igre` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function InkrementirajBrojIgara($IDSmer)
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

    public function DekrementirajBrojIgara($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojIgara',
            $KriterijumFiltriranja,
            'UkupanBrojIgara'
        );

        $NovaVrednost = max(0, (int)$StaraVrednost - 1);

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`kategorija_igre`
                SET UkupanBrojIgara=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }
}
?>
