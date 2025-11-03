<?php
session_start();

// 🔧 Setări bază de date
$host = "sql200.infinityfree.com";
$dbname = "if0_40320594_curatienie_cbee";
$dbuser = "if0_40320594";
$dbpass = "imoral111";

// Conectare
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Eroare DB: " . $conn->connect_error);
}

// 🔁 Funcție generare dată corectă pentru utilizator
function generateNextDate($conn) {
    $today = date("Y-m-d");
    $currentYear = date("Y");
    $currentMonth = date("m");
    $firstDayOfMonth = "$currentYear-$currentMonth-01";

    // Ce dată maximă există deja?
    $res = $conn->query("SELECT MAX(data) AS last_date FROM users");
    $row = $res->fetch_assoc();
    $lastDate = $row['last_date'] ?? null;

    if (!$lastDate || $lastDate < $today) {
        // Dacă nu există date sau toate sunt în trecut → începem cu AZI
        $nextDate = $today;
    } else {
        // Există date viitoare → folosim următoarea zi după ele
        $nextDate = date("Y-m-d", strtotime($lastDate . " +1 day"));
    }

    // Dacă am căzut în luna următoare → restart luna curentă
    if (substr($nextDate, 5, 2) !== $currentMonth) {
        $nextDate = $firstDayOfMonth;
    }

    // Evităm duplicatele
    do {
        $stmt = $conn->prepare("SELECT id FROM users WHERE data = ?");
        $stmt->bind_param("s", $nextDate);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;

        if ($exists) {
            $nextDate = date("Y-m-d", strtotime($nextDate . " +1 day"));
            if (substr($nextDate, 5, 2) !== $currentMonth) {
                $nextDate = $firstDayOfMonth;
            }
        }
    } while ($exists);

    return $nextDate;
}

// 🔐 Login sau creare cont
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nume_complet = trim($_POST["nume_complet"]);
    $parola = $_POST["parola"];
    $today = date("Y-m-d");
    $currentMonth = date("m");

    $stmt = $conn->prepare("SELECT * FROM users WHERE nume_complet = ?");
    $stmt->bind_param("s", $nume_complet);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        // ✅ LOGIN
        $user = $userResult->fetch_assoc();
        $userMonth = substr($user["data"], 5, 2);

        if (!password_verify($parola, $user["parola"])) {
            $error = "Parolă incorectă!";
        } else {
            $_SESSION["nume_complet"] = $user["nume_complet"];

            // Dacă data e expirată sau din altă lună → actualizare
            if ($user["data"] < $today || $userMonth !== $currentMonth) {
                $newDate = generateNextDate($conn);
                $up = $conn->prepare("UPDATE users SET data=? WHERE id=?");
                $up->bind_param("si", $newDate, $user["id"]);
                $up->execute();
                $_SESSION["data"] = $newDate;
            } else {
                $_SESSION["data"] = $user["data"];
            }

            header("Location: main.php");
            exit();
        }

    } else {
        // ✅ CREARE UTILIZATOR NOU
        $hashedPass = password_hash($parola, PASSWORD_DEFAULT);
        $newDate = generateNextDate($conn);

        $insert = $conn->prepare("INSERT INTO users (nume_complet, parola, data) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $nume_complet, $hashedPass, $newDate);

        if ($insert->execute()) {
            $_SESSION["nume_complet"] = $nume_complet;
            $_SESSION["data"] = $newDate;
            header("Location: main.php");
            exit();
        } else {
            $error = "Eroare la creare cont!";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Autentificare - CBEE</title>
<link rel="stylesheet" href="login_style.css">
</head>
<body>

<div class="login-container">
    <h2>Autentificare / Înregistrare</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="nume_complet" placeholder="Nume complet" required>
        <input type="password" name="parola" placeholder="Parolă" required>
        <button type="submit">Continuă</button>
    </form>
</div>

</body>
</html>
