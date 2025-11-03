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

// Take current user data from DB
$userQuery = $conn->prepare("SELECT * FROM users WHERE nume_complet = ?");
$userQuery->bind_param("s", $_SESSION['nume_complet']);
$userQuery->execute();
$currentUser = $userQuery->get_result()->fetch_assoc();

$currentUserId = $currentUser['id'];
$currentUserDate = $currentUser['data'];

// Accept swap
if (isset($_GET['accept'])) {
    $id = intval($_GET['accept']);
    $q = $conn->prepare("SELECT * FROM schimburi WHERE id=? AND to_user_id=? AND status='pending'");
    $q->bind_param("ii", $id, $currentUserId);
    $q->execute();
    $proposal = $q->get_result()->fetch_assoc();

    if ($proposal) {
        // get from user date
        $qFrom = $conn->prepare("SELECT id, data FROM users WHERE id=?");
        $qFrom->bind_param("i", $proposal['from_user_id']);
        $qFrom->execute();
        $fromUser = $qFrom->get_result()->fetch_assoc();

        // swap dates!!
        $update1 = $conn->prepare("UPDATE users SET data=? WHERE id=?");
        $update1->bind_param("si", $fromUser['data'], $currentUserId);
        $update1->execute();

        $update2 = $conn->prepare("UPDATE users SET data=? WHERE id=?");
        $update2->bind_param("si", $currentUserDate, $fromUser['id']);
        $update2->execute();

        // mark as accepted
        $updateSwap = $conn->prepare("UPDATE schimburi SET status='accepted' WHERE id=?");
        $updateSwap->bind_param("i", $id);
        $updateSwap->execute();

        header("Location: schimburi.php");
        exit();
    }
}

// Decline swap
if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $up = $conn->prepare("UPDATE schimburi SET status='declined' WHERE id=? AND to_user_id=?");
    $up->bind_param("ii", $id, $currentUserId);
    $up->execute();
    header("Location: schimburi.php");
    exit();
}

// Create a new proposal
if (isset($_POST['target_user'])) {
    $targetId = intval($_POST['target_user']);

    if ($targetId != $currentUserId) {
        $ins = $conn->prepare("INSERT INTO schimburi (from_user_id, to_user_id) VALUES (?, ?)");
        $ins->bind_param("ii", $currentUserId, $targetId);
        $ins->execute();
    }
    header("Location: schimburi.php");
    exit();
}

// Get pending proposals to this user
$pending = $conn->prepare("
    SELECT s.id, u.nume_complet 
    FROM schimburi s 
    JOIN users u ON s.from_user_id = u.id
    WHERE s.to_user_id=? AND s.status='pending'
");
$pending->bind_param("i", $currentUserId);
$pending->execute();
$pendingRequests = $pending->get_result();

// Get all other users to propose swaps to
$users = $conn->prepare("SELECT id, nume_complet, data FROM users WHERE id != ?");
$users->bind_param("i", $currentUserId);
$users->execute();
$allUsers = $users->get_result();
?>


<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<title>Schimburi Curățenie</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: Arial; margin: 0; padding: 20px;background: #eef5f5; }
h2 { text-align:center; color: #004d40; }
.box { background:white; padding: 15px; border-radius:12px; margin-bottom:20px; }
button, select { padding:10px; border-radius:8px; border:none; background:#00796b; color:white; cursor:pointer; }
button:hover { background: #009688; }
.user-box { display:flex; justify-content:space-between; margin:8px 0; background:#e5f6f3; padding:10px; border-radius:8px; }
.link-btn { background:none; border:none; color:#00695c; cursor:pointer; text-decoration:underline; }
@media(max-width:600px) {
  .user-box { flex-direction:column; text-align:center; gap:10px; }
}
</style>
</head>
<body>

<h2>Schimburi de Date</h2>

<div class="box">
    <h3>Propuneri primite</h3>
    <?php if ($pendingRequests->num_rows == 0): ?>
        <p>Nu ai propuneri momentan 👌</p>
    <?php endif; ?>

    <?php while($row = $pendingRequests->fetch_assoc()): ?>
    <div class="user-box">
        <span><?php echo htmlspecialchars($row['nume_complet']); ?> vrea schimb!</span>
        <div>
            <a href="?accept=<?php echo $row['id']; ?>"><button>Acceptă</button></a>
            <a href="?decline=<?php echo $row['id']; ?>"><button style="background:#b71c1c;">Refuză</button></a>
        </div>
    </div>
    <?php endwhile; ?>
</div>


<div class="box">
    <h3>Propune unui coleg</h3>
    <form method="POST">
        <select name="target_user" required>
            <option value="">Alege un nume</option>
            <?php while($row = $allUsers->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['nume_complet']); ?> — <?php echo $row['data']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Propune schimb</button>
    </form>
</div>

</body>
</html>
<?php $conn->close(); ?>
