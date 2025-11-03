<?php
session_start();
if (!isset($_SESSION['nume_complet'])) {
    header("Location: index.php");
    exit();
}

$nume_complet = $_SESSION['nume_complet'];
$data = $_SESSION['data'];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bun venit - Curățenie CBEE</title>
<style>
body {
    margin: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: #f7f9fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    width: 90%;
    max-width: 380px;
    text-align: center;
}

h2 {
    color: #004d40;
}

.date-box {
    margin: 15px 0 25px;
    background: #e6f3f1;
    padding: 12px;
    border-radius: 10px;
    font-size: 1.2rem;
    color: #004d40;
    font-weight: 600;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    font-size: 1rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.btn-nav {
    background-color: #00796b;
    color: #fff;
}
.btn-nav:hover {
    background-color: #009688;
}

.btn-delete {
    background-color: #b71c1c;
    color: #fff;
}
.btn-delete:hover {
    background-color: #d32f2f;
}
</style>
</head>
<body>

<div class="container">
    <h2>Bine ai venit, <?php echo htmlspecialchars($nume_complet); ?>!</h2>

    <div class="date-box">
        Data ta de curățenie este:<br> <strong><?php echo $data; ?></strong>
    </div>

    <a href="orar.php">
        <button class="btn-nav">Vezi Orar</button>
    </a>

    <a href="schimburi.php">
        <button class="btn-nav">Schimburi</button>
    </a>

    <a href="instructiuni.php">
        <button class="btn-nav">Instrucțiuni</button>
    </a>

    <form action="delete_account.php" method="POST"
        onsubmit="return confirm('Ești sigur că vrei să ștergi definitiv contul?')">
        <button type="submit" class="btn-delete">Șterge contul 🗑️</button>
    </form>
</div>

</body>
</html>
