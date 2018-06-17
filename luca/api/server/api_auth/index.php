<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

login();

$result = $mysqli->query("SELECT * FROM api");

$array = [];

$out = ["api_generate" => "http://api.ta-soest.nl/api_auth/gen.php"];
array_push($array, $out);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $api_key = $row["api_key"];
        $api_token = $row["api_token"];
        $api_name = $row["api_name"];
        $api_revoke = 'http://api.ta-soest.nl/api_auth/revoke.php?api_key=' . $api_key;
        $out = ["api_name" => $api_name, "api_key" => $api_key, "api_token" => $api_token, "api_revoke" => $api_revoke];
        array_push($array, $out);
    }
}

echo json_encode($array);
