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

    public function IgraPostojiUKatalogu($sifraIgre)
    {
        $sifraIgreEsc = mysqli_real_escape_string($this->konekcija, $sifraIgre);

        $upit = "SELECT SifraIgre FROM `".$this->baza."`.`drustvena_igra`
                 WHERE SifraIgre = '".$sifraIgreEsc."'
                 LIMIT 1";

        $rezultat = mysqli_query($this->konekcija, $upit);

        return ($rezultat && mysqli_num_rows($rezultat) > 0);
    }

    public function PostojiBrojNaloga($konekcijaObject, $brojNaloga)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBNabavka.php';

        $repo = new DBNabavka($konekcijaObject, "nabavka");

        return $repo->PostojiBrojNaloga($brojNaloga);
    }

    public function PostojiBrojNalogaOsim($konekcijaObject, $brojNaloga, $IDNabavke)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBNabavka.php';

        $repo = new DBNabavka($konekcijaObject, "nabavka");

        return $repo->PostojiBrojNalogaOsim($brojNaloga, $IDNabavke);
    }

    public function SnimiNovuNabavku($konekcijaObject, $nabavkaEntitet)
    {
        require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBNabavka.php';
        require_once __DIR__ . '/../../repozitorijumi/DBStavkaNabavke.php';

        $konekcija = $konekcijaObject->konekcijaDB;

        $brojNalogaEsc = mysqli_real_escape_string($konekcija, $nabavkaEntitet->BrojNaloga);
        $datumNabavkeEsc = mysqli_real_escape_string($konekcija, $nabavkaEntitet->DatumNabavke);
        $dobavljacEsc = mysqli_real_escape_string($konekcija, $nabavkaEntitet->Dobavljac);
        $napomenaEsc = mysqli_real_escape_string($konekcija, $nabavkaEntitet->Napomena);
        $nalogEvidentiraoEsc = mysqli_real_escape_string($konekcija, $nabavkaEntitet->NalogEvidentirao);

        $NabavkaObject = new DBNabavka($konekcijaObject, "nabavka");
        $StavkaObject = new DBStavkaNabavke($konekcijaObject, "stavka_nabavke");
        $TransakcijaObject = new Transakcija($konekcijaObject);

        $TransakcijaObject->ZapocniTransakciju();
        $utvrdjenaGreska = "";

        $utvrdjenaGreska .= $NabavkaObject->DodajNabavku(
            $brojNalogaEsc,
            $datumNabavkeEsc,
            $dobavljacEsc,
            $napomenaEsc,
            $nalogEvidentiraoEsc
        );

        $idNabavke = $NabavkaObject->DajPoslednjiID();

        if ($utvrdjenaGreska != "" || $idNabavke == null || $idNabavke == "") {
            $TransakcijaObject->ZavrsiTransakciju("Greska pri snimanju glavnog dela naloga.");
            return array("uspeh" => false, "greska" => "Greska pri snimanju glavnog dela naloga.");
        }

        foreach ($nabavkaEntitet->ListaStavki as $stavka) {
            $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka->DrustvenaIgra->SifraIgre);
            $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka->Kolicina);
            $cenaEsc = mysqli_real_escape_string($konekcija, $stavka->Cena);

            $utvrdjenaGreska .= $StavkaObject->DodajStavkuNabavke($idNabavke, $sifraIgreEsc, $kolicinaEsc, $cenaEsc);
        }

        $TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

        if ($utvrdjenaGreska != "") {
            return array("uspeh" => false, "greska" => $utvrdjenaGreska);
        }

        return array("uspeh" => true, "greska" => "");
    }

    public function IzmeniNabavku($konekcijaObject, $IDNabavke, $brojNaloga, $datumNabavke, $dobavljac, $napomena, $stavkeZaSnimanje)
    {
        require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBNabavka.php';
        require_once __DIR__ . '/../../repozitorijumi/DBStavkaNabavke.php';

        $konekcija = $konekcijaObject->konekcijaDB;

        $IDNabavkeEsc = mysqli_real_escape_string($konekcija, $IDNabavke);
        $brojNalogaEsc = mysqli_real_escape_string($konekcija, $brojNaloga);
        $datumNabavkeEsc = mysqli_real_escape_string($konekcija, $datumNabavke);
        $dobavljacEsc = mysqli_real_escape_string($konekcija, $dobavljac);
        $napomenaEsc = mysqli_real_escape_string($konekcija, $napomena);

        $NabavkaObject = new DBNabavka($konekcijaObject, "nabavka");
        $StavkaObject = new DBStavkaNabavke($konekcijaObject, "stavka_nabavke");
        $TransakcijaObject = new Transakcija($konekcijaObject);

        $TransakcijaObject->ZapocniTransakciju();
        $utvrdjenaGreska = "";

        $postojeceStavkeIds = $StavkaObject->DajIDStavkiZaNabavku($IDNabavkeEsc);

        $utvrdjenaGreska .= $NabavkaObject->IzmeniNabavku(
            $IDNabavkeEsc,
            $brojNalogaEsc,
            $datumNabavkeEsc,
            $dobavljacEsc,
            $napomenaEsc
        );

        $poslateStavkeIds = array();

        foreach ($stavkeZaSnimanje as $stavka) {
            $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka['SifraIgre']);
            $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka['Kolicina']);
            $cenaEsc = mysqli_real_escape_string($konekcija, $stavka['Cena']);

            if ($stavka['IDStavke'] != "") {
                $idStavkeEsc = mysqli_real_escape_string($konekcija, $stavka['IDStavke']);
                $poslateStavkeIds[] = $idStavkeEsc;
                $utvrdjenaGreska .= $StavkaObject->IzmeniStavkuNabavke(
                    $idStavkeEsc,
                    $IDNabavkeEsc,
                    $sifraIgreEsc,
                    $kolicinaEsc,
                    $cenaEsc
                );
            } else {
                $utvrdjenaGreska .= $StavkaObject->DodajStavkuNabavke(
                    $IDNabavkeEsc,
                    $sifraIgreEsc,
                    $kolicinaEsc,
                    $cenaEsc
                );
            }
        }

        foreach ($postojeceStavkeIds as $postojeciId) {
            if (!in_array($postojeciId, $poslateStavkeIds)) {
                $idZaBrisanjeEsc = mysqli_real_escape_string($konekcija, $postojeciId);
                $utvrdjenaGreska .= $StavkaObject->ObrisiStavkuNabavke($idZaBrisanjeEsc, $IDNabavkeEsc);
            }
        }

        $TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

        if ($utvrdjenaGreska != "") {
            return array("uspeh" => false, "greska" => $utvrdjenaGreska);
        }

        return array("uspeh" => true, "greska" => "");
    }

    public function ObrisiNabavku($konekcijaObject, $IDNabavke)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBNabavka.php';

        $konekcija = $konekcijaObject->konekcijaDB;
        $IDNabavkeEsc = mysqli_real_escape_string($konekcija, $IDNabavke);

        $NabavkaObject = new DBNabavka($konekcijaObject, "nabavka");

        return $NabavkaObject->ObrisiNabavku($IDNabavkeEsc);
    }

    public function StavkaPripadaNalogu($IDStavke, $IDNabavke)
    {
        $IDStavkeEsc = mysqli_real_escape_string($this->konekcija, $IDStavke);
        $IDNabavkeEsc = mysqli_real_escape_string($this->konekcija, $IDNabavke);

        $upit = "SELECT IDStavke FROM `".$this->baza."`.`stavka_nabavke`
                 WHERE IDStavke = '".$IDStavkeEsc."'
                 AND IDNabavke = '".$IDNabavkeEsc."'
                 LIMIT 1";

        $rezultat = mysqli_query($this->konekcija, $upit);

        return ($rezultat && mysqli_num_rows($rezultat) > 0);
    }
}

?>
