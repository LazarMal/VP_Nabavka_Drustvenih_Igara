<meta charset="UTF-8">

<div style="max-width:210mm; margin:0 auto; padding:15mm 20mm; font-family:'Trebuchet MS', Arial, sans-serif; color:#1a1a2e; box-sizing:border-box;">

<div class="no-print" style="text-align:right; margin-bottom:15px;">
<a href="Ruter.php?stranica=parametarskaStampaReklamacija" style="color:#003366; font-size:12px; margin-right:15px;">Nazad na parametarsku štampu</a>
<button type="button" onclick="window.print();" style="padding:6px 16px; font-size:12px; cursor:pointer;">ŠTAMPAJ</button>
</div>

<div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px double #003366; padding-bottom:15px; margin-bottom:12px;">
<div style="flex:1;">
<h1 style="font-size:20px; font-weight:bold; color:#003366; margin:0 0 10px 0; letter-spacing:0.5px;">
ZAPISNIK O REKLAMACIJI DRUŠTVENIH IGARA
</h1>
<p style="font-size:12px; color:#333; margin:0; line-height:1.5;">
Dokument za evidentiranje neispravnih ili oštećenih društvenih igara koje se reklamiraju dobavljaču.
</p>
</div>
<div style="font-size:14px; font-weight:bold; color:#003366; padding-top:4px; white-space:nowrap; margin-left:20px;">
REKLAMACIJA
</div>
</div>

<?php
if ($reklamacija == null) {
    echo "<p style=\"font-size:16px; color:#8B0000; text-align:center; padding:30px;\">";
    echo "Reklamacija sa unetim brojem nije pronađena.";
    echo "</p>";
} else {

    echo "<div style=\"margin-bottom:22px;\">";
    echo "<h2 style=\"font-size:13px; font-weight:bold; color:#003366; background:#E8F4FC; padding:7px 12px; margin:0 0 10px 0; border-left:4px solid #003366;\">01 PODACI O REKLAMACIJI</h2>";
    echo "<table style=\"width:100%; border-collapse:collapse; font-size:12px;\">";
    echo "<tr><td style=\"width:35%; padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Broj reklamacije</td><td style=\"padding:7px 8px; border:1px solid #ccc;\">".htmlspecialchars($reklamacija['BrojReklamacije'])."</td></tr>";
    echo "<tr><td style=\"padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Datum reklamacije</td><td style=\"padding:7px 8px; border:1px solid #ccc;\">".htmlspecialchars($reklamacija['DatumReklamacije'])."</td></tr>";
    echo "<tr><td style=\"padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Dobavljač</td><td style=\"padding:7px 8px; border:1px solid #ccc;\">".htmlspecialchars($reklamacija['Dobavljac'])."</td></tr>";
    echo "<tr><td style=\"padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Napomena</td><td style=\"padding:7px 8px; border:1px solid #ccc;\">".htmlspecialchars($reklamacija['Napomena'])."</td></tr>";
    echo "</table>";
    echo "</div>";

    echo "<div style=\"margin-bottom:22px;\">";
    echo "<h2 style=\"font-size:13px; font-weight:bold; color:#003366; background:#E8F4FC; padding:7px 12px; margin:0 0 10px 0; border-left:4px solid #003366;\">02 STAVKE REKLAMACIJE</h2>";
    echo "<table style=\"width:100%; border-collapse:collapse; font-size:11px;\">";
    echo "<tr style=\"background:#003366; color:white;\">";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366; text-align:center;\">Stavka</th>";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366;\">Društvena igra</th>";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366; text-align:right;\">Cena po komadu</th>";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366; text-align:center;\">Količina</th>";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366;\">Razlog reklamacije</th>";
    echo "<th style=\"padding:7px 6px; border:1px solid #003366; text-align:right;\">Ukupno</th>";
    echo "</tr>";

    $ukupnoReklamacija = 0;
    $brojStavki = 0;

    while ($stavka = mysqli_fetch_assoc($rezultatStavke)) {
        $brojStavki++;
        $ukupnoReklamacija += $stavka['Ukupno'];
        $bg = ($brojStavki % 2 == 0) ? "#f5f5f5" : "#ffffff";

        echo "<tr style=\"background:".$bg.";\">";
        echo "<td style=\"padding:6px; border:1px solid #ccc; text-align:center;\">".$brojStavki."</td>";
        echo "<td style=\"padding:6px; border:1px solid #ccc;\">".htmlspecialchars($stavka['Naziv'])." (".htmlspecialchars($stavka['SifraIgre']).")</td>";
        echo "<td style=\"padding:6px; border:1px solid #ccc; text-align:right;\">".htmlspecialchars($stavka['Cena'])."</td>";
        echo "<td style=\"padding:6px; border:1px solid #ccc; text-align:center;\">".htmlspecialchars($stavka['Kolicina'])."</td>";
        echo "<td style=\"padding:6px; border:1px solid #ccc;\">".htmlspecialchars($stavka['RazlogReklamacije'])."</td>";
        echo "<td style=\"padding:6px; border:1px solid #ccc; text-align:right;\">".htmlspecialchars($stavka['Ukupno'])."</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";

    echo "<div style=\"margin-bottom:30px;\">";
    echo "<h2 style=\"font-size:13px; font-weight:bold; color:#003366; background:#E8F4FC; padding:7px 12px; margin:0 0 10px 0; border-left:4px solid #003366;\">03 REKAPITULACIJA</h2>";
    echo "<table style=\"width:60%; border-collapse:collapse; font-size:12px;\">";
    echo "<tr><td style=\"padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Ukupan broj stavki</td><td style=\"padding:7px 8px; border:1px solid #ccc; font-weight:bold;\">".$brojStavki."</td></tr>";
    echo "<tr><td style=\"padding:7px 8px; border:1px solid #ccc; background:#f9f9f9; font-weight:bold;\">Ukupna vrednost reklamacije</td><td style=\"padding:7px 8px; border:1px solid #ccc; font-weight:bold;\">".$ukupnoReklamacija."</td></tr>";
    echo "</table>";
    echo "</div>";

    echo "<div style=\"border-top:1px solid #ccc; padding-top:18px; font-size:12px; line-height:1.8;\">";
    echo "<div><b>Reklamaciju evidentirao:</b> ".htmlspecialchars($reklamacija['ReklamacijuEvidentirao'])."</div>";
    echo "<div><b>Datum evidentiranja:</b> ".htmlspecialchars(isset($reklamacija['DatumEvidentiranja']) ? $reklamacija['DatumEvidentiranja'] : '')."</div>";
    echo "</div>";
}
?>

</div>
