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
<td align="center">
<b><font face="Trebuchet MS" color="black" size="3px">ИЗМЕНА ПОДАТАКА ДРУШТВЕНЕ ИГРЕ</font></b><br/>
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
<form name="FormaZaIzmenuIgre" action="kontroler/akcije/knjigaIzmeni.php" method="POST" enctype="multipart/form-data">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Шифра игре&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input name="sifraIgre" type="text" size="50" maxlength="13"
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
<b><font face="Trebuchet MS" color="black" size="2px">Назив игре&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="naziv" type="text" size="50" maxlength="100"
value="<?php echo $StariNaziv; ?>" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Произвођач&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="proizvodjac" type="text" size="50" maxlength="100"
value="<?php echo $StariProizvodjac; ?>" required />
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="top">
<b><font face="Trebuchet MS" color="black" size="2px">Категорија&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<select name="oznakaKategorije" required tabindex="7">
    <option value="">изаберите...</option>
    <?php
    if ($UkupanBrojZapisa > 0) 
    {                   
        for ($brojacKategorija = 0; $brojacKategorija < $UkupanBrojZapisa; $brojacKategorija++) 
        {
            $oznakaKategorije = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 0);               
            $nazivKategorije = $ZanrObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisa, $brojacKategorija, 1);

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
<font face="Trebuchet MS" color="black" size="2px">Тренутна категорија: <?php echo $StaraOznakaKategorije; ?></font>
<input type="hidden" name="StaraOznakaKategorije" value="<?php echo $StaraOznakaKategorije; ?>">
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="top">
<b><font face="Trebuchet MS" color="black" size="2px">Слика игре&nbsp;&nbsp;</font><br/></b>
</td>
<td align="left" valign="bottom">
<input name="nazivFajlaSlike" type="file" size="50" accept=".jpg,.jpeg,.png" /> <br/>
<font face="Trebuchet MS" color="black" size="2px">Стара слика: <?php echo $StariNazivFajlaSlike; ?></font>
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
<input type="submit" name="snimiButton" value="САЧУВАЈ ИЗМЕНУ" tabindex="3"/>
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
