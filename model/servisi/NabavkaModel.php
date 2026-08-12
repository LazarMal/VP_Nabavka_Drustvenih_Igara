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
            stavka_nabavke.ISBN,
            knjiga.Naziv,
            SUM(stavka_nabavke.Kolicina) AS Kolicina,
            stavka_nabavke.Cena,
            SUM(stavka_nabavke.Kolicina * stavka_nabavke.Cena) AS Ukupno
        FROM `".$this->baza."`.`stavka_nabavke`
        INNER JOIN `".$this->baza."`.`knjiga`
        ON stavka_nabavke.ISBN = knjiga.ISBN
        WHERE stavka_nabavke.IDNabavke = '".$IDNabavke."'
        GROUP BY stavka_nabavke.ISBN, knjiga.Naziv, stavka_nabavke.Cena";

        return mysqli_query($this->konekcija, $upit);
    }
}

?>