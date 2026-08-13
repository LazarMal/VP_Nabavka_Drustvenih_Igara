<?php
require_once __DIR__ . '/../tehnoloskeKlase/BaznaTabela.php';

class DBDrustvenaIgraV extends Tabela
{
    public function DajSvePodatkeODrustvenimIgrama($filterParametar, $viewNaziv = 'svipodacioidrutvenimigramasaslikom')
    {
        $viewEsc = preg_replace('/[^a-z0-9_]/', '', $viewNaziv);
        if ($viewEsc === '') {
            $viewEsc = 'svipodacioidrutvenimigramasaslikom';
        }

        if (isset($filterParametar) && $filterParametar != "") {
            $konekcija = $this->OtvorenaKonekcija->konekcijaDB;
            $filterEsc = mysqli_real_escape_string($konekcija, $filterParametar);
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`".$viewEsc."`
                     WHERE `SifraIgre` LIKE '%".$filterEsc."%'
                     OR `Naziv` LIKE '%".$filterEsc."%'
                     OR `Proizvodjac` LIKE '%".$filterEsc."%'";
        } else {
            $upit = "SELECT * FROM `".$this->NazivBazePodataka."`.`".$viewEsc."`";
        }

        $this->UcitajSvePoUpitu($upit);
    }
}
?>
