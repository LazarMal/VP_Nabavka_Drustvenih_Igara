
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="sr-RS" xml:lang="sr-RS">
<meta charset="UTF-8">
<head>
<title>ТФ М Пупин Зрењанин</title>
<meta charset="UTF-8">
<!-----STIL PRIKAZA CSS---->
<!-----<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">--->
<!----- POSTAVLJEN U PHP DA BI SE ODMAH VIDELA PROMENA, A NE DA VUCE IZ KESIRANOG FOLDERA U BROWSERU---->
<?php include 'css/stil.php';?>
</head>
<body>

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0">

<!-------------------------- ZAGLAVLJE ------->
<?php include 'delovi/zaglavljewelcome.php';?>


<!-------------------------- DONJI DEO  ------->
<tr>
<td style="width:10%;">
</td>

<!------------------------------------------------------------------------------------------->
<!---------------------- SREDINA DONJEG DELA SA SADRZAJEM pocinje ovde ---------------------->
<td align="center" valign="middle"> 
<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#003366">
<tr>
<td style="width:1%;">
</td>

<td style="width:20%;padding:0" cellspacing="0" cellpadding="0" border="0" valign="top">

<?php include 'delovi/menilevoadmin.php';?>

</td>

<td style="width:2%;">
</td>

<td style="padding:0" cellspacing="0" cellpadding="0" border="0" valign="top">

<!------- GLAVNI SADRZAJ desno ----------->  
<?php include 'delovi/desnowelcome.php';?>
</td>

<td style="width:2%;">
</td>

</tr>
</table>

</td>
<!---------------------- SADRZAJ zavrsava ovde ---------------------->

<td style="width:10%;">
</td>
</tr>
<!---------------------- DONJI DEO zavrsava ovde ---------------------->


<tr>
<td style="width:10%;">
</td>
<td align="center" valign="middle" > 
</td>
<td style="width:10%;">
</td>
</tr>
<!--- DONJI DEO sa donjom ivicom zavrsava ovde  ------->


<!-- footer panel starts here -->

<?php include 'delovi/footer.php';?>

</table>

</body>
</html>