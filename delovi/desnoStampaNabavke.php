<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

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
<b>SPISAK NALOGA ZA NABAVKU DRUŠTVENIH IGARA</b><br/>
</font>
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
    if ($filterBrojNaloga != "") {
        echo "Broj naloga: ".htmlspecialchars($filterBrojNaloga)."<br/>";
    }
    if ($filterDatumNabavke != "") {
        echo "Datum nabavke: ".htmlspecialchars($filterDatumNabavke)."<br/>";
    }
    if ($filterDobavljac != "") {
        echo "Dobavljač: ".htmlspecialchars($filterDobavljac)."<br/>";
    }
    if ($filterBrojNaloga == "" && $filterDatumNabavke == "" && $filterDobavljac == "") {
        echo "Nema unetih vrednosti filtera.<br/>";
    }
    echo "<br/>";
}

if (mysqli_num_rows($rezultatNabavke) == 0) {
    echo "NEMA EVIDENTIRANIH NALOGA ZA NABAVKU!";
} else {
    $ukupanBrojNaloga = 0;

    while ($nabavka = mysqli_fetch_assoc($rezultatNabavke)) {
        $ukupanBrojNaloga++;
        $IDNabavke = $nabavka['IDNabavke'];

        echo "<table style=\"width:95%; padding:0; margin-bottom:15px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bgcolor=\"white\">";
        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"5\">";
        echo "<b>Nalog za nabavku društvenih igara</b><br/>";
        echo "Broj naloga: ".htmlspecialchars($nabavka['BrojNaloga'])."<br/>";
        echo "Datum nabavke: ".htmlspecialchars($nabavka['DatumNabavke'])."<br/>";
        echo "Dobavljač: ".htmlspecialchars($nabavka['Dobavljac'])."<br/>";
        echo "Napomena: ".htmlspecialchars($nabavka['Napomena'])."<br/>";
        echo "Nalog evidentirao: ".htmlspecialchars($nabavka['NalogEvidentirao']);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td style=\"width:10%;\"><b>Stavka</b></td>";
        echo "<td style=\"width:35%;\"><b>Društvena igra</b></td>";
        echo "<td style=\"width:20%;\"><b>Cena po komadu</b></td>";
        echo "<td style=\"width:15%;\"><b>Količina</b></td>";
        echo "<td style=\"width:20%;\"><b>Ukupno</b></td>";
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
            echo "</tr>";
        }

        echo "<tr bgcolor=\"#E8F4FC\">";
        echo "<td colspan=\"2\" align=\"right\"><b>Rekapitulacija:</b></td>";
        echo "<td colspan=\"2\"><b>Ukupan broj stavki: ".$brojStavki."</b></td>";
        echo "<td><b>Ukupna vrednost nabavke: ".$ukupnoNabavka."</b></td>";
        echo "</tr>";
        echo "</table>";
    }

    echo "<table style=\"width:95%; padding:0\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">";
    echo "<tr>";
    echo "<td align=\"right\">";
    echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"2px\">Ukupan broj naloga: ".$ukupanBrojNaloga."</font>&nbsp;&nbsp;<br/>";
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
