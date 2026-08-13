<?php

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . "/../../model/servisi/NabavkaModel.php";
require_once __DIR__ . "/../../model/servisi/DrustvenaIgraModel.php";

class NabavkeController
{
    private $KonekcijaObject;
    private $konekcija;
    private $baza;
    private $NabavkaModel;
    private $DrustvenaIgraModel;

    public function __construct()
    {
        $this->KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
        $this->KonekcijaObject->connect();

        $this->konekcija = $this->KonekcijaObject->konekcijaDB;
        $this->baza = $this->KonekcijaObject->KompletanNazivBazePodataka;

        $this->NabavkaModel = new NabavkaModel($this->konekcija, $this->baza);
        $this->DrustvenaIgraModel = new DrustvenaIgraModel($this->konekcija, $this->baza);
    }

    public function DajSveNabavke()
    {
        return $this->NabavkaModel->DajSveNabavke();
    }

    public function DajNabavkePoFilteru($brojNaloga, $datumNabavke, $dobavljac)
    {
        return $this->NabavkaModel->DajNabavkePoFilteru($brojNaloga, $datumNabavke, $dobavljac);
    }

    public function DajNabavkuPoID($IDNabavke)
    {
        return $this->NabavkaModel->DajNabavkuPoID($IDNabavke);
    }

    public function DajNabavkuPoBrojuNaloga($brojNaloga)
    {
        return $this->NabavkaModel->DajNabavkuPoBrojuNaloga($brojNaloga);
    }

    public function DajStavkeNabavke($IDNabavke)
    {
        return $this->NabavkaModel->DajStavkeNabavke($IDNabavke);
    }

    public function DajDrustveneIgreZaNabavku()
    {
        return $this->DrustvenaIgraModel->DajSveDrustveneIgreZaNabavku();
    }

    public function DajNabavkeSaStavkama($rezultatNabavke)
    {
        return $this->NabavkaModel->DajNabavkeSaStavkama($rezultatNabavke);
    }

    public function DajNabavkaModel()
    {
        return $this->NabavkaModel;
    }

    public function IgraPostojiUKatalogu($sifraIgre)
    {
        return $this->NabavkaModel->IgraPostojiUKatalogu($sifraIgre);
    }

    public function PostojiBrojNaloga($brojNaloga)
    {
        return $this->NabavkaModel->PostojiBrojNaloga($this->KonekcijaObject, $brojNaloga);
    }

    public function PostojiBrojNalogaOsim($brojNaloga, $IDNabavke)
    {
        return $this->NabavkaModel->PostojiBrojNalogaOsim($this->KonekcijaObject, $brojNaloga, $IDNabavke);
    }

    public function SnimiNovuNabavku($nabavkaEntitet)
    {
        return $this->NabavkaModel->SnimiNovuNabavku($this->KonekcijaObject, $nabavkaEntitet);
    }

    public function IzmeniNabavku($IDNabavke, $brojNaloga, $datumNabavke, $dobavljac, $napomena, $stavkeZaSnimanje)
    {
        return $this->NabavkaModel->IzmeniNabavku(
            $this->KonekcijaObject,
            $IDNabavke,
            $brojNaloga,
            $datumNabavke,
            $dobavljac,
            $napomena,
            $stavkeZaSnimanje
        );
    }

    public function ObrisiNabavku($IDNabavke)
    {
        return $this->NabavkaModel->ObrisiNabavku($this->KonekcijaObject, $IDNabavke);
    }

    public function StavkaPripadaNalogu($IDStavke, $IDNabavke)
    {
        return $this->NabavkaModel->StavkaPripadaNalogu($IDStavke, $IDNabavke);
    }

    public function DajKonekcijaObject()
    {
        return $this->KonekcijaObject;
    }

    public function ZatvoriKonekciju()
    {
        $this->KonekcijaObject->disconnect();
    }
}

?>
