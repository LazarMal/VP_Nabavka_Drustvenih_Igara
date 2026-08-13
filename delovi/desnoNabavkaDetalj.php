<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>DETALJI NALOGA ZA NABAVKU DRUŠTVENIH IGARA</b><br/><br/>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="left">

<?php
if ($nabavka == null) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Nalog nije pronađen.</font>";
} else {
    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
    echo "<tr bgcolor=\"#B7F3FE\">";
    echo "<td colspan=\"2\"><b>PODACI O NABAVCI</b></td>";
    echo "</tr>";
    echo "<tr><td style=\"width:30%;\"><b>Broj naloga</b></td><td>".htmlspecialchars($nabavka['BrojNaloga'])."</td></tr>";
    echo "<tr><td><b>Datum nabavke</b></td><td>".htmlspecialchars($nabavka['DatumNabavke'])."</td></tr>";
    echo "<tr><td><b>Dobavljač</b></td><td>".htmlspecialchars($nabavka['Dobavljac'])."</td></tr>";
    echo "<tr><td><b>Napomena</b></td><td>".htmlspecialchars($nabavka['Napomena'])."</td></tr>";
    echo "<tr><td><b>Nalog evidentirao</b></td><td>".htmlspecialchars($nabavka['NalogEvidentirao'])."</td></tr>";
    echo "</table>";

    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
    echo "<tr bgcolor=\"#B7F3FE\">";
    echo "<td colspan=\"5\"><b>SPISAK IGARA ZA NABAVKU</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Stavka</b></td>";
    echo "<td><b>Društvena igra</b></td>";
    echo "<td><b>Cena po komadu</b></td>";
    echo "<td><b>Količina</b></td>";
    echo "<td><b>Ukupno</b></td>";
    echo "</tr>";

    $ukupnoNabavka = 0;
    $brojStavki = 0;

    while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
        $brojStavki++;
        $ukupnoNabavka += $stavka['Ukupno'];

        echo "<tr>";
        echo "<td>".$brojStavki."</td>";
        echo "<td>".htmlspecialchars($stavka['Naziv'])." (".htmlspecialchars($stavka['SifraIgre']).")</td>";
        echo "<td>".$stavka['Cena']."</td>";
        echo "<td>".$stavka['Kolicina']."</td>";
        echo "<td>".$stavka['Ukupno']."</td>";
        echo "</tr>";
    }

    echo "<tr bgcolor=\"#E8F4FC\">";
    echo "<td colspan=\"2\" align=\"right\"><b>Rekapitulacija:</b></td>";
    echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
    echo "<td><b>Ukupna vrednost nabavke: ".$ukupnoNabavka."</b></td>";
    echo "</tr>";
    echo "</table>";

    echo "<a href=\"Ruter.php?stranica=nabavke\"><input type=\"button\" value=\"POVRATAK NA LISTU\" /></a>";
    echo "&nbsp;&nbsp;";
    echo "<form action=\"Ruter.php?stranica=nabavkaIzmeniForm\" method=\"POST\" style=\"display:inline;\">";
    echo "<input type=\"hidden\" name=\"IDNabavke\" value=\"".htmlspecialchars($nabavka['IDNabavke'])."\">";
    echo "<input type=\"submit\" name=\"izmeniNalog\" value=\"IZMENI\" />";
    echo "</form>";
}
?>

</td>

<td style="width:5%;"></td>
</tr>
</table>
