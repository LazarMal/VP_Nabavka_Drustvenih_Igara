<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="white">

<tr>
<td style="width:15%;" align="right" valign="middle">
<font face="Trebuchet MS" color="darkblue" size="2px">
<b>&nbsp;датум: <?php echo date("d.m.Y."); ?></b><br/>
</font>
</td>
<td></td>
<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:15%;"></td>

<td align="center" valign="middle"> 
<font face="Trebuchet MS" color="darkblue" size="5px">
<b>СПИСАК КЊИГА</b><br/>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:15%;"></td>

<td align="left">
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">

<?php
if ($KnjigaViewObject->BrojZapisa==0)
{
    echo "НЕМА ЗАПИСА У ТАБЕЛИ!";
}
else
{
    echo "<table style=\"width:95%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
    echo "<tr>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">ISBN</font></b></td>";
    echo "<td style=\"width:30%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">НАЗИВ КЊИГЕ</font></b></td>";
    echo "<td style=\"width:25%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">АУТОР</font></b></td>";
    echo "<td style=\"width:25%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">ЖАНР</font></b></td>";
    echo "</tr>";

    for ($RBZapisa = 0; $RBZapisa < $KnjigaViewObject->BrojZapisa; $RBZapisa++) 
    {
        $ISBN = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 0);
        $Naziv = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 1);
        $Autor = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 2);
        $NazivZanra = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 3);

        echo "<tr>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">$ISBN</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">$Naziv</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">$Autor</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">$NazivZanra</font></td>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan=\"4\" align=\"right\">";
    echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">УКУПНО: ".$KnjigaViewObject->BrojZapisa."</font>&nbsp;&nbsp;<br/>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";
}

?>

</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:15%;"></td>

<td align="right" valign="middle"> 
<?php
echo "<br/><br/>";
echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">Одговорно лице</font><br/><br/>";
echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">_______________________</font><br/>";
?>
</td>

<td style="width:5%;"></td>
</tr>

</table>