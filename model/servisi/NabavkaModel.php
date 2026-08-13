<?php

class NabavkaModel
{
    private $konekcija;
    private $baza;

    public function __construct($konekcija, $baza)
    {
        $this->konekcija = $konekcija;
        $this->baza = $baza;
    }

    public function DajSveNabavke()
    {
        $upit = "SELECT * FROM `".$this->baza."`.`nabavka` ORDER BY DatumNabavke DESC";
        return mysqli_query($this->konekcija, $upit);
    }

    public function DajStavkeNabavke($IDNabavke)
    {
        $IDNabavke = mysqli_real_escape_string($this->konekcija, $IDNabavke);

        $upit = "
        SELECT 
            stavka_nabavke.SifraIgre AS SifraIgre,
            drustvena_igra.Naziv AS Naziv,
            stavka_nabavke.Kolicina AS Kolicina,
            stavka_nabavke.Cena AS Cena,
            (stavka_nabavke.Kolicina * stavka_nabavke.Cena) AS Ukupno
        FROM `".$this->baza."`.`stavka_nabavke`
        INNER JOIN `".$this->baza."`.`drustvena_igra`
        ON stavka_nabavke.SifraIgre = drustvena_igra.SifraIgre
        WHERE stavka_nabavke.IDNabavke = '".$IDNabavke."'
        ORDER BY stavka_nabavke.IDStavke ASC";

        return mysqli_query($this->konekcija, $upit);
    }
}

?>