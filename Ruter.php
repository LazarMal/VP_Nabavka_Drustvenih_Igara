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

    case 'novaReklamacija':
        proveriSesiju();

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();
        $rezultatDrustveneIgre = $ReklamacijeController->DajDrustveneIgreZaReklamaciju();

        $optionsDrustveneIgre = "<option value=\"\">izaberite igru...</option>";

        while ($igra = mysqli_fetch_assoc($rezultatDrustveneIgre)) {
            $optionsDrustveneIgre .= "<option value='" . htmlspecialchars($igra['SifraIgre'], ENT_QUOTES, 'UTF-8') . "'>"
                . htmlspecialchars($igra['Naziv'] . " - " . $igra['SifraIgre'], ENT_QUOTES, 'UTF-8')
                . "</option>";
        }

        include 'pogledi/NovaReklamacija.php';

        $ReklamacijeController->ZatvoriKonekciju();
        break;

    case 'reklamacije':
        proveriSesiju();

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();

        $filterBrojReklamacije = "";
        $filterDatumReklamacije = "";
        $filterDobavljac = "";

        if (isset($_GET['filtriraj']) && !isset($_GET['svi'])) {
            $filterBrojReklamacije = isset($_GET['filterBrojReklamacije']) ? trim($_GET['filterBrojReklamacije']) : "";
            $filterDatumReklamacije = isset($_GET['filterDatumReklamacije']) ? trim($_GET['filterDatumReklamacije']) : "";
            $filterDobavljac = isset($_GET['filterDobavljac']) ? trim($_GET['filterDobavljac']) : "";
            $rezultatReklamacije = $ReklamacijeController->DajReklamacijePoFilteru($filterBrojReklamacije, $filterDatumReklamacije, $filterDobavljac);
        } else {
            $rezultatReklamacije = $ReklamacijeController->DajSveReklamacije();
        }

        $listaReklamacija = $ReklamacijeController->DajReklamacijeSaStavkama($rezultatReklamacije);

        include 'pogledi/ReklamacijeLista.php';

        $ReklamacijeController->ZatvoriKonekciju();
        break;

    case 'reklamacijaDetalj':
        proveriSesiju();

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();

        $IDReklamacije = isset($_GET['id']) ? trim($_GET['id']) : "";
        $reklamacija = $IDReklamacije != "" ? $ReklamacijeController->DajReklamacijuPoID($IDReklamacije) : null;
        $rezultatStavke = ($reklamacija != null) ? $ReklamacijeController->DajStavkeReklamacije($IDReklamacije) : null;

        include 'pogledi/ReklamacijaDetalj.php';

        $ReklamacijeController->ZatvoriKonekciju();
        break;

    case 'obrisiReklamaciju':
        proveriSesiju();
        require 'kontroler/akcije/reklamacijaObrisi.php';
        break;

    case 'stampaReklamacija':
        proveriSesiju();

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();

        $filterBrojReklamacije = "";
        $filterDatumReklamacije = "";
        $filterDobavljac = "";
        $filtrirano = false;

        if (isset($_GET['filtriraj']) && !isset($_GET['svi'])) {
            $filtrirano = true;
            $filterBrojReklamacije = isset($_GET['filterBrojReklamacije']) ? trim($_GET['filterBrojReklamacije']) : "";
            $filterDatumReklamacije = isset($_GET['filterDatumReklamacije']) ? trim($_GET['filterDatumReklamacije']) : "";
            $filterDobavljac = isset($_GET['filterDobavljac']) ? trim($_GET['filterDobavljac']) : "";
            $rezultatReklamacije = $ReklamacijeController->DajReklamacijePoFilteru($filterBrojReklamacije, $filterDatumReklamacije, $filterDobavljac);
        } else {
            $rezultatReklamacije = $ReklamacijeController->DajSveReklamacije();
        }

        $listaReklamacija = $ReklamacijeController->DajReklamacijeSaStavkama($rezultatReklamacije);

        include 'pogledi/ReklamacijeStampa.php';

        $ReklamacijeController->ZatvoriKonekciju();
        break;

    case 'parametarskaStampaReklamacija':
        proveriSesiju();
        include 'pogledi/ReklamacijaParametarskaStampa.php';
        break;

    case 'stampaJedneReklamacije':
        proveriSesiju();

        $brojReklamacije = isset($_POST['brojReklamacije']) ? trim($_POST['brojReklamacije']) : "";

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();
        $reklamacija = ($brojReklamacije != "") ? $ReklamacijeController->DajReklamacijuPoBrojuReklamacije($brojReklamacije) : null;
        $rezultatStavke = ($reklamacija != null) ? $ReklamacijeController->DajStavkeReklamacije($reklamacija['IDReklamacije']) : null;

        include 'pogledi/StampaPodatakaOReklamaciji.php';

        $ReklamacijeController->ZatvoriKonekciju();
        break;

    case 'reklamacijaIzmeniForm':
        proveriSesiju();

        require_once 'kontroler/stranice/ReklamacijeController.php';

        $ReklamacijeController = new ReklamacijeController();

        $IDReklamacije = isset($_POST['IDReklamacije']) ? trim($_POST['IDReklamacije']) : (isset($_GET['id']) ? trim($_GET['id']) : "");
        $reklamacija = $IDReklamacije != "" ? $ReklamacijeController->DajReklamacijuPoID($IDReklamacije) : null;
        $rezultatStavke = ($reklamacija != null) ? $ReklamacijeController->DajStavkeReklamacije($IDReklamacije) : null;

        $listaIgara = array();
        $rezultatDrustveneIgre = $ReklamacijeController->DajDrustveneIgreZaReklamaciju();
        while ($igra = mysqli_fetch_assoc($rezultatDrustveneIgre)) {
            $listaIgara[] = $igra;
        }

        $optionsDrustveneIgre = "<option value=\"\">izaberite igru...</option>";

        foreach ($listaIgara as $igra) {
            $optionsDrustveneIgre .= "<option value='" . htmlspecialchars($igra['SifraIgre'], ENT_QUOTES, 'UTF-8') . "'>"
                . htmlspecialchars($igra['Naziv'] . " - " . $igra['SifraIgre'], ENT_QUOTES, 'UTF-8')
                . "</option>";
        }

        include 'pogledi/ReklamacijaIzmeniForm.php';

        $ReklamacijeController->ZatvoriKonekciju();
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