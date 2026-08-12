<?php
header("Content-Type: application/json; charset=UTF-8");

$akcija = isset($_GET['akcija']) ? $_GET['akcija'] : "";

switch ($akcija) {

    case "knjige":
        require "knjige.php";
        break;

    case "knjiga":
        require "knjiga.php";
        break;

    default:
        echo json_encode(array(
            "poruka" => "Nepoznata REST akcija",
            "dozvoljene_akcije" => array(
                "knjige",
                "knjiga"
            )
        ), JSON_UNESCAPED_UNICODE);
        break;
}
?>