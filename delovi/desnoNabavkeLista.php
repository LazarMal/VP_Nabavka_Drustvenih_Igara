<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">

<tr>
<td style="width:5%;"></td>

<td>
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>ПРЕГЛЕД НАБАВКИ КЊИГА</b><br/><br/>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="left">

<?php

if (mysqli_num_rows($rezultatNabavke) == 0) {
    echo "<font face=\"Trebuchet MS\" color=\"darkblue\" size=\"3px\">Нема евидентираних набавки.</font>";
} else {
    while ($nabavka = mysqli_fetch_assoc($rezultatNabavke)) {

        $IDNabavke = $nabavka['IDNabavke'];

        echo "<table style=\"width:95%; padding:0; margin-bottom:20px;\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=\"1\" bgcolor=\"#FFFFFF\">";
        echo "<tr bgcolor=\"#B7F3FE\">";
        echo "<td colspan=\"5\">";
        echo "<font face=\"Trebuchet MS\" color=\"black\" size=\"3px\">";
        echo "<b>Набавка број: ".$nabavka['IDNabavke']."</b><br/>";
        echo "Датум: ".$nabavka['DatumNabavke']."<br/>";
        echo "Добављач: ".$nabavka['Dobavljac']."<br/>";
        echo "Напомена: ".$nabavka['Napomena'];
        echo "</font>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><b>ISBN</b></td>";
        echo "<td><b>Назив књиге</b></td>";
        echo "<td><b>Количина</b></td>";
        echo "<td><b>Цена</b></td>";
        echo "<td><b>Укупно</b></td>";
        echo "</tr>";

        $rezultatStavke = $NabavkeController->DajStavkeNabavke($IDNabavke);
        $ukupnoNabavka = 0;

        while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
            $ukupnoNabavka += $stavka['Ukupno'];

            echo "<tr>";
            echo "<td>".$stavka['ISBN']."</td>";
            echo "<td>".$stavka['Naziv']."</td>";
            echo "<td>".$stavka['Kolicina']."</td>";
            echo "<td>".$stavka['Cena']."</td>";
            echo "<td>".$stavka['Ukupno']."</td>";
            echo "</tr>";
        }

        echo "<tr>";
        echo "<td colspan=\"4\" align=\"right\"><b>Укупна вредност набавке:</b></td>";
        echo "<td><b>".$ukupnoNabavka."</b></td>";
        echo "</tr>";

        echo "</table>";
    }
}
?>

</td>

<td style="width:5%;"></td>
</tr>
</table>
