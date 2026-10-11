<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Shadow Collective</title>
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .box {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        h1 { color: #58a6ff; }
        .flag {
            background-color: #238636;
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            margin: 20px 0;
            word-break: break-all;
        }
        a {
            color: #58a6ff;
            text-decoration: none;
            font-size: 16px;
        }
        a:hover { text-decoration: underline; }
        .logout {
            display: inline-block;
            margin-top: 20px;
            color: #f85149;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Broker Admin Panel</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></p>

        <h3>Stage 4 Flag:</h3>
        <div class="flag">SHADOW{web_8c3d7a}</div>

        <h3>Classified Payload:</h3>
        <p>Download the encrypted archive for Stage 5 analysis.</p>
        <p><a href="download.php">Download payload.zip</a></p>
        <p><strong>Archive Password:</strong> raven77</p>

        <a class="logout" href="index.php">Logout</a>
    </div>
</body>
</html>