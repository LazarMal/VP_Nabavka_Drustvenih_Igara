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
<b><font face="Trebuchet MS" color="black" size="3px">UNOS NOVE DRUSTVENE IGRE</font></b><br/>
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

<table style="width:95%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">

<form name="FormaZaUnosIgre" action="kontroler/akcije/knjigaSnimi.php" method="POST" enctype="multipart/form-data" onsubmit="return proveriUnosIgre();">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Sifra igre&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input name="sifraIgre" id="sifraIgre" type="text" size="50" maxlength="13"
placeholder="Unesite sifru igre" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Naziv igre&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="naziv" id="naziv" type="text" size="50" maxlength="100"
placeholder="Unesite naziv igre" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Proizvodjac&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="proizvodjac" id="proizvodjac" type="text" size="50" maxlength="100"
placeholder="Unesite proizvodjaca" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
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
            $oznakaKategorije = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 0);               
            $nazivKategorije = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 1);               
            echo "<option value=\"$oznakaKategorije\">$nazivKategorije</option>";                     
        }
    }
    ?>
</select>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Slika igre&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="nazivFajlaSlike" type="file" size="50" accept=".jpg,.jpeg,.png" />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" name="snimiButton" value="SACUVAJ" tabindex="3"/>
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

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />

<script>
function proveriUnosIgre() {
    let sifraIgre = document.getElementById("sifraIgre").value.trim();
    let naziv = document.getElementById("naziv").value.trim();
    let proizvodjac = document.getElementById("proizvodjac").value.trim();
    let kategorija = document.getElementById("oznakaKategorije").value;

    if (!/^[A-Za-z0-9]{1,13}$/.test(sifraIgre)) {
        alert("Sifra igre mora biti alfanumericka i do 13 karaktera.");
        return false;
    }

    if (naziv == "" || naziv.length > 100) {
        alert("Naziv igre je obavezan i ne sme biti duzi od 100 karaktera.");
        return false;
    }

    if (proizvodjac == "" || proizvodjac.length > 100) {
        alert("Proizvodjac je obavezan i ne sme biti duzi od 100 karaktera.");
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
