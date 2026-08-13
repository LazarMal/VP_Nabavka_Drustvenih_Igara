<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>ДЕТАЉИ НАЛОГА ЗА НАБАВКУ ДРУШТВЕНИХ ИГАРА</b><br/><br/>
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
    echo "<td colspan=\"2\"><b>ПОДАЦИ O NALOGU</b></td>";
    echo "</tr>";
    echo "<tr><td style=\"width:30%;\"><b>Број naloga</b></td><td>".htmlspecialchars($nabavka['BrojNaloga'])."</td></tr>";
    echo "<tr><td><b>Датум nabavke</b></td><td>".htmlspecialchars($nabavka['DatumNabavke'])."</td></tr>";
    echo "<tr><td><b>Добављач</b></td><td>".htmlspecialchars($nabavka['Dobavljac'])."</td></tr>";
    echo "<tr><td><b>Напомена</b></td><td>".htmlspecialchars($nabavka['Napomena'])."</td></tr>";
    echo "<tr><td><b>Nalog evidentirao</b></td><td>".htmlspecialchars($nabavka['NalogEvidentirao'])."</td></tr>";
    echo "</table>";

    echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
    echo "<tr bgcolor=\"#B7F3FE\">";
    echo "<td colspan=\"5\"><b>СТАВКЕ NALOGA</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Стavka</b></td>";
    echo "<td><b>Друštvena igra</b></td>";
    echo "<td><b>Цena po komadu</b></td>";
    echo "<td><b>Количina</b></td>";
    echo "<td><b>Укупno</b></td>";
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
    echo "<td colspan=\"2\" align=\"right\"><b>Рекapitulacija:</b></td>";
    echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
    echo "<td><b>".$ukupnoNabavka."</b></td>";
    echo "</tr>";
    echo "</table>";

    echo "<a href=\"Ruter.php?stranica=nabavke\"><input type=\"button\" value=\"ПОВРАТАК NA LISTU\" /></a>";
    echo "&nbsp;&nbsp;";
    echo "<form action=\"Ruter.php?stranica=nabavkaIzmeniForm\" method=\"POST\" style=\"display:inline;\">";
    echo "<input type=\"hidden\" name=\"IDNabavke\" value=\"".htmlspecialchars($nabavka['IDNabavke'])."\">";
    echo "<input type=\"submit\" name=\"izmeniNalog\" value=\"ИЗМЕНИ\" />";
    echo "</form>";
}
?>

</td>

<td style="width:5%;"></td>
</tr>
</table>
