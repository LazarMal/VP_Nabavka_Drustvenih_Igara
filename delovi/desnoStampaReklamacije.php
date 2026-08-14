<meta charset="UTF-8">

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="white">

<tr>
<td style="width:15%;" align="right" valign="middle">
<font face="Trebuchet MS" color="darkblue" size="2px">
<b>&nbsp;datum: <?php echo date("d.m.Y."); ?></b><br/>
</font>
</td>
<td></td>
<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:15%;"></td>

<td align="center" valign="middle">
<font face="Trebuchet MS" color="darkblue" size="5px">
<b>PREGLED REKLAMACIJA DRUŠTVENIH IGARA</b><br/>
</font>
<div class="no-print" style="margin-top:10px;">
<a href="Ruter.php?stranica=reklamacije"><font face="Trebuchet MS" color="darkblue" size="2px">Nazad na reklamacije</font></a>
</div>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:15%;"></td>

<td align="left">
<br/>
<font face="Trebuchet MS" color="darkblue" size="3px">

<?php
if ($filtrirano) {
    echo "<b>Primenjeni filteri:</b><br/>";
    if ($filterBrojReklamacije != "") {
        echo "Broj reklamacije: ".htmlspecialchars($filterBrojReklamacije)."<br/>";
    }
    if ($filterDatumReklamacije != "") {
        echo "Datum reklamacije: ".htmlspecialchars($filterDatumReklamacije)."<br/>";
    }
    if ($filterDobavljac != "") {
        echo "Dobavljač: ".htmlspecialchars($filterDobavljac, ENT_QUOTES, 'UTF-8')."<br/>";
    }
    if ($filterBrojReklamacije == "" && $filterDatumReklamacije == "" && $filterDobavljac == "") {
        echo "Nema unetih vrednosti filtera.<br/>";
    }
    echo "<br/>";
}

if (count($listaReklamacija) == 0) {
    echo "NEMA EVIDENTIRANIH REKLAMACIJA!";
} else {
    $ukupanBrojReklamacija = 0;

    foreach ($listaReklamacija as $reklamacija) {
        $ukupanBrojReklamacija++;

        echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"6\">";
        echo "<b>Reklamacija društvenih igara</b><br/>";
        echo "Broj reklamacije: ".htmlspecialchars($reklamacija['BrojReklamacije'])."<br/>";
        echo "Datum reklamacije: ".htmlspecialchars($reklamacija['DatumReklamacije'])."<br/>";
        echo "Dobavljač: ".htmlspecialchars($reklamacija['Dobavljac'])."<br/>";
        echo "Napomena: ".htmlspecialchars($reklamacija['Napomena'])."<br/>";
        echo "Reklamaciju evidentirao: ".htmlspecialchars($reklamacija['ReklamacijuEvidentirao']);
        echo "</td>";
        echo "</tr>";

        echo "<tr bgcolor=\"#D8E7F4\">";
        echo "<td colspan=\"6\"><b>STAVKE REKLAMACIJE</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td style=\"width:8%;\"><b>Stavka</b></td>";
        echo "<td style=\"width:25%;\"><b>Društvena igra</b></td>";
        echo "<td style=\"width:12%;\"><b>Cena po komadu</b></td>";
        echo "<td style=\"width:10%;\"><b>Količina</b></td>";
        echo "<td style=\"width:25%;\"><b>Razlog reklamacije</b></td>";
        echo "<td style=\"width:12%;\"><b>Ukupno</b></td>";
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
            echo "</tr>";
        }

        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"3\" align=\"right\"><b>Rekapitulacija:</b></td>";
        echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
        echo "<td><b>Ukupna vrednost reklamacije: ".$ukupnoReklamacija."</b></td>";
        echo "</tr>";
        echo "</table>";
    }

    echo "<table style=\"width:95%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">";
    echo "<tr>";
    echo "<td align=\"right\">";
    echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">Ukupan broj reklamacija: ".$ukupanBrojReklamacija."</font>&nbsp;&nbsp;<br/>";
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
echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">Odgovorno lice</font><br/><br/>";
echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">_______________________</font><br/>";
?>
</td>

<td style="width:5%;"></td>
</tr>

</table>
