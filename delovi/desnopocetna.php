<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td>
<br/> 
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>SPISAK DRUŠTVENIH IGARA</b><br/><br/>

<form action="" method="GET">
Šifra / naziv: <input type="text" name="filter" />
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
if ($DrustvenaIgraViewObject->BrojZapisa==0)
{
    echo "NEMA ZAPISA U TABELI!";
}
else
{
    echo "<table style=\"width:90%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" bgcolor=\"#D8E7F4\">";
    echo "<tr>";
    echo "<td style=\"width:10%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;SLIKA&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:15%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;ŠIFRA&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:30%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;NAZIV IGRE&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:25%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;PROIZVOĐAČ&nbsp;</font></b><br/></td>";
    echo "<td style=\"width:20%;\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">&nbsp;KATEGORIJA&nbsp;</font></b><br/></td>";
    echo "</tr>";

    for ($RBZapisa = 0; $RBZapisa < $DrustvenaIgraViewObject->BrojZapisa; $RBZapisa++) 
    {
        $SifraIgre = $DrustvenaIgraViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($DrustvenaIgraViewObject->Kolekcija, $RBZapisa, 0);
        $Naziv = $DrustvenaIgraViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($DrustvenaIgraViewObject->Kolekcija, $RBZapisa, 1);
        $Proizvodjac = $DrustvenaIgraViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($DrustvenaIgraViewObject->Kolekcija, $RBZapisa, 2);
        $NazivKategorije = $DrustvenaIgraViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($DrustvenaIgraViewObject->Kolekcija, $RBZapisa, 3);
        $NazivFajlaSlike = $DrustvenaIgraViewObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($DrustvenaIgraViewObject->Kolekcija, $RBZapisa, 4);

        echo "<tr>";
        echo "<td align=\"center\">";

        if ($NazivFajlaSlike != "")
        {
         echo "<img src=\"SlikeIgara/".$NazivFajlaSlike."\" width=\"45\" height=\"60\">";
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
    echo "<td align=\"right\"><b><font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">УКУПНО: ".$DrustvenaIgraViewObject->BrojZapisa."&nbsp;&nbsp;</font><br/></td>";
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

