<?php
class DBKnjigaV extends Tabela 
{
    public function DajSvePodatkeOKnjigama($filterParametar)
    {
        if (isset($filterParametar) && $filterParametar != "")
        {
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`svipodacioknjigamasaslikom`
                     WHERE `ISBN` LIKE '%".$filterParametar."%'
                     OR `Naziv` LIKE '%".$filterParametar."%'";
        }
        else
        {
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`svipodacioknjigamasaslikom`";
        }

        $this->UcitajSvePoUpitu($upit);
    }
}
?>