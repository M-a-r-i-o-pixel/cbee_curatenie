<?php
session_start();
if (!isset($_SESSION['nume_complet'])) {
    header("Location: index.php");
    exit();
}

// Database settings
$host = "sql200.infinityfree.com";
$dbname = "if0_40320594_curatienie_cbee";
$dbuser = "if0_40320594";
$dbpass = "imoral111";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$search = "";
$query = "SELECT nume_complet, data FROM users";
$params = [];

if (!empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $query .= " WHERE nume_complet LIKE ?";
}

$query .= " ORDER BY data ASC";
$stmt = $conn->prepare($query);

if (!empty($search)) {
    $like = "%$search%";
    $stmt->bind_param("s", $like);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orar Curățenie</title>
<style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #f7f9fa;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #004d40;
    margin-bottom: 10px;
}

.search-box {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.search-box input {
    padding: 10px;
    width: 250px;
    border-radius: 8px 0 0 8px;
    border: 1px solid #00796b;
    outline: none;
    font-size: 1rem;
}

.search-box button {
    padding: 10px 20px;
    border: none;
    background: #00796b;
    color: #fff;
    font-size: 1rem;
    border-radius: 0 8px 8px 0;
    cursor: pointer;
}

.search-box button:hover {
    background: #009688;
}

/* Responsive table */
.table-container {
    overflow-x: auto;
}

table {
    border-collapse: collapse;
    width: 100%;
    max-width: 900px;
    margin: auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

thead {
    background: #004d40;
    color: #fff;
}

th, td {
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #e1e1e1;
}

tr:hover {
    background-color: #e6f3f1;
}

@media (max-width: 600px) {
    th, td {
        padding: 10px;
        font-size: 0.9rem;
    }
}
</style>
</head>
<body>

<h2>Orar Curățenie CBEE</h2>

<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Caută nume..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Caută</button>
    </form>
</div>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Nume complet</th>
            <th>Dată programată</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nume_complet']); ?></td>
            <td><?php echo htmlspecialchars($row['data']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

</body>
</html>

<?php $conn->close(); ?>
