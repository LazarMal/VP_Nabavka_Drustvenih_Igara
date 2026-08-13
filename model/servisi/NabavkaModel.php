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
        $upit = "SELECT * FROM `".$this->baza."`.`nabavka` ORDER BY DatumNabavke DESC, IDNabavke DESC";
        return mysqli_query($this->konekcija, $upit);
    }

    public function DajNabavkePoFilteru($brojNaloga, $datumNabavke, $dobavljac)
    {
        $uslovi = array();

        if ($brojNaloga != "") {
            $brojNalogaEsc = mysqli_real_escape_string($this->konekcija, $brojNaloga);
            $uslovi[] = "BrojNaloga LIKE '%".$brojNalogaEsc."%'";
        }

        if ($datumNabavke != "") {
            $datumEsc = mysqli_real_escape_string($this->konekcija, $datumNabavke);
            $uslovi[] = "DatumNabavke = '".$datumEsc."'";
        }

        if ($dobavljac != "") {
            $dobavljacEsc = mysqli_real_escape_string($this->konekcija, $dobavljac);
            $uslovi[] = "Dobavljac LIKE '%".$dobavljacEsc."%'";
        }

        $upit = "SELECT * FROM `".$this->baza."`.`nabavka`";

        if (count($uslovi) > 0) {
            $upit .= " WHERE ".implode(" AND ", $uslovi);
        }

        $upit .= " ORDER BY DatumNabavke DESC, IDNabavke DESC";

        return mysqli_query($this->konekcija, $upit);
    }

    public function DajNabavkuPoID($IDNabavke)
    {
        $IDNabavke = mysqli_real_escape_string($this->konekcija, $IDNabavke);

        $upit = "SELECT * FROM `".$this->baza."`.`nabavka`
                 WHERE IDNabavke = '".$IDNabavke."'
                 LIMIT 1";

        $rezultat = mysqli_query($this->konekcija, $upit);

        if (!$rezultat || mysqli_num_rows($rezultat) == 0) {
            return null;
        }

        return mysqli_fetch_assoc($rezultat);
    }

    public function DajNabavkuPoBrojuNaloga($brojNaloga)
    {
        $brojNalogaEsc = mysqli_real_escape_string($this->konekcija, $brojNaloga);

        $upit = "SELECT * FROM `".$this->baza."`.`nabavka`
                 WHERE BrojNaloga = '".$brojNalogaEsc."'
                 LIMIT 1";

        $rezultat = mysqli_query($this->konekcija, $upit);

        if (!$rezultat || mysqli_num_rows($rezultat) == 0) {
            return null;
        }

        return mysqli_fetch_assoc($rezultat);
    }

    public function DajStavkeNabavke($IDNabavke)
    {
        $IDNabavke = mysqli_real_escape_string($this->konekcija, $IDNabavke);

        $upit = "
        SELECT 
            stavka_nabavke.IDStavke AS IDStavke,
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
