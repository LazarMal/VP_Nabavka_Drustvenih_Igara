<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td>
<br/> 
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>СПИСАК КЊИГА</b><br/><br/>

<form action="" method="GET">
ISBN: <input type="text" name="filter" />
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
    echo "<td style=\"width:15%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;ISBN&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:30%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;НАЗИВ КЊИГЕ&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:25%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;АУТОР&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;ЖАНР&nbsp;</font></b><br/></td>";
    echo "</tr>";

    for ($RBZapisa = 0; $RBZapisa < $KnjigaViewObject->BrojZapisa; $RBZapisa++) 
    {
        $ISBN = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 0);
        $Naziv = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 1);
        $Autor = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 2);
        $NazivZanra = $KnjigaViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KnjigaViewObject->Kolekcija, $RBZapisa, 3);
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
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$ISBN</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Naziv</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Autor</font><br/></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$NazivZanra</font><br/></td>";
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