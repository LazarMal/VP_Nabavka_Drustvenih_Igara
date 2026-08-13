<?php

require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTransakcija.php';
require_once __DIR__ . '/../../repozitorijumi/DBDrustvenaIgra.php';
require_once __DIR__ . '/../../repozitorijumi/DBDrustvenaIgraSP.php';
require_once __DIR__ . '/../../repozitorijumi/DBKategorijaIgre.php';

class DrustvenaIgraModel
{
    private $konekcija;
    private $baza;

    public function __construct($konekcija, $baza)
    {
        $this->konekcija = $konekcija;
        $this->baza = $baza;
    }

    public function DajSveDrustveneIgreZaNabavku()
    {
        $upit = "SELECT SifraIgre, Naziv
                 FROM `".$this->baza."`.`drustvena_igra`
                 ORDER BY Naziv ASC";

        return mysqli_query($this->konekcija, $upit);
    }

    public function PostojiSifraIgre($sifraIgre)
    {
        $sifraEsc = mysqli_real_escape_string($this->konekcija, $sifraIgre);
        $upit = "SELECT SifraIgre FROM `".$this->baza."`.`drustvena_igra` WHERE SifraIgre='".$sifraEsc."' LIMIT 1";
        $rez = mysqli_query($this->konekcija, $upit);
        return $rez && mysqli_num_rows($rez) > 0;
    }

    public function IgraPostoji($sifraIgre)
    {
        return $this->PostojiSifraIgre($sifraIgre);
    }

    public function SnimiNovuDrustvenuIgru($konekcijaObject, $sifraIgre, $naziv, $proizvodjac, $oznakaKategorije, $nazivFajlaSlike)
    {
        $transakcija = new Transakcija($konekcijaObject);
        $transakcija->ZapocniTransakciju();

        $igraRepo = new DBDrustvenaIgra($konekcijaObject, 'drustvena_igra');
        $igraRepo->SifraIgre = $sifraIgre;
        $igraRepo->Naziv = $naziv;
        $igraRepo->Proizvodjac = $proizvodjac;
        $igraRepo->OznakaKategorije = $oznakaKategorije;
        $igraRepo->NazivFajlaSlike = $nazivFajlaSlike;

        $greska1 = $igraRepo->DodajNovuDrustvenuIgru();

        $katRepo = new DBKategorijaIgre($konekcijaObject, 'kategorija_igre');
        $greska2 = $katRepo->InkrementirajBrojIgara($oznakaKategorije);

        $greska = $greska1 . $greska2;
        $transakcija->ZavrsiTransakciju($greska);

        return $greska;
    }

    public function SnimiNovuDrustvenuIgruSP($konekcijaObject, $sifraIgre, $naziv, $proizvodjac, $oznakaKategorije, $nazivFajlaSlike)
    {
        $spRepo = new DBDrustvenaIgraSP($konekcijaObject, 'drustvena_igra');
        $spRepo->SifraIgre = $sifraIgre;
        $spRepo->Naziv = $naziv;
        $spRepo->Proizvodjac = $proizvodjac;
        $spRepo->OznakaKategorije = $oznakaKategorije;
        $spRepo->NazivFajlaSlike = $nazivFajlaSlike;

        return $spRepo->DodajNovuDrustvenuIgru();
    }

    public function IzmeniDrustvenuIgru($konekcijaObject, $staraSifra, $sifraIgre, $naziv, $proizvodjac, $oznakaKategorije, $nazivFajlaSlike)
    {
        $transakcija = new Transakcija($konekcijaObject);
        $transakcija->ZapocniTransakciju();

        $igraRepo = new DBDrustvenaIgra($konekcijaObject, 'drustvena_igra');
        $staraOznaka = $igraRepo->DajOznakuKategorijeIgre($staraSifra);

        $greska1 = $igraRepo->IzmeniDrustvenuIgru($staraSifra, $sifraIgre, $naziv, $proizvodjac, $oznakaKategorije, $nazivFajlaSlike);

        $greska2 = "";
        if ($staraOznaka != "" && $staraOznaka != $oznakaKategorije) {
            $katRepo = new DBKategorijaIgre($konekcijaObject, 'kategorija_igre');
            $greska2 = $katRepo->DekrementirajBrojIgara($staraOznaka) . $katRepo->InkrementirajBrojIgara($oznakaKategorije);
        }

        $greska = $greska1 . $greska2;
        $transakcija->ZavrsiTransakciju($greska);

        return $greska;
    }

    public function ObrisiDrustvenuIgru($konekcijaObject, $sifraIgre)
    {
        $sifraEsc = mysqli_real_escape_string($this->konekcija, $sifraIgre);

        $provera = mysqli_query(
            $this->konekcija,
            "SELECT COUNT(*) AS broj FROM `".$this->baza."`.`stavka_nabavke` WHERE SifraIgre='".$sifraEsc."'"
        );
        $red = mysqli_fetch_assoc($provera);
        if ($red && (int)$red['broj'] > 0) {
            return "Igra se ne moze obrisati jer postoji u evidentiranim nalozima.";
        }

        $igraRepo = new DBDrustvenaIgra($konekcijaObject, 'drustvena_igra');
        if (!$igraRepo->IgraPostoji($sifraIgre)) {
            return "Igra za brisanje ne postoji u katalogu.";
        }

        $oznaka = $igraRepo->DajOznakuKategorijeIgre($sifraIgre);

        $transakcija = new Transakcija($konekcijaObject);
        $transakcija->ZapocniTransakciju();

        $greska1 = $igraRepo->ObrisiDrustvenuIgru($sifraIgre);

        $greska2 = "";
        if ($greska1 === "") {
            $katRepo = new DBKategorijaIgre($konekcijaObject, 'kategorija_igre');
            $greska2 = $katRepo->DekrementirajBrojIgara($oznaka);
        }

        $greska = $greska1 . $greska2;
        $transakcija->ZavrsiTransakciju($greska);

        return $greska;
    }
}

?>
