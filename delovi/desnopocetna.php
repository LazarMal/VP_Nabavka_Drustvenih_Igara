<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td>
<br/> 
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>СПИСАК ДРУШТВЕНИХ ИГАРА</b><br/><br/>

<form action="" method="GET">
Шифра / назив: <input type="text" name="filter" />
<input type="submit" name="filtriraj" value="FILTRIRAJ" />
<input type="submit" name="svi" value="SVI" />
</form>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

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
    echo "<table style=\"width:90%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" bgcolor=\"#D8E7F4\">";
    echo "<tr>";
    echo "<td style=\"width:10%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;СЛИКА&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:15%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;ШИФРА&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:30%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;НАЗИВ ИГРЕ&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:25%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;ПРОИЗВОЂАЧ&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;КАТЕГОРИЈА&nbsp;</font></b><br/></td>";
    echo "</tr>";

    for ($RBZapisa = 0; $RBZapisa < $KnjigaViewObject->BrojZapisa; $RBZapisa++) 
    {
        $SifraIgre = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 0);
        $Naziv = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 1);
        $Proizvodjac = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 2);
        $NazivKategorije = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 3);
        $NazivFajlaSlike = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 4);

        echo "<tr>";
        echo "<td align=\"center\">";

        if ($NazivFajlaSlike != "")
        {
         echo "<img src=\"SlikeKnjiga/".$NazivFajlaSlike."\" width=\"45\" height=\"60\">";
        }
        else
        {
            echo "-";
        }

        echo "</td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$SifraIgre</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Naziv</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Proizvodjac</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$NazivKategorije</font><br/></td>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan=\"4\" align=\"right\"></td>";
    echo "<td align=\"right\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">УКУПНО: ".$KnjigaViewObject->BrojZapisa."&nbsp;&nbsp;</font><br/></td>";
    echo "</tr>";

    echo "</table>";
    echo "<br/><br/>";
}

?>

</font>
</td>

<td style="width:5%;"></td>
</tr>
</table>

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />
