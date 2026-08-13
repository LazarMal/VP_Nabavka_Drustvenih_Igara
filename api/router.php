<?php
header("Content-Type: application/json; charset=UTF-8");

$akcija = isset($_GET['akcija']) ? $_GET['akcija'] : "";

switch ($akcija) {

    case "igre":
        require __DIR__ . '/igre.php';
        break;

    case "igra":
        require __DIR__ . '/igra.php';
        break;

    case "proveraJedinstvenosti":
        require __DIR__ . '/proveraJedinstvenosti.php';
        break;

    default:
        http_response_code(400);
        echo json_encode(array(
            "poruka" => "Nepoznata REST akcija",
            "dozvoljene_akcije" => array(
                "igre",
                "igra",
                "proveraJedinstvenosti"
            )
        ), JSON_UNESCAPED_UNICODE);
        break;
}
?>
