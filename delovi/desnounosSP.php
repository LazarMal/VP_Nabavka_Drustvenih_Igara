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
<b><font face="Trebuchet MS" color="black" size="3px">УНОС НОВЕ КЊИГЕ применом stored procedure</font></b><br/>
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
<form name="FormaZaUnosKnjigeSP" action="kontroler/akcije/knjigaSnimiSP.php" method="POST" enctype="multipart/form-data">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">ISBN&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input name="isbn" type="text" size="50" maxlength="13" minlength="13"
pattern="[0-9]{13}"
title="ISBN мора имати тачно 13 цифара"
placeholder="Унесите ISBN књиге" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Назив књиге&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="naziv" type="text" size="50" maxlength="100"
placeholder="Унесите назив књиге" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Аутор&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="autor" type="text" size="50" maxlength="100"
placeholder="Унесите аутора" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Жанр&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<select name="oznakaZanra" required tabindex="7">
    <option value="">изаберите...</option>
    <?php
    if ($UkupanBrojZapisa > 0) {                   
        for ($brojacZanrova = 0; $brojacZanrova < $UkupanBrojZapisa; $brojacZanrova++) {
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
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Слика књиге&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="nazivFajlaSlike" type="file" size="50" />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="snimiButton" value="САЧУВАЈ ПРЕКО SP" tabindex="3"/></td>
</tr>

</form>
</table>

</td>
<td style="width:3%;"></td>
</tr>
</table>
</td>

<td style="width:5%;"></td>
</tr>
</table>

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />