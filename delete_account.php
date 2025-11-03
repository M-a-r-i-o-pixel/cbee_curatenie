<?php
session_start();

if (!isset($_SESSION['nume_complet'])) {
    header("Location: index.php");
    exit();
}

$host = "sql200.infinityfree.com";
$dbname = "if0_40320594_curatienie_cbee";
$dbuser = "if0_40320594";
$dbpass = "imoral111";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$nume_complet = $_SESSION['nume_complet'];

$stmt = $conn->prepare("DELETE FROM users WHERE nume_complet=? LIMIT 1");
$stmt->bind_param("s", $nume_complet);
$stmt->execute();

session_unset();
session_destroy();

$conn->close();

header("Location: index.php");
exit();
?>
