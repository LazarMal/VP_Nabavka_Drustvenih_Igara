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
Назив књиге: <input type="text" name="filter" />
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
    echo "<table style=\"width:98%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"3\" border=\"1\" bgcolor=\"#D8E7F4\">";
    echo "<tr>";
    echo "<td style=\"width:10%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">СЛИКА</font></b></td>";
    echo "<td style=\"width:16%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">ISBN</font></b></td>";
    echo "<td style=\"width:24%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">НАЗИВ КЊИГЕ</font></b></td>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">АУТОР</font></b></td>";
    echo "<td style=\"width:16%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">ЖАНР</font></b></td>";
    echo "<td style=\"width:7%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">ИЗМЕНА</font></b></td>";
    echo "<td style=\"width:7%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">БРИСАЊЕ</font></b></td>";
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

            if ($NazivFajlaSlike != "") {
                echo "<img src=\"http://localhost/vp2025/SlikeKnjiga/$NazivFajlaSlike\" width=\"45\" height=\"60\">";
            } else {
                echo "-";
            }

        echo "</td>";

        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$ISBN</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Naziv</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Autor</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$NazivZanra</font></td>";

        echo "<td align=\"center\">";
        echo "<form action=\"ruter.php?stranica=izmenaForm\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"isbn\" value=\"$ISBN\">";
        echo "<input type=\"submit\" name=\"izmeniKnjigu\" value=\"ИЗМЕНИ\" />";
        echo "</form>";
        echo "</td>";

        echo "<td align=\"center\">";
        echo "<form action=\"ruter.php?stranica=obrisiKnjigu\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"isbn\" value=\"$ISBN\">";
        echo "<input type=\"submit\" name=\"obrisiKnjigu\" value=\"ОБРИШИ\" onclick=\"return confirm('Да ли сте сигурни да желите да обришете књигу?')\"/>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan=\"5\" align=\"right\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">УКУПНО: ".$KnjigaViewObject->BrojZapisa."&nbsp;&nbsp;</font></b></td>";
    echo "<td></td>";
    echo "<td></td>";
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