<?php
session_start();

// Setări DB
$host = "sql200.infinityfree.com";
$dbname = "if0_40320594_curatienie_cbee";
$dbuser = "if0_40320594";
$dbpass = "imoral111";

// Conexiune DB
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Eroare DB: " . $conn->connect_error);
}

// ----------------------------------------------------------
// 🔥 Funcție pentru găsirea primei date libere începând de azi
// ----------------------------------------------------------
function generateNextDate($conn) {
    $date = date("Y-m-d"); // Start: azi

    while (true) {
        $check = $conn->prepare("SELECT id FROM users WHERE data = ?");
        $check->bind_param("s", $date);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;

        if (!$exists) return $date;

        $date = date("Y-m-d", strtotime($date . " +1 day"));
    }
}

// ----------------------------------------------------------------
// 🔥 NEW: Actualizare AUTOMATĂ pentru TOȚI utilizatorii cu date expirate
// ----------------------------------------------------------------
function updateAllExpiredDates($conn) {
    $today = date("Y-m-d");

    // Selectăm toți userii cu date în trecut
    $query = $conn->prepare("SELECT id, data FROM users WHERE data < ?");
    $query->bind_param("s", $today);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        $newDate = generateNextDate($conn);

        $up = $conn->prepare("UPDATE users SET data = ? WHERE id = ?");
        $up->bind_param("si", $newDate, $row["id"]);
        $up->execute();
    }
}

// 🔥 Executăm actualizarea la fiecare acces/login
updateAllExpiredDates($conn);

// ---------------------------------------------------------------
// 🔥 Procesare Login / Creare Cont
// ---------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nume_complet = trim($_POST["nume_complet"]);
    $parola = $_POST["parola"];
    $today = date("Y-m-d");

    // verificăm dacă userul există
    $stmt = $conn->prepare("SELECT * FROM users WHERE nume_complet = ?");
    $stmt->bind_param("s", $nume_complet);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // LOGIN
        $user = $result->fetch_assoc();

        if (!password_verify($parola, $user["parola"])) {
            $error = "Parolă incorectă!";
        } else {
            $_SESSION["nume_complet"] = $user["nume_complet"];

            // Asigurăm dată validă (deja actualizată global)
            $validDate = $user["data"];
            if ($validDate < $today) {
                $validDate = generateNextDate($conn);
                $up = $conn->prepare("UPDATE users SET data=? WHERE id=?");
                $up->bind_param("si", $validDate, $user["id"]);
                $up->execute();
            }

            $_SESSION["data"] = $validDate;

            header("Location: main.php");
            exit();
        }

    } else {
        // CREARE CONT NOU
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
<title>Autentificare</title>
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
