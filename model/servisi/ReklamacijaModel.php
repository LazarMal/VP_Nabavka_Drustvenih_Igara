<?php

require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';
require_once __DIR__ . '/../../repozitorijumi/DBStavkaReklamacije.php';
require_once __DIR__ . '/DrustvenaIgraModel.php';

class ReklamacijaModel
{
    private $konekcija;
    private $baza;

    public function __construct($konekcija, $baza)
    {
        $this->konekcija = $konekcija;
        $this->baza = $baza;
    }

    private function DajKonekcijaObjectZaRepo()
    {
        $konekcijaObject = new stdClass();
        $konekcijaObject->konekcijaDB = $this->konekcija;
        $konekcijaObject->KompletanNazivBazePodataka = $this->baza;
        $konekcijaObject->VerzijaMYSQLNaredbi = "mysqli";

        return $konekcijaObject;
    }

    private function DajReklamacijaRepo()
    {
        return new DBReklamacija($this->DajKonekcijaObjectZaRepo(), "reklamacija");
    }

    private function DajStavkaRepo()
    {
        return new DBStavkaReklamacije($this->DajKonekcijaObjectZaRepo(), "stavka_reklamacije");
    }

    public function DajSveReklamacije()
    {
        return $this->DajReklamacijaRepo()->DajSveReklamacije();
    }

    public function DajReklamacijePoFilteru($brojReklamacije, $datumReklamacije, $dobavljac)
    {
        $uslovi = array();

        if ($brojReklamacije != "") {
            $brojReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $brojReklamacije);
            $uslovi[] = "BrojReklamacije LIKE '%".$brojReklamacijeEsc."%'";
        }

        if ($datumReklamacije != "") {
            $datumEsc = mysqli_real_escape_string($this->konekcija, $datumReklamacije);
            $uslovi[] = "DatumReklamacije = '".$datumEsc."'";
        }

        if ($dobavljac != "") {
            $dobavljacEsc = mysqli_real_escape_string($this->konekcija, $dobavljac);
            $uslovi[] = "Dobavljac LIKE '%".$dobavljacEsc."%'";
        }

        return $this->DajReklamacijaRepo()->DajReklamacijePoFilteru($uslovi);
    }

    public function DajReklamacijuPoID($IDReklamacije)
    {
        $IDReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $IDReklamacije);

        return $this->DajReklamacijaRepo()->DajReklamacijuPoID($IDReklamacijeEsc);
    }

    public function DajReklamacijuPoBrojuReklamacije($brojReklamacije)
    {
        $brojReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $brojReklamacije);

        return $this->DajReklamacijaRepo()->DajReklamacijuPoBrojuReklamacije($brojReklamacijeEsc);
    }

    public function DajStavkeReklamacije($IDReklamacije)
    {
        $IDReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $IDReklamacije);

        return $this->DajStavkaRepo()->DajStavkeZaPrikaz($IDReklamacijeEsc);
    }

    public function IgraPostojiUKatalogu($sifraIgre)
    {
        $drustvenaIgraModel = new DrustvenaIgraModel($this->konekcija, $this->baza);

        return $drustvenaIgraModel->IgraPostoji($sifraIgre);
    }

    public function PostojiBrojReklamacije($konekcijaObject, $brojReklamacije)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';

        $repo = new DBReklamacija($konekcijaObject, "reklamacija");

        return $repo->PostojiBrojReklamacije($brojReklamacije);
    }

    public function PostojiBrojReklamacijeOsim($konekcijaObject, $brojReklamacije, $IDReklamacije)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';

        $repo = new DBReklamacija($konekcijaObject, "reklamacija");

        return $repo->PostojiBrojReklamacijeOsim($brojReklamacije, $IDReklamacije);
    }

    public function SnimiNovuReklamaciju($konekcijaObject, $reklamacijaEntitet)
    {
        require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBStavkaReklamacije.php';

        $konekcija = $konekcijaObject->konekcijaDB;

        $brojReklamacijeEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->BrojReklamacije);
        $datumReklamacijeEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->DatumReklamacije);
        $dobavljacEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->Dobavljac);
        $napomenaEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->Napomena);
        $reklamacijuEvidentiraoEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->ReklamacijuEvidentirao);
        $datumEvidentiranjaEsc = mysqli_real_escape_string($konekcija, $reklamacijaEntitet->DatumEvidentiranja);

        $ReklamacijaObject = new DBReklamacija($konekcijaObject, "reklamacija");
        $StavkaObject = new DBStavkaReklamacije($konekcijaObject, "stavka_reklamacije");
        $TransakcijaObject = new Transakcija($konekcijaObject);

        $TransakcijaObject->ZapocniTransakciju();
        $utvrdjenaGreska = "";

        $utvrdjenaGreska .= $ReklamacijaObject->DodajReklamaciju(
            $brojReklamacijeEsc,
            $datumReklamacijeEsc,
            $dobavljacEsc,
            $napomenaEsc,
            $reklamacijuEvidentiraoEsc,
            $datumEvidentiranjaEsc
        );

        $idReklamacije = $ReklamacijaObject->DajPoslednjiID();

        if ($utvrdjenaGreska != "" || $idReklamacije == null || $idReklamacije == "") {
            $TransakcijaObject->ZavrsiTransakciju("Greska pri snimanju glavnog dela reklamacije.");
            return array("uspeh" => false, "greska" => "Greska pri snimanju glavnog dela reklamacije.");
        }

        foreach ($reklamacijaEntitet->ListaStavki as $stavka) {
            $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka->DrustvenaIgra->SifraIgre);
            $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka->Kolicina);
            $cenaEsc = mysqli_real_escape_string($konekcija, $stavka->Cena);
            $razlogReklamacijeEsc = mysqli_real_escape_string($konekcija, $stavka->RazlogReklamacije);

            $utvrdjenaGreska .= $StavkaObject->DodajStavkuReklamacije($idReklamacije, $sifraIgreEsc, $kolicinaEsc, $cenaEsc, $razlogReklamacijeEsc);
        }

        $TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

        if ($utvrdjenaGreska != "") {
            return array("uspeh" => false, "greska" => $utvrdjenaGreska);
        }

        return array("uspeh" => true, "greska" => "");
    }

    public function IzmeniReklamaciju($konekcijaObject, $IDReklamacije, $brojReklamacije, $datumReklamacije, $dobavljac, $napomena, $stavkeZaSnimanje)
    {
        require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';
        require_once __DIR__ . '/../../repozitorijumi/DBStavkaReklamacije.php';

        $konekcija = $konekcijaObject->konekcijaDB;

        $IDReklamacijeEsc = mysqli_real_escape_string($konekcija, $IDReklamacije);
        $brojReklamacijeEsc = mysqli_real_escape_string($konekcija, $brojReklamacije);
        $datumReklamacijeEsc = mysqli_real_escape_string($konekcija, $datumReklamacije);
        $dobavljacEsc = mysqli_real_escape_string($konekcija, $dobavljac);
        $napomenaEsc = mysqli_real_escape_string($konekcija, $napomena);

        $ReklamacijaObject = new DBReklamacija($konekcijaObject, "reklamacija");
        $StavkaObject = new DBStavkaReklamacije($konekcijaObject, "stavka_reklamacije");
        $TransakcijaObject = new Transakcija($konekcijaObject);

        $TransakcijaObject->ZapocniTransakciju();
        $utvrdjenaGreska = "";

        $postojeceStavkeIds = $StavkaObject->DajIDStavkiZaReklamaciju($IDReklamacijeEsc);

        $utvrdjenaGreska .= $ReklamacijaObject->IzmeniReklamaciju(
            $IDReklamacijeEsc,
            $brojReklamacijeEsc,
            $datumReklamacijeEsc,
            $dobavljacEsc,
            $napomenaEsc
        );

        $poslateStavkeIds = array();

        foreach ($stavkeZaSnimanje as $stavka) {
            $sifraIgreEsc = mysqli_real_escape_string($konekcija, $stavka['SifraIgre']);
            $kolicinaEsc = mysqli_real_escape_string($konekcija, $stavka['Kolicina']);
            $cenaEsc = mysqli_real_escape_string($konekcija, $stavka['Cena']);
            $razlogReklamacijeEsc = mysqli_real_escape_string($konekcija, $stavka['RazlogReklamacije']);

            if ($stavka['IDStavkeReklamacije'] != "") {
                $idStavkeEsc = mysqli_real_escape_string($konekcija, $stavka['IDStavkeReklamacije']);
                $poslateStavkeIds[] = $idStavkeEsc;
                $utvrdjenaGreska .= $StavkaObject->IzmeniStavkuReklamacije(
                    $idStavkeEsc,
                    $IDReklamacijeEsc,
                    $sifraIgreEsc,
                    $kolicinaEsc,
                    $cenaEsc,
                    $razlogReklamacijeEsc
                );
            } else {
                $utvrdjenaGreska .= $StavkaObject->DodajStavkuReklamacije(
                    $IDReklamacijeEsc,
                    $sifraIgreEsc,
                    $kolicinaEsc,
                    $cenaEsc,
                    $razlogReklamacijeEsc
                );
            }
        }

        foreach ($postojeceStavkeIds as $postojeciId) {
            if (!in_array($postojeciId, $poslateStavkeIds)) {
                $idZaBrisanjeEsc = mysqli_real_escape_string($konekcija, $postojeciId);
                $utvrdjenaGreska .= $StavkaObject->ObrisiStavkuReklamacije($idZaBrisanjeEsc, $IDReklamacijeEsc);
            }
        }

        $TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

        if ($utvrdjenaGreska != "") {
            return array("uspeh" => false, "greska" => $utvrdjenaGreska);
        }

        return array("uspeh" => true, "greska" => "");
    }

    public function ObrisiReklamaciju($konekcijaObject, $IDReklamacije)
    {
        require_once __DIR__ . '/../../repozitorijumi/DBReklamacija.php';

        $konekcija = $konekcijaObject->konekcijaDB;
        $IDReklamacijeEsc = mysqli_real_escape_string($konekcija, $IDReklamacije);

        $ReklamacijaObject = new DBReklamacija($konekcijaObject, "reklamacija");

        return $ReklamacijaObject->ObrisiReklamaciju($IDReklamacijeEsc);
    }

    public function StavkaPripadaReklamaciji($IDStavkeReklamacije, $IDReklamacije)
    {
        $IDStavkeReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $IDStavkeReklamacije);
        $IDReklamacijeEsc = mysqli_real_escape_string($this->konekcija, $IDReklamacije);

        return $this->DajStavkaRepo()->StavkaPripadaReklamaciji($IDStavkeReklamacijeEsc, $IDReklamacijeEsc);
    }

    public function DajReklamacijeSaStavkama($rezultatReklamacije)
    {
        $lista = array();

        if (!$rezultatReklamacije) {
            return $lista;
        }

        while ($reklamacija = mysqli_fetch_assoc($rezultatReklamacije)) {
            $stavke = array();
            $rezultatStavke = $this->DajStavkeReklamacije($reklamacija['IDReklamacije']);
            if ($rezultatStavke) {
                while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
                    $stavke[] = $stavka;
                }
            }
            $reklamacija['stavke'] = $stavke;
            $lista[] = $reklamacija;
        }

        return $lista;
    }
}

?>
