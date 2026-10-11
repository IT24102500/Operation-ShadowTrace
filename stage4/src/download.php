<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}

$file = "../files/payload.zip";

if (file_exists($file)) {
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=payload.zip");
    header("Content-Length: " . filesize($file));
    readfile($file);
    exit;
} else {
    echo "File not found.";
}
?>