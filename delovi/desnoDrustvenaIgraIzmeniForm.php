<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<table style="width:100%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">

<tr>
<td style="width:3%;"></td>
<td align="center">
<b><font face="Trebuchet MS" color="black" size="3px">IZMENA PODATAKA DRUŠTVENE IGRE</font></b><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center"><font color="#D8E7F4" size="1px">.</font></td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<table style="width:70%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">
<form name="FormaZaIzmenuIgre" action="kontroler/akcije/drustvenaIgraIzmeni.php" method="POST" enctype="multipart/form-data" onsubmit="return proveriIzmenuIgre();">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Šifra igre&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input name="sifraIgre" id="sifraIgre" type="text" size="50" maxlength="13"
value="<?php echo $StariSifraIgre; ?>" required />
<input type="hidden" name="StaraSifraIgre" value="<?php echo $StariSifraIgre; ?>">
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Naziv igre&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="naziv" id="naziv" type="text" size="50" maxlength="100"
value="<?php echo $StariNaziv; ?>" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Proizvođač&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="proizvodjac" id="proizvodjac" type="text" size="50" maxlength="100"
value="<?php echo $StariProizvodjac; ?>" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="top">
<b><font face="Trebuchet MS" color="black" size="2px">Kategorija&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<select name="oznakaKategorije" id="oznakaKategorije" required tabindex="7">
    <option value="">izaberite...</option>
    <?php
    if ($UkupanBrojZapisa > 0) 
    {                   
        for ($brojacKategorija = 0; $brojacKategorija < $UkupanBrojZapisa; $brojacKategorija++) 
        {
            $oznakaKategorije = $KategorijaIgreObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 0);               
            $nazivKategorije = $KategorijaIgreObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 1);

                if ($oznakaKategorije == $StaraOznakaKategorije) {
                echo "<option value=\"$oznakaKategorije\" selected>$nazivKategorije</option>";
            } else {
                echo "<option value=\"$oznakaKategorije\">$nazivKategorije</option>";
            }
        }
    }
    ?>
</select>
<br/>
<font face="Trebuchet MS" color="black" size="2px">Trenutna kategorija: <?php echo $StaraOznakaKategorije; ?></font>
<input type="hidden" name="StaraOznakaKategorije" value="<?php echo $StaraOznakaKategorije; ?>">
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="top">
<b><font face="Trebuchet MS" color="black" size="2px">Slika igre&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="nazivFajlaSlike" type="file" size="50" accept=".jpg,.jpeg,.png" /> <br/>
<font face="Trebuchet MS" color="black" size="2px">Stara slika: <?php echo $StariNazivFajlaSlike; ?></font>
<input type="hidden" name="StariNazivFajlaSlike" value="<?php echo $StariNazivFajlaSlike; ?>">
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" name="snimiButton" value="SACUVAJ IZMENU" tabindex="3"/>
</td>
</tr>

</form>
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

<script>
function proveriIzmenuIgre() {
    let sifraIgre = document.getElementById("sifraIgre").value.trim();
    let naziv = document.getElementById("naziv").value.trim();
    let proizvodjac = document.getElementById("proizvodjac").value.trim();
    let kategorija = document.getElementById("oznakaKategorije").value;

    if (!/^[A-Za-z0-9]{1,13}$/.test(sifraIgre)) {
        alert("Šifra igre mora biti alfanumerička i do 13 karaktera.");
        return false;
    }

    if (naziv == "" || naziv.length > 100) {
        alert("Naziv igre je obavezan i ne sme biti duži od 100 karaktera.");
        return false;
    }

    if (proizvodjac == "" || proizvodjac.length > 100) {
        alert("Proizvođač je obavezan i ne sme biti duži od 100 karaktera.");
        return false;
    }

    if (kategorija == "") {
        alert("Morate izabrati kategoriju.");
        return false;
    }

    let slika = document.querySelector('input[name="nazivFajlaSlike"]');
    if (slika && slika.value != "") {
        let ext = slika.value.split(".").pop().toLowerCase();
        if (["jpg", "jpeg", "png"].indexOf(ext) === -1) {
            alert("Dozvoljene su samo JPG, JPEG i PNG slike.");
            return false;
        }
    }

    return true;
}
</script>
