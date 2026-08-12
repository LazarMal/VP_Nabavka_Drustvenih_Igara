<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$stranica = isset($_GET['stranica']) ? $_GET['stranica'] : 'index';

function proveriSesiju()
{
    $korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

    if (!isset($korisnik)) {
        header('Location:ruter.php?stranica=index');
        exit();
    }
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

        $StariISBNZaIzmenu = isset($_POST['isbn']) ? $_POST['isbn'] : null;

        require_once 'kontroler/stranice/KnjigeController.php';

        $KnjigeController = new KnjigeController();

        $ZanrObject = $KnjigeController->DajSveZanrove();
        $KnjigaObject = $KnjigeController->DajKnjiguPoISBN($StariISBNZaIzmenu);

        $KolekcijaZapisa = $ZanrObject->Kolekcija;
        $UkupanBrojZapisa = $ZanrObject->BrojZapisa;

        $KolekcijaZapisaStudenata = $KnjigaObject->Kolekcija;
        $UkupanBrojZapisaStudenata = $KnjigaObject->BrojZapisa;

        if ($UkupanBrojZapisaStudenata > 0) {
            $row = 0;
            $StariISBN = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 0);
            $StariNaziv = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 1);
            $StariAutor = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 2);
            $StaraOznakaZanra = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 3);
            $StariNazivFajlaSlike = $KnjigaObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 4);
        } else {
            $StariISBN = "";
            $StariNaziv = "";
            $StariAutor = "";
            $StaraOznakaZanra = "";
            $StariNazivFajlaSlike = "";
        }

        include 'pogledi/KnjigaIzmeniForm.php';

        $KnjigeController->ZatvoriKonekciju();
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

        $optionsKnjige = "<option value=\"\">изаберите књигу...</option>";

        while ($knjiga = mysqli_fetch_assoc($rezultatKnjige)) {
            $optionsKnjige .= "<option value='".$knjiga['ISBN']."' data-cena='".$knjiga['Cena']."'>
                    ".$knjiga['Naziv']." - ".$knjiga['ISBN']."
                  </option>";
        }

        include 'pogledi/NovaNabavka.php';

        $NabavkeController->ZatvoriKonekciju();
        break;

    case 'nabavke':
        proveriSesiju();

        require_once 'kontroler/stranice/NabavkeController.php';

        $NabavkeController = new NabavkeController();
        $rezultatNabavke = $NabavkeController->DajSveNabavke();

        include 'pogledi/NabavkeLista.php';

        $NabavkeController->ZatvoriKonekciju();
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