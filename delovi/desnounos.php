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
<b><font face="Trebuchet MS" color="black" size="3px">УНОС НОВЕ КЊИГЕ</font></b><br/>
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

<form name="FormaZaUnosKnjige" action="kontroler/akcije/knjigaSnimi.php" method="POST" enctype="multipart/form-data" onsubmit="return proveriUnosKnjige();">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">ISBN&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input name="isbn" id="isbn" type="text" size="50" maxlength="13" minlength="13"
pattern="[0-9]{13}"
title="ISBN мора имати тачно 13 цифара"
placeholder="Унесите ISBN књиге" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Назив књиге&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="naziv" id="naziv" type="text" size="50" maxlength="100"
placeholder="Унесите назив књиге" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Аутор&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="autor" id="autor" type="text" size="50" maxlength="100"
placeholder="Унесите аутора" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td align="left" valign="bottom"></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Жанр&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">

<select name="oznakaZanra" id="oznakaZanra" required tabindex="7">
    <option value="">изаберите...</option>
    <?php
    if ($UkupanBrojZapisa > 0) 
    {                   
        for ($brojacZanrova = 0; $brojacZanrova < $UkupanBrojZapisa; $brojacZanrova++) 
        {
            $oznakaZanra = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacZanrova, 0);               
            $nazivZanra = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacZanrova, 1);               
            echo "<option value=\"$oznakaZanra\">$nazivZanra</option>";                     
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
<b><font face="Trebuchet MS" color="black" size="2px">Слика књиге&nbsp;&nbsp;</font><br/></b>
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
<input type="submit" name="snimiButton" value="САЧУВАЈ" tabindex="3"/>
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
function proveriUnosKnjige() {
    let isbn = document.getElementById("isbn").value.trim();
    let naziv = document.getElementById("naziv").value.trim();
    let autor = document.getElementById("autor").value.trim();
    let zanr = document.getElementById("oznakaZanra").value;

    if (!/^[0-9]{13}$/.test(isbn)) {
        alert("ISBN мора имати тачно 13 цифара.");
        return false;
    }

    if (naziv == "" || naziv.length > 100) {
        alert("Назив књиге је обавезан и не сме бити дужи од 100 карактера.");
        return false;
    }

    if (autor == "" || autor.length > 100) {
        alert("Аутор је обавезан и не сме бити дужи од 100 карактера.");
        return false;
    }

    if (zanr == "") {
        alert("Морате изабрати жанр.");
        return false;
    }

    return true;
}
</script>