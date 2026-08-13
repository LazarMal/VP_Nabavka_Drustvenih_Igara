<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/> 
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>SPISAK DRUSTVENIH IGAR</b><br/><br/>

<form action="Ruter.php" method="GET">
<input type="hidden" name="stranica" value="knjige">
Naziv / sifra / proizvodjac: <input type="text" name="filter" />
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
    echo "NEMA ZAPISA U TABELI!";
}
else
{
    echo "<table style=\"width:98%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"3\" border=\"1\" bgcolor=\"#D8E7F4\">";
    echo "<tr>";
    echo "<td style=\"width:10%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">SLIKA</font></b></td>";
    echo "<td style=\"width:16%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">SIFRA</font></b></td>";
    echo "<td style=\"width:24%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">NAZIV IGRE</font></b></td>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">PROIZVODJAC</font></b></td>";
    echo "<td style=\"width:16%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">KATEGORIJA</font></b></td>";
    echo "<td style=\"width:7%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">IZMENA</font></b></td>";
    echo "<td style=\"width:7%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">BRISANJE</font></b></td>";
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

            if ($NazivFajlaSlike != "") {
                echo "<img src=\"SlikeKnjiga/$NazivFajlaSlike\" width=\"45\" height=\"60\">";
            } else {
                echo "-";
            }

        echo "</td>";

        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$SifraIgre</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Naziv</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$Proizvodjac</font></td>";
        echo "<td><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">$NazivKategorije</font></td>";

        echo "<td align=\"center\">";
        echo "<form action=\"Ruter.php?stranica=izmenaForm\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"sifraIgre\" value=\"$SifraIgre\">";
        echo "<input type=\"submit\" name=\"izmeniIgru\" value=\"IZMENI\" />";
        echo "</form>";
        echo "</td>";

        echo "<td align=\"center\">";
        echo "<form action=\"Ruter.php?stranica=obrisiKnjigu\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"sifraIgre\" value=\"$SifraIgre\">";
        echo "<input type=\"submit\" name=\"obrisiIgru\" value=\"OBRISI\" onclick=\"return confirm('Da li ste sigurni da zelite da obrisete igru?')\"/>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan=\"5\" align=\"right\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">UKUPNO: ".$KnjigaViewObject->BrojZapisa."&nbsp;&nbsp;</font></b></td>";
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
