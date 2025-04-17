<?php
/*
Oligowizard API PHP Wrapper
(C) OLIGOWIZARD LTD
VERSION 1.0
2025
A lightweight PHP wrapper for sending requests to the Oligowizard API. Includes examples for authentication, sequence queries, and result parsing.
https://github.com/Oligowizard/oligowizard-api-wrapper
*/

// Set authentication tokens (ACTION REQUIRED)
$API_KEY = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"; // Treat your API key like a password - this key links your requests to your account
$CF_client_id = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$CF_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

// Set HTTP headers
$URL = "https://api.oligowizard.app";
$headers = [
    "API-Key: $API_KEY",
    "Content-Type: application/json",
    "CF-Access-Client-Id: $CF_client_id",
    "CF-Access-Client-Secret: $CF_secret"
];

function test_connection($URL, $headers) {
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode === 200) {
        return true;
    } else {
        return false;
    }
}

function advanced($sequence, $A260 = 1.0, $three_prime = "OH", $five_prime = "OH", $five_is_PS = "FALSE", $Na_conc = 50, $K_conc = 0, $Mg_conc = 0, $URL, $headers) {
    $full_URL = $URL . "/advanced";
    $payload = json_encode([
        "sequence" => $sequence,
        "three_prime" => $three_prime,
        "five_prime" => $five_prime,
        "A260" => $A260,
        "five_is_PS" => $five_is_PS,
        "Na_conc" => $Na_conc,
        "K_conc" => $K_conc,
        "Mg_conc" => $Mg_conc
    ]);

    $ch = curl_init($full_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data !== null) {
        return $data;
    } else {
        echo $httpcode;
        return false;
    }
}

function convert($sequence, $input_code, $output_code, $URL, $headers) {
    $full_URL = $URL . "/convert";

    $ribose_mods = [
        "DNA" => 0,
        "RNA" => 1,
        "LNA" => 2,
        "MOE" => 3,
        "OMe" => 4,
        "2'F" => 5
    ];

    $input_code = $ribose_mods[$input_code];
    $output_code = $ribose_mods[$output_code];

    $payload = json_encode([
        "sequence" => $sequence,
        "input_code" => $input_code,
        "output_code" => $output_code
    ]);

    $ch = curl_init($full_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data !== null) {
        return $data;
    } else {
        echo $httpcode;
        return false;
    }
}

function structure($sequence, $scale = 0.45, $size = 12, $width = 1, $face = 96, $filename = null, $URL, $headers) {
    $full_URL = $URL . "/structure";

    $payload = json_encode([
        "sequence" => $sequence,
        "scale" => $scale,
        "size" => $size,
        "width" => $width,
        "face" => $face
    ]);

    $ch = curl_init($full_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers_raw = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode === 200) {
        if (!$filename) {
            if (preg_match('/filename=([^\s;]+)/', $headers_raw, $matches)) {
                $filename = $matches[1];
            } else {
                $filename = "oligo_structure.cdxml";
            }
        }

        if (file_put_contents($filename, $body)) {
            return $filename;
        } else {
            echo "failed to retrieve structure file";
            return false;
        }
    } else {
        echo $httpcode;
        return false;
    }
}


// === EXAMPLE ===
if (test_connection($URL, $headers)) {

    $example_sequence = "tcactttcataatgctgg";

    $nus_moe = convert($example_sequence, "DNA", "MOE", $URL, $headers)["output"];

    $example_query = advanced($nus_moe, 1.0, "OH", "OH", "FALSE", 50, 0, 0, $URL, $headers);

    echo $example_query["molext"] . "\n";
    echo $example_query["mass3"] . " g/mol " . $example_query["mass3_text"] . "\n";

    structure($nus_moe, 0.45, 12, 1, 96, "Nusinersen_structure.cdxml", $URL, $headers);

} else {
    echo "Connection Error\n";
}
?>
