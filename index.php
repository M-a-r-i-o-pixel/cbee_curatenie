<?php
session_start();

// DB settings
$host = "sql200.infinityfree.com";
$dbname = "if0_40320594_curatienie_cbee";
$dbuser = "if0_40320594";
$dbpass = "imoral111";

// Connect to DB
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) die("Eroare DB: " . $conn->connect_error);

// Function: Generate UNIQUE random date in current month
function generateUniqueRandomDate($conn) {
    $currentYear = date("Y");
    $currentMonth = date("m");

    // Get last day of the current month
    $lastDay = date("t");

    do {
        $randomDay = rand(1, $lastDay);
        $randomDate = "$currentYear-$currentMonth-" . str_pad($randomDay, 2, "0", STR_PAD_LEFT);

        // Check uniqueness
        $check = $conn->prepare("SELECT id FROM users WHERE data = ?");
        $check->bind_param("s", $randomDate);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
    } while ($exists);

    return $randomDate;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nume_complet = trim($_POST['nume_complet']);
    $parola = $_POST['parola'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE nume_complet = ?");
    $stmt->bind_param("s", $nume_complet);
    $stmt->execute();
    $result = $stmt->get_result();
    $today = date("Y-m-d");

    if ($result->num_rows > 0) {
        // User exists → verify password
        $user = $result->fetch_assoc();

        if (password_verify($parola, $user['parola'])) {
            $_SESSION['nume_complet'] = $nume_complet;

            // Update date if expired
            if ($user['data'] < $today) {
                $newDate = generateUniqueRandomDate($conn);
                $update = $conn->prepare("UPDATE users SET data = ? WHERE id = ?");
                $update->bind_param("si", $newDate, $user['id']);
                $update->execute();
                $user['data'] = $newDate;
            }

            $_SESSION['data'] = $user['data'];
            header("Location: main.php");
            exit();
        } else {
            $error = "Parolă incorectă!";
        }
    } else {
        // User does not exist → create new
        $hashedPass = password_hash($parola, PASSWORD_DEFAULT);
        $randomDate = generateUniqueRandomDate($conn);

        $insert = $conn->prepare("INSERT INTO users (nume_complet, parola, data) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $nume_complet, $hashedPass, $randomDate);

        if ($insert->execute()) {
            $_SESSION['nume_complet'] = $nume_complet;
            $_SESSION['data'] = $randomDate;
            header('Location: main.php');
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
