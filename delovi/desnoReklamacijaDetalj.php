<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>DETALJI REKLAMACIJE DRUŠTVENIH IGARA</b><br/><br/>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="left">

<?php
if ($reklamacija == null) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Reklamacija nije pronađena.</font>";
} else {
    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
    echo "<tr bgcolor=\"#B7F3FE\">";
    echo "<td colspan=\"2\"><b>PODACI O REKLAMACIJI</b></td>";
    echo "</tr>";
    echo "<tr><td style=\"width:30%;\"><b>Broj reklamacije</b></td><td>".htmlspecialchars($reklamacija['BrojReklamacije'])."</td></tr>";
    echo "<tr><td><b>Datum reklamacije</b></td><td>".htmlspecialchars($reklamacija['DatumReklamacije'])."</td></tr>";
    echo "<tr><td><b>Dobavljač</b></td><td>".htmlspecialchars($reklamacija['Dobavljac'])."</td></tr>";
    echo "<tr><td><b>Napomena</b></td><td>".htmlspecialchars($reklamacija['Napomena'])."</td></tr>";
    echo "<tr><td><b>Reklamaciju evidentirao</b></td><td>".htmlspecialchars($reklamacija['ReklamacijuEvidentirao'])."</td></tr>";
    echo "</table>";

    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
    echo "<tr bgcolor=\"#B7F3FE\">";
    echo "<td colspan=\"6\"><b>STAVKE REKLAMACIJE</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Stavka</b></td>";
    echo "<td><b>Društvena igra</b></td>";
    echo "<td><b>Cena po komadu</b></td>";
    echo "<td><b>Količina</b></td>";
    echo "<td><b>Razlog reklamacije</b></td>";
    echo "<td><b>Ukupno</b></td>";
    echo "</tr>";

    $ukupnoReklamacija = 0;
    $brojStavki = 0;

    while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
        $brojStavki++;
        $ukupnoReklamacija += $stavka['Ukupno'];

        echo "<tr>";
        echo "<td>".$brojStavki."</td>";
        echo "<td>".htmlspecialchars($stavka['Naziv'])." (".htmlspecialchars($stavka['SifraIgre']).")</td>";
        echo "<td>".htmlspecialchars($stavka['Cena'])."</td>";
        echo "<td>".htmlspecialchars($stavka['Kolicina'])."</td>";
        echo "<td>".htmlspecialchars($stavka['RazlogReklamacije'])."</td>";
        echo "<td>".htmlspecialchars($stavka['Ukupno'])."</td>";
        echo "</tr>";
    }

    echo "<tr bgcolor=\"#E8F4FC\">";
    echo "<td colspan=\"3\" align=\"right\"><b>REKAPITULACIJA</b></td>";
    echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
    echo "<td><b>Ukupna vrednost reklamacije: ".$ukupnoReklamacija."</b></td>";
    echo "</tr>";
    echo "</table>";

    echo "<a href=\"Ruter.php?stranica=reklamacije\"><input type=\"button\" value=\"POVRATAK NA LISTU\" /></a>";
    echo "&nbsp;&nbsp;";
    echo "<form action=\"Ruter.php?stranica=reklamacijaIzmeniForm\" method=\"POST\" style=\"display:inline;\">";
    echo "<input type=\"hidden\" name=\"IDReklamacije\" value=\"".htmlspecialchars($reklamacija['IDReklamacije'])."\">";
    echo "<input type=\"submit\" name=\"izmeniReklamaciju\" value=\"IZMENI\" />";
    echo "</form>";
}
?>

</td>

<td style="width:5%;"></td>
</tr>
</table>
