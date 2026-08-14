<?php

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . "/../../model/servisi/ReklamacijaModel.php";
require_once __DIR__ . "/../../model/servisi/DrustvenaIgraModel.php";

class ReklamacijeController
{
    private $KonekcijaObject;
    private $konekcija;
    private $baza;
    private $ReklamacijaModel;
    private $DrustvenaIgraModel;

    public function __construct()
    {
        $this->KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
        $this->KonekcijaObject->connect();

        $this->konekcija = $this->KonekcijaObject->konekcijaDB;
        $this->baza = $this->KonekcijaObject->KompletanNazivBazePodataka;

        $this->ReklamacijaModel = new ReklamacijaModel($this->konekcija, $this->baza);
        $this->DrustvenaIgraModel = new DrustvenaIgraModel($this->konekcija, $this->baza);
    }

    public function DajSveReklamacije()
    {
        return $this->ReklamacijaModel->DajSveReklamacije();
    }

    public function DajReklamacijePoFilteru($brojReklamacije, $datumReklamacije, $dobavljac)
    {
        return $this->ReklamacijaModel->DajReklamacijePoFilteru($brojReklamacije, $datumReklamacije, $dobavljac);
    }

    public function DajReklamacijuPoID($IDReklamacije)
    {
        return $this->ReklamacijaModel->DajReklamacijuPoID($IDReklamacije);
    }

    public function DajReklamacijuPoBrojuReklamacije($brojReklamacije)
    {
        return $this->ReklamacijaModel->DajReklamacijuPoBrojuReklamacije($brojReklamacije);
    }

    public function DajStavkeReklamacije($IDReklamacije)
    {
        return $this->ReklamacijaModel->DajStavkeReklamacije($IDReklamacije);
    }

    public function DajDrustveneIgreZaReklamaciju()
    {
        return $this->DrustvenaIgraModel->DajSveDrustveneIgreZaReklamaciju();
    }

    public function DajReklamacijeSaStavkama($rezultatReklamacije)
    {
        return $this->ReklamacijaModel->DajReklamacijeSaStavkama($rezultatReklamacije);
    }

    public function DajReklamacijaModel()
    {
        return $this->ReklamacijaModel;
    }

    public function IgraPostojiUKatalogu($sifraIgre)
    {
        return $this->ReklamacijaModel->IgraPostojiUKatalogu($sifraIgre);
    }

    public function PostojiBrojReklamacije($brojReklamacije)
    {
        return $this->ReklamacijaModel->PostojiBrojReklamacije($this->KonekcijaObject, $brojReklamacije);
    }

    public function PostojiBrojReklamacijeOsim($brojReklamacije, $IDReklamacije)
    {
        return $this->ReklamacijaModel->PostojiBrojReklamacijeOsim($this->KonekcijaObject, $brojReklamacije, $IDReklamacije);
    }

    public function SnimiNovuReklamaciju($reklamacijaEntitet)
    {
        return $this->ReklamacijaModel->SnimiNovuReklamaciju($this->KonekcijaObject, $reklamacijaEntitet);
    }

    public function IzmeniReklamaciju($IDReklamacije, $brojReklamacije, $datumReklamacije, $dobavljac, $napomena, $stavkeZaSnimanje)
    {
        return $this->ReklamacijaModel->IzmeniReklamaciju(
            $this->KonekcijaObject,
            $IDReklamacije,
            $brojReklamacije,
            $datumReklamacije,
            $dobavljac,
            $napomena,
            $stavkeZaSnimanje
        );
    }

    public function ObrisiReklamaciju($IDReklamacije)
    {
        return $this->ReklamacijaModel->ObrisiReklamaciju($this->KonekcijaObject, $IDReklamacije);
    }

    public function StavkaPripadaReklamaciji($IDStavkeReklamacije, $IDReklamacije)
    {
        return $this->ReklamacijaModel->StavkaPripadaReklamaciji($IDStavkeReklamacije, $IDReklamacije);
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
