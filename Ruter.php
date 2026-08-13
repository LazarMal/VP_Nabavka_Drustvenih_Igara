<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$stranica = isset($_GET['stranica']) ? $_GET['stranica'] : 'index';

function proveriSesiju()
{
    if (!isset($_SESSION["korisnik"])) {
        header('Location:Ruter.php?stranica=index');
        exit();
    }
}

function odjaviKorisnika()
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location:Ruter.php?stranica=prijava');
    exit();
}

switch ($stranica) {

    case 'index':
        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();
        $filter = isset($_GET['filtriraj']) ? $_GET['filter'] : null;
        $KnjigaViewObject = $KnjigeController->DajSveKnjige($filter);

        include 'index.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'prijava':
        include 'pogledi/prijava.php';
        break;

    case 'odjava':
        odjaviKorisnika();
        break;

    case 'welcome':
        proveriSesiju();
        include 'pogledi/Welcome.php';
        break;

    case 'knjige':
        proveriSesiju();

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();
        $filter = isset($_GET['filtriraj']) ? $_GET['filter'] : null;
        $KnjigaViewObject = $KnjigeController->DajSveKnjige($filter);

        include 'pogledi/KnjigeLista.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'unos':
        proveriSesiju();

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();

        $ZanrObject = $KnjigeController->DajSveZanrove();
        $KolekcijaZapisa = $ZanrObject->Kolekcija;
        $UkupanBrojZapisa = $ZanrObject->BrojZapisa;

        include 'pogledi/unos.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'unosSP':
        proveriSesiju();

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();

        $ZanrObject = $KnjigeController->DajSveZanrove();
        $KolekcijaZapisa = $ZanrObject->Kolekcija;
        $UkupanBrojZapisa = $ZanrObject->BrojZapisa;

        include 'pogledi/unosSP.php';

        $KnjigeController->ZatvoriKonekciju();
        break;
    case 'izmenaForm':
        proveriSesiju();

        $StariSifraIgreZaIzmenu = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : null;

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();

        $ZanrObject = $KnjigeController->DajSveZanrove();
        $KnjigaObject = $KnjigeController->DajKnjiguPoISBN($StariSifraIgreZaIzmenu);

        $KolekcijaZapisa = $ZanrObject->Kolekcija;
        $UkupanBrojZapisa = $ZanrObject->BrojZapisa;

        $KolekcijaZapisaStudenata = $KnjigaObject->Kolekcija;
        $UkupanBrojZapisaStudenata = $KnjigaObject->BrojZapisa;

        if ($UkupanBrojZapisaStudenata > 0) {
            $row = 0;
            $StariSifraIgre = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 0);
            $StariNaziv = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 1);
            $StariProizvodjac = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 2);
            $StaraOznakaKategorije = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 3);
            $StariNazivFajlaSlike = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 4);
        } else {
            $StariSifraIgre = "";
            $StariNaziv = "";
            $StariProizvodjac = "";
            $StaraOznakaKategorije = "";
            $StariNazivFajlaSlike = "";
        }

        include 'pogledi/KnjigaIzmeniForm.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'obrisiKnjigu':
        proveriSesiju();
        require 'kontroler/akcije/KnjigaObrisi.php';
        break;

    case 'stampa':
        proveriSesiju();

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();
        $filter = isset($_GET['filtriraj']) ? $_GET['filter'] : null;
        $KnjigaViewObject = $KnjigeController->DajSveKnjige($filter);

        include 'pogledi/KnjigeStampa.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'parametarskaStampa':
        proveriSesiju();
        include 'pogledi/KnjigeParametarskaStampa.php';
        break;

    case 'stampaJedneKnjige':
        proveriSesiju();

        $ISBNZaStampu = isset($_POST['BrojIndeksaFilter']) ? $_POST['BrojIndeksaFilter'] : null;

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();
        $KnjigaObject = $KnjigeController->DajKnjiguZaStampu($ISBNZaStampu);

        $KolekcijaZapisaStudenata = $KnjigaObject->Kolekcija;
        $UkupanBrojZapisaStudenata = $KnjigaObject->BrojZapisa;

        if ($UkupanBrojZapisaStudenata > 0) {
            $row = 0;
            $ISBN = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 0);
            $Naziv = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 1);
            $Autor = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 2);
            $NazivZanra = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 3);
            $NazivFajlaSlike = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 4);
        }

        include 'pogledi/StampaPodatakaOKnjizi.php';

        $KnjigeController->ZatvoriKonekciju();
        break;

    case 'novaNabavka':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();
        $rezultatKnjige = $NabavkeController->DajKnjigeZaNabavku();

        $optionsKnjige = "<option value=\"\">изаберите игру...</option>";

        while ($igra = mysqli_fetch_assoc($rezultatKnjige)) {
            $optionsKnjige .= "<option value='".$igra['SifraIgre']."' data-cena='".$igra['Cena']."'>
                    ".$igra['Naziv']." - ".$igra['SifraIgre']."
                  </option>";
        }

        include 'pogledi/NovaNabavka.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'nabavke':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();

        $filterBrojNaloga = "";
        $filterDatumNabavke = "";
        $filterDobavljac = "";

        if (isset($_GET['filtriraj']) && !isset($_GET['svi'])) {
            $filterBrojNaloga = isset($_GET['filterBrojNaloga']) ? trim($_GET['filterBrojNaloga']) : "";
            $filterDatumNabavke = isset($_GET['filterDatumNabavke']) ? trim($_GET['filterDatumNabavke']) : "";
            $filterDobavljac = isset($_GET['filterDobavljac']) ? trim($_GET['filterDobavljac']) : "";
            $rezultatNabavke = $NabavkeController->DajNabavkePoFilteru($filterBrojNaloga, $filterDatumNabavke, $filterDobavljac);
        } else {
            $rezultatNabavke = $NabavkeController->DajSveNabavke();
        }

        include 'pogledi/NabavkeLista.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'nabavkaDetalj':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();

        $IDNabavke = isset($_GET['id']) ? trim($_GET['id']) : "";
        $nabavka = $IDNabavke != "" ? $NabavkeController->DajNabavkuPoID($IDNabavke) : null;
        $rezultatStavke = ($nabavka != null) ? $NabavkeController->DajStavkeNabavke($IDNabavke) : null;

        include 'pogledi/NabavkaDetalj.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'obrisiNabavku':
        proveriSesiju();
        require 'kontroler/akcije/nabavkaObrisi.php';
        break;

    default:
        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();
        $filter = null;
        $KnjigaViewObject = $KnjigeController->DajSveKnjige($filter);

        include 'index.php';

        $KnjigeController->ZatvoriKonekciju();
        break;
}
?>