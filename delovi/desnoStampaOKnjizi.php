<meta charset="UTF-8">

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="white">

<tr>
<td style="width:5%;"></td>

<td align="center">
<font face="Trebuchet MS" color="darkblue" size="4px">
<b>ПОДАЦИ О КЊИЗИ</b><br/>
</font>
</td>

<td style="width:5%;"></td>
</tr>

<tr>
<td style="width:5%;"></td>

<td align="center">
<br/>
<font face="Trebuchet MS" color="darkblue" size="4px">

<?php
$URLSlike='SlikeKnjiga/'.$NazivFajlaSlike;

if ($NazivFajlaSlike != "")
{
    $URLSlike='SlikeKnjiga/'.$NazivFajlaSlike;
    echo "<img src=\"".$URLSlike."\" width=\"200\"/><br/><br/>";
}

echo "<font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">ISBN: $ISBN</font><br/>";
echo "<b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">Назив књиге: $Naziv</font><br/>";
echo "<b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">Аутор: $Autor</font><br/>";
echo "<b><font face=\"Trebuchet MS\" color:#3F4534 size=\"3px\">Жанр: $NazivZanra</font><br/>";
?>

</font>
</td>

<td style="width:5%;"></td>
</tr>
</table>