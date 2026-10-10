<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        $db = new PDO("sqlite:/var/www/db/users.db");

        // Intentionally vulnerable SQL query (SQL Injection)
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $db->query($query);
        $user = $result ? $result->fetch(PDO::FETCH_ASSOC) : false;

        if ($user) {
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $user["username"];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } catch (Exception $e) {
        $error = "Database error. Please run setup first.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shadow Collective - Broker Portal</title>
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background-color: #161b22;
            padding: 40px;
            border-radius: 10px;
            border: 1px solid #30363d;
            width: 320px;
            text-align: center;
        }
        h2 { color: #58a6ff; margin-bottom: 20px; }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #30363d;
            border-radius: 5px;
            background-color: #0d1117;
            color: #c9d1d9;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #238636;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover { background-color: #2ea043; }
        .error { color: #f85149; margin-top: 10px; }
        .subtitle { color: #8b949e; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Shadow Collective</h2>
        <div class="subtitle">Affiliate Broker Portal</div>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    </div>
</body>
</html>