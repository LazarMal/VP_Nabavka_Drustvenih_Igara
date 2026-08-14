<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<table style="width:100%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">

<tr>
<td style="width:3%;"></td>
<td align="left">
<b><font face="Trebuchet MS" color="black" size="3px">IZMENA REKLAMACIJE DRUŠTVENIH IGARA</font></b><br/><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<?php
if ($reklamacija == null) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Reklamacija nije pronađena.</font>";
    echo "<br/><br/><a href=\"Ruter.php?stranica=reklamacije\"><input type=\"button\" value=\"POVRATAK NA LISTU\" /></a>";
} else {

function napraviOpcijeIgara($listaIgara, $selectedSifra = "")
{
    $options = "<option value=\"\">izaberite igru...</option>";

    foreach ($listaIgara as $igra) {
        $sel = ($igra['SifraIgre'] == $selectedSifra) ? " selected" : "";
        $options .= "<option value='" . htmlspecialchars($igra['SifraIgre'], ENT_QUOTES, 'UTF-8') . "'" . $sel . ">"
            . htmlspecialchars($igra['Naziv']) . " - " . htmlspecialchars($igra['SifraIgre'])
            . "</option>";
    }

    return $options;
}
?>

<form action="kontroler/akcije/reklamacijaIzmeni.php" method="POST" onsubmit="return proveriReklamaciju();">
<input type="hidden" name="IDReklamacije" id="IDReklamacije" value="<?php echo htmlspecialchars($reklamacija['IDReklamacije']); ?>">

<table style="width:90%;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="2" align="left">
<b>PODACI O REKLAMACIJI</b>
</td>
</tr>

<tr>
<td align="right"><b>Broj reklamacije&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="brojReklamacije" id="brojReklamacije" maxlength="50" required value="<?php echo htmlspecialchars($reklamacija['BrojReklamacije']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Datum reklamacije&nbsp;&nbsp;</b></td>
<td align="left"><input type="date" name="datumReklamacije" id="datumReklamacije" required value="<?php echo htmlspecialchars($reklamacija['DatumReklamacije']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Dobavljač&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="dobavljac" id="dobavljac" maxlength="100" required value="<?php echo htmlspecialchars($reklamacija['Dobavljac']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Napomena&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="napomena" id="napomena" size="50" maxlength="255" required value="<?php echo htmlspecialchars($reklamacija['Napomena']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Reklamaciju evidentirao&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" value="<?php echo htmlspecialchars($reklamacija['ReklamacijuEvidentirao']); ?>" readonly style="background-color:#EEEEEE;"></td>
</tr>

<tr>
<td align="right"><b>Datum evidentiranja&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" value="<?php echo htmlspecialchars(isset($reklamacija['DatumEvidentiranja']) ? $reklamacija['DatumEvidentiranja'] : ''); ?>" readonly style="background-color:#EEEEEE;"></td>
</tr>
</table>

<br/>

<table id="stavkeTabela" style="width:90%; margin-left:auto; margin-right:auto;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="6" align="left"><b>STAVKE REKLAMACIJE</b></td>
</tr>
<tr>
<td><b>Društvena igra</b></td>
<td><b>Količina</b></td>
<td><b>Cena po komadu</b></td>
<td><b>Razlog reklamacije</b></td>
<td><b>Ukupno</b></td>
<td><b>Akcija</b></td>
</tr>

<?php
if ($rezultatStavke != null && mysqli_num_rows($rezultatStavke) > 0) {
    while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
        $ukupnoRed = $stavka['Kolicina'] * $stavka['Cena'];
        echo "<tr class=\"stavkaRed\">";
        echo "<td>";
        echo "<input type=\"hidden\" name=\"idStavkeReklamacije[]\" value=\"" . htmlspecialchars($stavka['IDStavkeReklamacije']) . "\">";
        echo "<select name=\"sifraIgre[]\" class=\"igraSelect\" required style=\"width:200px;\">";
        echo napraviOpcijeIgara($listaIgara, $stavka['SifraIgre']);
        echo "</select>";
        echo "</td>";
        echo "<td><input type=\"number\" name=\"kolicina[]\" class=\"kolicinaInput\" min=\"1\" step=\"1\" required style=\"width:70px;\" value=\"" . htmlspecialchars($stavka['Kolicina']) . "\"></td>";
        echo "<td><input type=\"number\" name=\"cena[]\" class=\"cenaInput\" min=\"0.01\" step=\"0.01\" required style=\"width:90px;\" value=\"" . htmlspecialchars($stavka['Cena']) . "\"></td>";
        echo "<td><input type=\"text\" name=\"razlogReklamacije[]\" class=\"razlogInput\" maxlength=\"255\" required style=\"width:180px;\" value=\"" . htmlspecialchars($stavka['RazlogReklamacije']) . "\"></td>";
        echo "<td><input type=\"text\" class=\"ukupnoInput\" readonly style=\"width:90px;\" value=\"" . number_format($ukupnoRed, 2, '.', '') . "\"></td>";
        echo "<td><button type=\"button\" onclick=\"obrisiStavku(this)\">OBRISI</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr class=\"stavkaRed\">";
    echo "<td>";
    echo "<input type=\"hidden\" name=\"idStavkeReklamacije[]\" value=\"\">";
    echo "<select name=\"sifraIgre[]\" class=\"igraSelect\" required style=\"width:200px;\">";
    echo napraviOpcijeIgara($listaIgara);
    echo "</select>";
    echo "</td>";
    echo "<td><input type=\"number\" name=\"kolicina[]\" class=\"kolicinaInput\" min=\"1\" step=\"1\" required style=\"width:70px;\"></td>";
    echo "<td><input type=\"number\" name=\"cena[]\" class=\"cenaInput\" min=\"0.01\" step=\"0.01\" required style=\"width:90px;\"></td>";
    echo "<td><input type=\"text\" name=\"razlogReklamacije[]\" class=\"razlogInput\" maxlength=\"255\" required style=\"width:180px;\"></td>";
    echo "<td><input type=\"text\" class=\"ukupnoInput\" readonly style=\"width:90px;\"></td>";
    echo "<td><button type=\"button\" onclick=\"obrisiStavku(this)\">OBRISI</button></td>";
    echo "</tr>";
}
?>
</table>

<br/>

<table style="width:90%;" align="center">
<tr>
<td align="center">
<button type="button" onclick="dodajStavku()">DODAJ JOS JEDNU STAVKU</button>
<br/><br/>
<input type="submit" value="SACUVAJ IZMENE">
<br/><br/>
<a href="Ruter.php?stranica=reklamacije"><input type="button" value="ODUSTANI"></a>
</td>
</tr>
</table>

</form>

<script>
let optionsIgre = `<?php echo str_replace("`", "\`", $optionsDrustveneIgre); ?>`;
let brojReklamacijeZauzet = false;

function postaviDogadjajeZaRed(red) {
    let kolicinaInput = red.querySelector(".kolicinaInput");
    let cenaInput = red.querySelector(".cenaInput");

    kolicinaInput.addEventListener("input", function() {
        izracunajUkupno(red);
    });

    cenaInput.addEventListener("input", function() {
        izracunajUkupno(red);
    });

    izracunajUkupno(red);
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
            <input type="hidden" name="idStavkeReklamacije[]" value="">
            <select name="sifraIgre[]" class="igraSelect" required style="width:200px;">
                ${optionsIgre}
            </select>
        </td>
        <td>
            <input type="number" name="kolicina[]" class="kolicinaInput" min="1" step="1" required style="width:70px;">
        </td>
        <td>
            <input type="number" name="cena[]" class="cenaInput" min="0.01" step="0.01" required style="width:90px;">
        </td>
        <td>
            <input type="text" name="razlogReklamacije[]" class="razlogInput" maxlength="255" required style="width:180px;">
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
        alert("Reklamacija mora imati bar jednu stavku.");
        return;
    }

    dugme.closest("tr").remove();
}

function proveriReklamaciju() {
    let brojReklamacije = document.getElementById("brojReklamacije").value.trim();
    let datum = document.getElementById("datumReklamacije").value;
    let dobavljac = document.getElementById("dobavljac").value.trim();
    let napomenaEl = document.getElementById("napomena");
    let napomena = napomenaEl ? napomenaEl.value.trim() : "";
    let redovi = document.querySelectorAll(".stavkaRed");

    if (brojReklamacije == "" || datum == "" || dobavljac == "") {
        alert("Morate popuniti sva obavezna polja o reklamaciji.");
        return false;
    }

    if (brojReklamacije.length > 50) {
        alert("Broj reklamacije ne sme biti duži od 50 karaktera.");
        return false;
    }

    if (dobavljac.length > 100) {
        alert("Dobavljač ne sme biti duži od 100 karaktera.");
        return false;
    }

    if (napomena === "") {
        alert("Napomena je obavezna.");
        return false;
    }

    if (napomena.length > 255) {
        alert("Napomena ne sme biti duža od 255 karaktera.");
        return false;
    }

    if (!/^\d{4}-\d{2}-\d{2}$/.test(datum)) {
        alert("Datum reklamacije nije ispravan.");
        return false;
    }

    if (redovi.length == 0) {
        alert("Reklamacija mora imati bar jednu stavku.");
        return false;
    }

    for (let i = 0; i < redovi.length; i++) {
        let igra = redovi[i].querySelector(".igraSelect").value;
        let kolicinaVal = redovi[i].querySelector(".kolicinaInput").value;
        let cenaVal = redovi[i].querySelector(".cenaInput").value;
        let razlogVal = redovi[i].querySelector(".razlogInput").value.trim();
        let kolicinaNum = Number(kolicinaVal);
        let cena = parseFloat(cenaVal);

        if (igra == "") {
            alert("Morate izabrati društvenu igru u svakoj stavci.");
            return false;
        }

        if (kolicinaVal === "" || !Number.isInteger(kolicinaNum) || kolicinaNum <= 0) {
            alert("Količina mora biti pozitivan ceo broj veći od 0.");
            return false;
        }

        if (cenaVal === "" || isNaN(cena) || cena <= 0) {
            alert("Cena mora biti pozitivna decimalna vrednost veća od 0.");
            return false;
        }

        if (razlogVal === "") {
            alert("Razlog reklamacije je obavezan u svakoj stavci.");
            return false;
        }

        if (razlogVal.length > 255) {
            alert("Razlog reklamacije ne sme biti duži od 255 karaktera.");
            return false;
        }
    }

    if (brojReklamacijeZauzet) {
        alert("Broj reklamacije je već zauzet.");
        return false;
    }

    return true;
}

function proveriBrojReklamacije() {
    let brojReklamacije = document.getElementById("brojReklamacije").value.trim();
    let idReklamacije = document.getElementById("IDReklamacije").value;
    brojReklamacijeZauzet = false;
    if (brojReklamacije === "") return;

    let url = "api/router.php?akcija=proveraJedinstvenosti&tip=brojReklamacije&vrednost=" + encodeURIComponent(brojReklamacije);
    if (idReklamacije !== "") {
        url += "&izuzmiId=" + encodeURIComponent(idReklamacije);
    }

    fetch(url)
        .then(r => r.json())
        .then(data => {
            brojReklamacijeZauzet = data.postoji === true;
            if (brojReklamacijeZauzet) {
                alert("Broj reklamacije je već zauzet.");
            }
        });
}

document.getElementById("brojReklamacije").addEventListener("blur", proveriBrojReklamacije);

document.querySelectorAll(".stavkaRed").forEach(function(red) {
    postaviDogadjajeZaRed(red);
});
</script>

<?php } ?>

</td>
<td style="width:3%;"></td>
</tr>

</table>

</td>
<td style="width:5%;"></td>
</tr>
</table>
