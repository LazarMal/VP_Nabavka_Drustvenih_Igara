<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>PREGLED NALOGA ZA NABAVKU DRUSTVENIH IGAR</b><br/><br/>

<form action="Ruter.php" method="GET">
<input type="hidden" name="stranica" value="nabavke">
Broj naloga: <input type="text" name="filterBrojNaloga" value="<?php echo htmlspecialchars($filterBrojNaloga); ?>" maxlength="50" />
&nbsp;&nbsp;
Datum nabavke: <input type="date" name="filterDatumNabavke" value="<?php echo htmlspecialchars($filterDatumNabavke); ?>" />
&nbsp;&nbsp;
Dobavljac: <input type="text" name="filterDobavljac" value="<?php echo htmlspecialchars($filterDobavljac); ?>" maxlength="100" />
&nbsp;&nbsp;
<input type="submit" name="filtriraj" value="FILTRIRAJ" />
<input type="submit" name="svi" value="SVI" />
</form>
<br/>
<a href="Ruter.php?stranica=stampaNabavke"><input type="button" value="ŠTAMPA SVIH NALOGA" /></a>
&nbsp;&nbsp;
<?php
$stampUrl = "Ruter.php?stranica=stampaNabavke&filtriraj=1";
if ($filterBrojNaloga != "") {
    $stampUrl .= "&filterBrojNaloga=" . urlencode($filterBrojNaloga);
}
if ($filterDatumNabavke != "") {
    $stampUrl .= "&filterDatumNabavke=" . urlencode($filterDatumNabavke);
}
if ($filterDobavljac != "") {
    $stampUrl .= "&filterDobavljac=" . urlencode($filterDobavljac);
}
?>
<a href="<?php echo htmlspecialchars($stampUrl); ?>"><input type="button" value="ŠTAMPA FILTRIRANIH NALOGA" /></a>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<?php

if (mysqli_num_rows($rezultatNabavke) == 0) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Nema evidentiranih naloga za nabavku.</font>";
} else {
    while ($nabavka = mysqli_fetch_assoc($rezultatNabavke)) {

        $IDNabavke = $nabavka['IDNabavke'];

        echo "<table style=\"width:95%; padding:0; margin-bottom:20px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
        echo "<tr bgcolor=\"#B7F3FE\">";
        echo "<td colspan=\"5\">";
        echo "<font face=\"Trebuchet MS\" color=\"black\" size=\"3px\">";
        echo "<b>Nalog za nabavku — ID: ".$nabavka['IDNabavke']."</b><br/>";
        echo "Broj naloga: ".htmlspecialchars($nabavka['BrojNaloga'])."<br/>";
        echo "Datum nabavke: ".htmlspecialchars($nabavka['DatumNabavke'])."<br/>";
        echo "Dobavljac: ".htmlspecialchars($nabavka['Dobavljac'])."<br/>";
        echo "Nalog evidentirao: ".htmlspecialchars($nabavka['NalogEvidentirao'])."<br/>";
        echo "Napomena: ".htmlspecialchars($nabavka['Napomena']);
        echo "</font>";
        echo "</td>";
        echo "<td align=\"center\" valign=\"middle\">";
        echo "<a href=\"Ruter.php?stranica=nabavkaDetalj&amp;id=".$IDNabavke."\">";
        echo "<input type=\"button\" value=\"DETALJ\" />";
        echo "</a><br/><br/>";
        echo "<form action=\"Ruter.php?stranica=nabavkaIzmeniForm\" method=\"POST\" style=\"margin-bottom:8px;\">";
        echo "<input type=\"hidden\" name=\"IDNabavke\" value=\"".$IDNabavke."\">";
        echo "<input type=\"submit\" name=\"izmeniNalog\" value=\"IZMENI\" />";
        echo "</form>";
        echo "<form action=\"Ruter.php?stranica=obrisiNabavku\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"IDNabavke\" value=\"".$IDNabavke."\">";
        echo "<input type=\"submit\" name=\"obrisiNalog\" value=\"OBRISI\" onclick=\"return confirm('Da li ste sigurni da zelite da obrisete nalog broj ".htmlspecialchars($nabavka['BrojNaloga'], ENT_QUOTES)."?')\" />";
        echo "</form>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><b>Stavka</b></td>";
        echo "<td><b>Drustvena igra</b></td>";
        echo "<td><b>Cena po komadu</b></td>";
        echo "<td><b>Kolicina</b></td>";
        echo "<td><b>Ukupno</b></td>";
        echo "<td></td>";
        echo "</tr>";

        $rezultatStavke = $NabavkeController->DajStavkeNabavke($IDNabavke);
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
            echo "<td></td>";
            echo "</tr>";
        }

        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"2\" align=\"right\"><b>Rekapitulacija:</b></td>";
        echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
        echo "<td><b>".$ukupnoNabavka."</b></td>";
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
