<?php
class DBKnjigaV extends Tabela 
{
    public function DajSvePodatkeOKnjigama($filterParametar)
    {
        if (isset($filterParametar) && $filterParametar != "")
        {
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`svipodacioidrutvenimigramasaslikom`
                     WHERE `SifraIgre` LIKE '%".$filterParametar."%'
                     OR `Naziv` LIKE '%".$filterParametar."%'
                     OR `Proizvodjac` LIKE '%".$filterParametar."%'";
        }
        else
        {
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`svipodacioidrutvenimigramasaslikom`";
        }

        $this->UcitajSvePoUpitu($upit);
    }
}
?>
