<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$stranica = isset($_GET['stranica']) ? $_GET['stranica'] : 'index';

function proveriSesiju()
{
    if (!isset($_SESSION["korisnik"])) {
        header('Location:Ruter.php?stranica=prijava');
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
        if (!isset($_SESSION["korisnik"])) {
            header('Location:Ruter.php?stranica=prijava');
            exit();
        }
        header('Location:Ruter.php?stranica=welcome');
        exit();
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

    case 'drustveneIgre':
        proveriSesiju();

        require_once 'kontroler/stranice/DrustveneIgreController.php';

        $DrustveneIgreController = new DrustveneIgreController();
        $filter = isset($_GET['filtriraj']) ? $_GET['filter'] : null;
        $DrustvenaIgraViewObject = $DrustveneIgreController->DajSveDrustveneIgre($filter);

        include 'pogledi/DrustvenaIgraLista.php';

        $DrustveneIgreController->ZatvoriKonekciju();
        break;

    case 'unos':
        proveriSesiju();

        require_once 'kontroler/stranice/DrustveneIgreController.php';

        $DrustveneIgreController = new DrustveneIgreController();

        $KategorijaIgreObject = $DrustveneIgreController->DajSveKategorijeIgre();
        $KolekcijaZapisa = $KategorijaIgreObject->Kolekcija;
        $UkupanBrojZapisa = $KategorijaIgreObject->BrojZapisa;

        include 'pogledi/unos.php';

        $DrustveneIgreController->ZatvoriKonekciju();
        break;

    case 'unosSP':
        proveriSesiju();

        require_once 'kontroler/stranice/DrustveneIgreController.php';

        $DrustveneIgreController = new DrustveneIgreController();

        $KategorijaIgreObject = $DrustveneIgreController->DajSveKategorijeIgre();
        $KolekcijaZapisa = $KategorijaIgreObject->Kolekcija;
        $UkupanBrojZapisa = $KategorijaIgreObject->BrojZapisa;

        include 'pogledi/unosSP.php';

        $DrustveneIgreController->ZatvoriKonekciju();
        break;
    case 'izmenaForm':
        proveriSesiju();

        $StariSifraIgreZaIzmenu = isset($_POST['sifraIgre']) ? $_POST['sifraIgre'] : null;

        require_once 'kontroler/stranice/DrustveneIgreController.php';

        $DrustveneIgreController = new DrustveneIgreController();

        $KategorijaIgreObject = $DrustveneIgreController->DajSveKategorijeIgre();
        $DrustvenaIgraObject = $DrustveneIgreController->DajDrustvenuIgruPoSifri($StariSifraIgreZaIzmenu);

        $KolekcijaZapisa = $KategorijaIgreObject->Kolekcija;
        $UkupanBrojZapisa = $KategorijaIgreObject->BrojZapisa;

        $KolekcijaZapisaStudenata = $DrustvenaIgraObject->Kolekcija;
        $UkupanBrojZapisaStudenata = $DrustvenaIgraObject->BrojZapisa;

        if ($UkupanBrojZapisaStudenata > 0) {
            $row = 0;
            $StariSifraIgre = $DrustvenaIgraObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 0);
            $StariNaziv = $DrustvenaIgraObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 1);
            $StariProizvodjac = $DrustvenaIgraObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 2);
            $StaraOznakaKategorije = $DrustvenaIgraObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 3);
            $StariNazivFajlaSlike = $DrustvenaIgraObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 4);
        } else {
            $StariSifraIgre = "";
            $StariNaziv = "";
            $StariProizvodjac = "";
            $StaraOznakaKategorije = "";
            $StariNazivFajlaSlike = "";
        }

        include 'pogledi/DrustvenaIgraIzmeniForm.php';

        $DrustveneIgreController->ZatvoriKonekciju();
        break;

    case 'obrisiDrustvenuIgru':
        proveriSesiju();
        require 'kontroler/akcije/DrustvenaIgraObrisi.php';
        break;

    case 'novaNabavka':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();
        $rezultatDrustveneIgre = $NabavkeController->DajDrustveneIgreZaNabavku();

        $optionsDrustveneIgre = "<option value=\"\">izaberite igru...</option>";

        while ($igra = mysqli_fetch_assoc($rezultatDrustveneIgre)) {
            $optionsDrustveneIgre .= "<option value='" . htmlspecialchars($igra['SifraIgre'], ENT_QUOTES, 'UTF-8') . "'>"
                . htmlspecialchars($igra['Naziv'] . " - " . $igra['SifraIgre'], ENT_QUOTES, 'UTF-8')
                . "</option>";
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

        $listaNabavaka = $NabavkeController->DajNabavkeSaStavkama($rezultatNabavke);

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

    case 'stampaNabavke':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();

        $filterBrojNaloga = "";
        $filterDatumNabavke = "";
        $filterDobavljac = "";
        $filtrirano = false;

        if (isset($_GET['filtriraj']) && !isset($_GET['svi'])) {
            $filtrirano = true;
            $filterBrojNaloga = isset($_GET['filterBrojNaloga']) ? trim($_GET['filterBrojNaloga']) : "";
            $filterDatumNabavke = isset($_GET['filterDatumNabavke']) ? trim($_GET['filterDatumNabavke']) : "";
            $filterDobavljac = isset($_GET['filterDobavljac']) ? trim($_GET['filterDobavljac']) : "";
            $rezultatNabavke = $NabavkeController->DajNabavkePoFilteru($filterBrojNaloga, $filterDatumNabavke, $filterDobavljac);
        } else {
            $rezultatNabavke = $NabavkeController->DajSveNabavke();
        }

        $listaNabavaka = $NabavkeController->DajNabavkeSaStavkama($rezultatNabavke);

        include 'pogledi/NabavkeStampa.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'parametarskaStampaNabavke':
        proveriSesiju();
        include 'pogledi/NabavkaParametarskaStampa.php';
        break;

    case 'stampaJednogNaloga':
        proveriSesiju();

        $BrojNalogaFilter = isset($_POST['BrojNalogaFilter']) ? trim($_POST['BrojNalogaFilter']) : null;

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();
        $nabavka = ($BrojNalogaFilter != null && $BrojNalogaFilter != "") ? $NabavkeController->DajNabavkuPoBrojuNaloga($BrojNalogaFilter) : null;
        $rezultatStavke = ($nabavka != null) ? $NabavkeController->DajStavkeNabavke($nabavka['IDNabavke']) : null;

        include 'pogledi/StampaPodatakaONalogu.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'nabavkaIzmeniForm':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();

        $IDNabavke = isset($_POST['IDNabavke']) ? trim($_POST['IDNabavke']) : (isset($_GET['id']) ? trim($_GET['id']) : "");
        $nabavka = $IDNabavke != "" ? $NabavkeController->DajNabavkuPoID($IDNabavke) : null;
        $rezultatStavke = ($nabavka != null) ? $NabavkeController->DajStavkeNabavke($IDNabavke) : null;

        $listaIgara = array();
        $rezultatDrustveneIgre = $NabavkeController->DajDrustveneIgreZaNabavku();
        while ($igra = mysqli_fetch_assoc($rezultatDrustveneIgre)) {
            $listaIgara[] = $igra;
        }

        $optionsDrustveneIgre = "<option value=\"\">izaberite igru...</option>";

        foreach ($listaIgara as $igra) {
            $optionsDrustveneIgre .= "<option value='" . htmlspecialchars($igra['SifraIgre'], ENT_QUOTES, 'UTF-8') . "'>"
                . htmlspecialchars($igra['Naziv'] . " - " . $igra['SifraIgre'], ENT_QUOTES, 'UTF-8')
                . "</option>";
        }

        include 'pogledi/NabavkaIzmeniForm.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    default:
        if (!isset($_SESSION["korisnik"])) {
            header('Location:Ruter.php?stranica=prijava');
            exit();
        }
        header('Location:Ruter.php?stranica=welcome');
        exit();
        break;
}
?>