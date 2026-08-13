<meta charset="UTF-8">

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
<b><font face="Trebuchet MS" color="black" size="3px">ИЗМЕНА НАЛОГА ЗА НАБАВКУ ДРУШТВЕНИХ ИГАРА</font></b><br/><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<?php
if ($nabavka == null) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Nalog nije pronađen.</font>";
    echo "<br/><br/><a href=\"Ruter.php?stranica=nabavke\"><input type=\"button\" value=\"ПОВРАТАК NA LISTU\" /></a>";
} else {

function napraviOpcijeIgara($listaIgara, $selectedSifra = "")
{
    $options = "<option value=\"\">izaberite igru...</option>";

    foreach ($listaIgara as $igra) {
        $sel = ($igra['SifraIgre'] == $selectedSifra) ? " selected" : "";
        $options .= "<option value='" . htmlspecialchars($igra['SifraIgre']) . "' data-cena='" . htmlspecialchars($igra['Cena']) . "'" . $sel . ">"
            . htmlspecialchars($igra['Naziv']) . " - " . htmlspecialchars($igra['SifraIgre'])
            . "</option>";
    }

    return $options;
}
?>

<form action="kontroler/akcije/nabavkaIzmeni.php" method="POST" onsubmit="return proveriNabavku();">
<input type="hidden" name="IDNabavke" value="<?php echo htmlspecialchars($nabavka['IDNabavke']); ?>">

<table style="width:90%;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="2" align="left">
<b>ПОДАЦИ O NALOGU</b>
</td>
</tr>

<tr>
<td align="right"><b>Броj naloga&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="brojNaloga" id="brojNaloga" maxlength="50" required value="<?php echo htmlspecialchars($nabavka['BrojNaloga']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Датum nabavke&nbsp;&nbsp;</b></td>
<td align="left"><input type="date" name="datumNabavke" id="datumNabavke" required value="<?php echo htmlspecialchars($nabavka['DatumNabavke']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Добављач&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="dobavljac" id="dobavljac" maxlength="100" required value="<?php echo htmlspecialchars($nabavka['Dobavljac']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Напomena&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" name="napomena" id="napomena" size="50" maxlength="255" value="<?php echo htmlspecialchars($nabavka['Napomena']); ?>"></td>
</tr>

<tr>
<td align="right"><b>Nalog evidentirao&nbsp;&nbsp;</b></td>
<td align="left"><input type="text" value="<?php echo htmlspecialchars($nabavka['NalogEvidentirao']); ?>" readonly style="background-color:#EEEEEE;"></td>
</tr>
</table>

<br/>

<table id="stavkeTabela" style="width:90%; margin-left:auto; margin-right:auto;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="6" align="left"><b>STAVKE NALOGA</b></td>
</tr>
<tr>
<td><b>Друštvena igra</b></td>
<td><b>Količina</b></td>
<td><b>Cena</b></td>
<td><b>Ukupno</b></td>
<td><b>Akcija</b></td>
</tr>

<?php
if ($rezultatStavke != null && mysqli_num_rows($rezultatStavke) > 0) {
    while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
        $ukupnoRed = $stavka['Kolicina'] * $stavka['Cena'];
        echo "<tr class=\"stavkaRed\">";
        echo "<td>";
        echo "<input type=\"hidden\" name=\"idStavke[]\" value=\"" . htmlspecialchars($stavka['IDStavke']) . "\">";
        echo "<select name=\"sifraIgre[]\" class=\"igraSelect\" required style=\"width:280px;\">";
        echo napraviOpcijeIgara($listaIgara, $stavka['SifraIgre']);
        echo "</select>";
        echo "</td>";
        echo "<td><input type=\"number\" name=\"kolicina[]\" class=\"kolicinaInput\" min=\"1\" step=\"1\" required style=\"width:90px;\" value=\"" . htmlspecialchars($stavka['Kolicina']) . "\"></td>";
        echo "<td><input type=\"number\" name=\"cena[]\" class=\"cenaInput\" min=\"0.01\" step=\"0.01\" required style=\"width:90px;\" value=\"" . htmlspecialchars($stavka['Cena']) . "\"></td>";
        echo "<td><input type=\"text\" class=\"ukupnoInput\" readonly style=\"width:90px;\" value=\"" . number_format($ukupnoRed, 2, '.', '') . "\"></td>";
        echo "<td><button type=\"button\" onclick=\"obrisiStavku(this)\">ОБРИШИ</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr class=\"stavkaRed\">";
    echo "<td>";
    echo "<input type=\"hidden\" name=\"idStavke[]\" value=\"\">";
    echo "<select name=\"sifraIgre[]\" class=\"igraSelect\" required style=\"width:280px;\">";
    echo napraviOpcijeIgara($listaIgara);
    echo "</select>";
    echo "</td>";
    echo "<td><input type=\"number\" name=\"kolicina[]\" class=\"kolicinaInput\" min=\"1\" step=\"1\" required style=\"width:90px;\"></td>";
    echo "<td><input type=\"number\" name=\"cena[]\" class=\"cenaInput\" min=\"0.01\" step=\"0.01\" required style=\"width:90px;\"></td>";
    echo "<td><input type=\"text\" class=\"ukupnoInput\" readonly style=\"width:90px;\"></td>";
    echo "<td><button type=\"button\" onclick=\"obrisiStavku(this)\">ОБРИШИ</button></td>";
    echo "</tr>";
}
?>
</table>

<br/>

<table style="width:90%;" align="center">
<tr>
<td align="center">
<button type="button" onclick="dodajStavku()">DODAJ JOŠ JEDNU STAVKU</button>
<br/><br/>
<input type="submit" value="SAČUVAJ IZMENE">
<br/><br/>
<a href="Ruter.php?stranica=nabavke"><input type="button" value="ОДУСТАНИ"></a>
</td>
</tr>
</table>

</form>

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
            <input type="hidden" name="idStavke[]" value="">
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
            <button type="button" onclick="obrisiStavku(this)">ОБРИШИ</button>
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
    let redovi = document.querySelectorAll(".stavkaRed");

    if (brojNaloga == "" || datum == "" || dobavljac == "") {
        alert("Morate popuniti sva obavezna polja o nalogu.");
        return false;
    }

    if (redovi.length == 0) {
        alert("Nalog mora imati bar jednu stavku.");
        return false;
    }

    for (let i = 0; i < redovi.length; i++) {
        let igra = redovi[i].querySelector(".igraSelect").value;
        let kolicina = parseFloat(redovi[i].querySelector(".kolicinaInput").value);
        let cena = parseFloat(redovi[i].querySelector(".cenaInput").value);

        if (igra == "" || isNaN(kolicina) || kolicina <= 0 || isNaN(cena) || cena <= 0) {
            alert("Morate ispravno popuniti sve stavke naloga.");
            return false;
        }
    }

    return true;
}

document.querySelectorAll(".stavkaRed").forEach(function(red) {
    postaviDogadjajeZaRed(red);
});
</script>

<?php } ?>

</td>
<td style="width:3%;"></td>
</tr>

</table>

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />

</td>
<td style="width:5%;"></td>
</tr>
</table>
