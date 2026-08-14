<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>PREGLED REKLAMACIJA DRUŠTVENIH IGARA</b><br/><br/>

<form action="Ruter.php" method="GET">
<input type="hidden" name="stranica" value="reklamacije">
Broj reklamacije: <input type="text" name="filterBrojReklamacije" value="<?php echo htmlspecialchars($filterBrojReklamacije, ENT_QUOTES, 'UTF-8'); ?>" maxlength="50" />
&nbsp;&nbsp;
Datum reklamacije: <input type="date" name="filterDatumReklamacije" value="<?php echo htmlspecialchars($filterDatumReklamacije, ENT_QUOTES, 'UTF-8'); ?>" />
&nbsp;&nbsp;
Dobavljač: <input type="text" name="filterDobavljac" value="<?php echo htmlspecialchars($filterDobavljac, ENT_QUOTES, 'UTF-8'); ?>" maxlength="100" />
&nbsp;&nbsp;
<input type="submit" name="filtriraj" value="FILTRIRAJ" />
<input type="submit" name="svi" value="SVI" />
</form>
<br/>
<?php
$stampUrl = "Ruter.php?stranica=stampaReklamacija";
if (isset($_GET['filtriraj']) && !isset($_GET['svi'])) {
    $stampUrl .= "&filtriraj=1";
    if ($filterBrojReklamacije != "") {
        $stampUrl .= "&filterBrojReklamacije=" . urlencode($filterBrojReklamacije);
    }
    if ($filterDatumReklamacije != "") {
        $stampUrl .= "&filterDatumReklamacije=" . urlencode($filterDatumReklamacije);
    }
    if ($filterDobavljac != "") {
        $stampUrl .= "&filterDobavljac=" . urlencode($filterDobavljac);
    }
}
?>
<a href="<?php echo htmlspecialchars($stampUrl); ?>"><input type="button" value="ŠTAMPA" /></a>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<?php

if (count($listaReklamacija) == 0) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Nema evidentiranih reklamacija.</font>";
} else {
    foreach ($listaReklamacija as $reklamacija) {

        $IDReklamacije = $reklamacija['IDReklamacije'];

        echo "<table style=\"width:95%; padding:0; margin-bottom:20px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
        echo "<tr bgcolor=\"#B7F3FE\">";
        echo "<td colspan=\"6\">";
        echo "<font face=\"Trebuchet MS\" color=\"black\" size=\"3px\">";
        echo "<b>Reklamacija — ID: ".htmlspecialchars($reklamacija['IDReklamacije'])."</b><br/>";
        echo "Broj reklamacije: ".htmlspecialchars($reklamacija['BrojReklamacije'])."<br/>";
        echo "Datum reklamacije: ".htmlspecialchars($reklamacija['DatumReklamacije'])."<br/>";
        echo "Dobavljač: ".htmlspecialchars($reklamacija['Dobavljac'])."<br/>";
        echo "Reklamaciju evidentirao: ".htmlspecialchars($reklamacija['ReklamacijuEvidentirao'])."<br/>";
        echo "Napomena: ".htmlspecialchars($reklamacija['Napomena']);
        echo "</font>";
        echo "</td>";
        echo "<td align=\"center\" valign=\"middle\">";
        echo "<a href=\"Ruter.php?stranica=reklamacijaDetalj&amp;id=".$IDReklamacije."\">";
        echo "<input type=\"button\" value=\"DETALJ\" />";
        echo "</a><br/><br/>";
        echo "<form action=\"Ruter.php?stranica=reklamacijaIzmeniForm\" method=\"POST\" style=\"margin-bottom:8px;\">";
        echo "<input type=\"hidden\" name=\"IDReklamacije\" value=\"".$IDReklamacije."\">";
        echo "<input type=\"submit\" name=\"izmeniReklamaciju\" value=\"IZMENI\" />";
        echo "</form>";
        echo "<form action=\"Ruter.php?stranica=obrisiReklamaciju\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"IDReklamacije\" value=\"".$IDReklamacije."\">";
        echo "<input type=\"submit\" name=\"obrisiReklamaciju\" value=\"OBRISI\" onclick=\"return confirm('Da li ste sigurni da zelite da obrisete reklamaciju broj ".htmlspecialchars($reklamacija['BrojReklamacije'], ENT_QUOTES)."?')\" />";
        echo "</form>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><b>Stavka</b></td>";
        echo "<td><b>Društvena igra</b></td>";
        echo "<td><b>Cena po komadu</b></td>";
        echo "<td><b>Količina</b></td>";
        echo "<td><b>Razlog reklamacije</b></td>";
        echo "<td><b>Ukupno</b></td>";
        echo "<td></td>";
        echo "</tr>";

        $ukupnoReklamacija = 0;
        $brojStavki = 0;

        foreach ($reklamacija['stavke'] as $stavka) {
            $brojStavki++;
            $ukupnoReklamacija += $stavka['Ukupno'];

            echo "<tr>";
            echo "<td>".$brojStavki."</td>";
            echo "<td>".htmlspecialchars($stavka['Naziv'])." (".htmlspecialchars($stavka['SifraIgre']).")</td>";
            echo "<td>".htmlspecialchars($stavka['Cena'])."</td>";
            echo "<td>".htmlspecialchars($stavka['Kolicina'])."</td>";
            echo "<td>".htmlspecialchars($stavka['RazlogReklamacije'])."</td>";
            echo "<td>".htmlspecialchars($stavka['Ukupno'])."</td>";
            echo "<td></td>";
            echo "</tr>";
        }

        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"3\" align=\"right\"><b>Rekapitulacija:</b></td>";
        echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
        echo "<td><b>Ukupna vrednost reklamacije: ".$ukupnoReklamacija."</b></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "</table>";
    }
}
?>

</td>

<td style="width:5%;"></td>
</tr>
</table>
