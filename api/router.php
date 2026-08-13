<?php
header("Content-Type: application/json; charset=UTF-8");

$akcija = isset($_GET['akcija']) ? $_GET['akcija'] : "";

switch ($akcija) {

    case "igre":
        require "igre.php";
        break;

    case "igra":
        require "igra.php";
        break;

    default:
        echo json_encode(array(
            "poruka" => "Nepoznata REST akcija",
            "dozvoljene_akcije" => array(
                "igre",
                "igra"
            )
        ), JSON_UNESCAPED_UNICODE);
        break;
}
?>
