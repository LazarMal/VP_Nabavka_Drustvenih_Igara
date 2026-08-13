<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="sr-RS" xml:lang="sr-RS">
<head>
<meta charset="UTF-8">
<title>Novi nalog za nabavku drustvenih igara</title>
<?php include 'css/stil.php';?>
</head>

<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nalogEvidentirao = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : "";
?>

<table class="no-spacing" style="width:100%; padding:0; border-spacing:0;" align="center" cellspacing="0" cellpadding="0" border="0">

<?php include 'delovi/zaglavljewelcome.php';?>

<tr style="padding:0px;">
<td style="width:10%;"></td>

<td align="center" valign="middle" style="width:80%; padding:0"> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#003366">
<tr>
<td style="width:1%;"></td>

<td style="width:15%;padding:0" valign="top">
<?php include 'delovi/menilevoadmin.php';?>
</td>

<td style="width:1%;"></td>

<td style="width:80%;padding:0" valign="top">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<table style="width:100%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">

<tr>
<td style="width:3%;"></td>
<td align="left">
<b><font face="Trebuchet MS" color="black" size="3px">NOVI NALOG ZA NABAVKU DRUSTVENIH IGAR</font></b><br/><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<form action="kontroler/akcije/nabavkaSnimi.php" method="POST" onsubmit="return proveriNabavku();">

<table style="width:90%;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="2" align="left">
<b>PODACI O NABAVCI</b>
</td>
</tr>

<tr>
<td align="right"><b>Broj naloga&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="brojNaloga" id="brojNaloga" maxlength="50" required placeholder="Unesite broj naloga"></td>
</tr>

<tr>
<td align="right"><b>Datum nabavke&nbsp;&nbsp;</b></td>
<td align="left"><input type="date" name="datumNabavke" id="datumNabavke" required></td>
</tr>

<tr>
<td align="right"><b>Dobavljac&nbsp;&nbsp;</b></td>
<td align="left">
<input type="text" name="dobavljac" id="dobavljac" maxlength="100" required placeholder="Unesite dobavljaca">
</td>
</tr>

<tr>
<td align="right"><b>Napomena&nbsp;&nbsp;</b></td>
<td align="left">
<input type="text" name="napomena" id="napomena" size="50" maxlength="255" value="">
</td>
</tr>

<tr>
<td align="right"><b>Nalog evidentirao&nbsp;&nbsp;</b></td>
<td align="left">
<input type="text" value="<?php echo htmlspecialchars($nalogEvidentirao); ?>" readonly style="background-color:#EEEEEE;">
</td>
</tr>
</table>

<br/>

<table id="stavkeTabela" style="width:90%; margin-left:auto; margin-right:auto;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">

<tr>
<td colspan="5" align="left">
<b>STAVKE NALOGA</b>
</td>
</tr>

<tr>
<td><b>Drustvena igra</b></td>
<td><b>Kolicina</b></td>
<td><b>Cena</b></td>
<td><b>Ukupno</b></td>
<td><b>Akcija</b></td>
</tr>

<tr class="stavkaRed">
<td>
<select name="sifraIgre[]" class="igraSelect" required style="width:280px;">
<?php echo $optionsKnjige; ?>
</select>
</td>

<td>
<input type="number" name="kolicina[]" class="kolicinaInput" min="1" step="1" required style="width:90px;">
</td>

<td>
<input type="number" name="cena[]" class="cenaInput" min="0.01" step="0.01" required style="width:90px;">
</td>

<td>
<input type="text" class="ukupnoInput" readonly style="width:90px;">
</td>

<td>
<button type="button" onclick="obrisiStavku(this)">OBRISI</button>
</td>
</tr>

</table>

<br/>

<table style="width:90%;" align="center">
<tr>
<td align="center">
<button type="button" onclick="dodajStavku()">DODAJ JOS JEDNU STAVKU</button>
<br/><br/>
<input type="submit" value="SACUVAJ NALOG">
</td>
</tr>
</table>

</form>

</td>
<td style="width:3%;"></td>
</tr>

</table>

</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center"><font color="#D8E7F4" size="1px">.</font></td>
<td style="width:3%;"></td>
</tr>

</table>
</td>

<td style="width:5%;"></td>
</tr>
</table>

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />

</td>

<td style="width:1%;"></td>
</tr>
</table>

</td>

<td style="width:10%;"></td>
</tr>

<?php include 'delovi/footer.php';?>

</table>

<script>
let optionsIgre = `<?php echo str_replace("`", "\`", $optionsKnjige); ?>`;

function postaviDogadjajeZaRed(red) {
    let igraSelect = red.querySelector(".igraSelect");
    let kolicinaInput = red.querySelector(".kolicinaInput");
    let cenaInput = red.querySelector(".cenaInput");

    igraSelect.addEventListener("change", function() {
        let selectedOption = this.options[this.selectedIndex];
        let cena = selectedOption.getAttribute("data-cena");

        if (cena !== null && cena !== "") {
            cenaInput.value = cena;
        }
        izracunajUkupno(red);
    });

    kolicinaInput.addEventListener("input", function() {
        izracunajUkupno(red);
    });

    cenaInput.addEventListener("input", function() {
        izracunajUkupno(red);
    });
}

function izracunajUkupno(red) {
    let kolicina = parseFloat(red.querySelector(".kolicinaInput").value);
    let cena = parseFloat(red.querySelector(".cenaInput").value);
    let ukupnoInput = red.querySelector(".ukupnoInput");

    if (!isNaN(kolicina) && !isNaN(cena)) {
        ukupnoInput.value = (kolicina * cena).toFixed(2);
    } else {
        ukupnoInput.value = "";
    }
}

function dodajStavku() {
    let tabela = document.getElementById("stavkeTabela");

    let noviRed = document.createElement("tr");
    noviRed.className = "stavkaRed";

    noviRed.innerHTML = `
        <td>
            <select name="sifraIgre[]" class="igraSelect" required style="width:280px;">
                ${optionsIgre}
            </select>
        </td>
        <td>
            <input type="number" name="kolicina[]" class="kolicinaInput" min="1" step="1" required style="width:90px;">
        </td>
        <td>
            <input type="number" name="cena[]" class="cenaInput" min="0.01" step="0.01" required style="width:90px;">
        </td>
        <td>
            <input type="text" class="ukupnoInput" readonly style="width:90px;">
        </td>
        <td>
            <button type="button" onclick="obrisiStavku(this)">OBRISI</button>
        </td>
    `;

    tabela.appendChild(noviRed);
    postaviDogadjajeZaRed(noviRed);
}

function obrisiStavku(dugme) {
    let redovi = document.querySelectorAll(".stavkaRed");

    if (redovi.length <= 1) {
        alert("Nalog mora imati bar jednu stavku.");
        return;
    }

    dugme.closest("tr").remove();
}

function proveriNabavku() {
    let brojNaloga = document.getElementById("brojNaloga").value.trim();
    let datum = document.getElementById("datumNabavke").value;
    let dobavljac = document.getElementById("dobavljac").value.trim();
    let napomenaEl = document.getElementById("napomena");
    let napomena = napomenaEl ? napomenaEl.value.trim() : "";
    let redovi = document.querySelectorAll(".stavkaRed");

    if (brojNaloga == "" || datum == "" || dobavljac == "") {
        alert("Morate popuniti sva obavezna polja o nalogu.");
        return false;
    }

    if (brojNaloga.length > 50) {
        alert("Broj naloga ne sme biti duži od 50 karaktera.");
        return false;
    }

    if (dobavljac.length > 100) {
        alert("Dobavljač ne sme biti duži od 100 karaktera.");
        return false;
    }

    if (napomena.length > 255) {
        alert("Napomena ne sme biti duža od 255 karaktera.");
        return false;
    }

    if (!/^\d{4}-\d{2}-\d{2}$/.test(datum)) {
        alert("Datum nabavke nije ispravan.");
        return false;
    }

    if (redovi.length == 0) {
        alert("Nalog mora imati bar jednu stavku.");
        return false;
    }

    for (let i = 0; i < redovi.length; i++) {
        let igra = redovi[i].querySelector(".igraSelect").value;
        let kolicinaVal = redovi[i].querySelector(".kolicinaInput").value;
        let cenaVal = redovi[i].querySelector(".cenaInput").value;
        let kolicina = parseInt(kolicinaVal, 10);
        let cena = parseFloat(cenaVal);

        if (igra == "") {
            alert("Morate izabrati društvenu igru u svakoj stavci.");
            return false;
        }

        if (kolicinaVal === "" || isNaN(kolicina) || String(kolicina) !== String(parseInt(kolicinaVal, 10)) || kolicina <= 0) {
            alert("Količina mora biti pozitivan ceo broj veći od 0.");
            return false;
        }

        if (cenaVal === "" || isNaN(cena) || cena <= 0) {
            alert("Cena mora biti pozitivna decimalna vrednost veća od 0.");
            return false;
        }
    }

    return true;
}

let prviRed = document.querySelector(".stavkaRed");
postaviDogadjajeZaRed(prviRed);
</script>

</body>
</html>
