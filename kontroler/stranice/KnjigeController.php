<?php
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaKonekcija.php';
require_once __DIR__ . '/../../tehnoloskeKlase/BaznaTabela.php';
require_once __DIR__ . '/../../repozitorijumi/DBZanr.php';
require_once __DIR__ . '/../../repozitorijumi/DBKnjiga.php';
require_once __DIR__ . '/../../repozitorijumi/DBKnjigaV.php';

class KnjigeController
{
    private $KonekcijaObject;

    public function __construct()
    {
        $this->KonekcijaObject = new Konekcija(__DIR__ . '/../../tehnoloskeKlase/BaznaParametriKonekcije.xml');
        $this->KonekcijaObject->connect();
    }

    public function DajZanrove()
    {
        $zanrObject = new DBZanr($this->KonekcijaObject, 'zanr');
        $zanrObject->UcitajKolekcijuSvihZanrova();

        return $zanrObject;
    }

    public function DajSveZanrove()
    {
        return $this->DajZanrove();
    }

    public function DajSveKnjige($filter = null)
    {
        $knjigaViewObject = new DBKnjigaV($this->KonekcijaObject, 'svipodacioknjigamasaslikom');
        $knjigaViewObject->DajSvePodatkeOKnjigama($filter);
        return $knjigaViewObject;
    }

    public function DajKnjiguPoISBN($isbn)
    {
        $knjigaObject = new DBKnjiga($this->KonekcijaObject, 'knjiga');
        $knjigaObject->UcitajKnjiguPoISBN($isbn);
        return $knjigaObject;
    }

    public function DajKnjiguZaIzmenu($isbn)
    {
        return $this->DajKnjiguPoISBN($isbn);
    }

    public function ZatvoriKonekciju()
    {
        $this->KonekcijaObject->disconnect();
    }
    public function DajKnjiguZaStampu($filter)
    {
        $knjigaObject = new DBKnjigaV(
            $this->KonekcijaObject,
            'svipodacioknjigamasaslikom'
        );

        $knjigaObject->DajSvePodatkeOKnjigama($filter);

        return $knjigaObject;
    }
}
?>