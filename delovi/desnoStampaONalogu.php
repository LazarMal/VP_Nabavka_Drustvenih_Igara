<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="white">

<tr>
<td style="width:5%;"></td>

<td align="center">
<font face="Trebuchet MS" color="darkblue" size="5px">
<b>Nalog za nabavku društvenih igara</b><br/>
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
if ($nabavka == null) {
    echo "Nalog nije pronađen za uneti broj naloga.";
} else {
    echo "<b>PODACI O NABAVCI</b><br/><br/>";
    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
    echo "<tr><td style=\"width:30%;\"><b>Broj naloga</b></td><td>".htmlspecialchars($nabavka['BrojNaloga'])."</td></tr>";
    echo "<tr><td><b>Datum nabavke</b></td><td>".htmlspecialchars($nabavka['DatumNabavke'])."</td></tr>";
    echo "<tr><td><b>Dobavljač</b></td><td>".htmlspecialchars($nabavka['Dobavljac'])."</td></tr>";
    echo "<tr><td><b>Napomena</b></td><td>".htmlspecialchars($nabavka['Napomena'])."</td></tr>";
    echo "</table>";

    echo "<b>SPISAK IGARA</b><br/><br/>";
    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
    echo "<tr bgcolor=\"#E8F4FC\">";
    echo "<td style=\"width:10%;\"><b>Stavka</b></td>";
    echo "<td style=\"width:35%;\"><b>Društvena igra</b></td>";
    echo "<td style=\"width:20%;\"><b>Cena po komadu</b></td>";
    echo "<td style=\"width:15%;\"><b>Količina</b></td>";
    echo "<td style=\"width:20%;\"><b>Ukupno</b></td>";
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

    echo "</table>";

    echo "<b>REKAPITULACIJA</b><br/><br/>";
    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
    echo "<tr><td style=\"width:50%;\"><b>Ukupan broj stavki</b></td><td>".$brojStavki."</td></tr>";
    echo "<tr><td><b>Ukupna vrednost nabavke</b></td><td>".$ukupnoNabavka."</td></tr>";
    echo "</table>";

    echo "<br/><b>Nalog evidentirao:</b> ".htmlspecialchars($nabavka['NalogEvidentirao']);
}
?>

</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

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
