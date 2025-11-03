<?php
session_start();
if (!isset($_SESSION['nume_complet'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Instrucțiuni - Curățenie CBEE</title>

<style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: #f7f9fa;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 850px;
    margin: auto;
    background: #ffffff;
    padding: 20px 25px;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #004d40;
}

ul {
    padding-left: 18px;
}

li {
    margin-bottom: 12px;
    font-size: 1.05rem;
}

.box {
    background: #e6f3f1;
    border-left: 6px solid #00796b;
    padding: 12px 15px;
    margin-top: 20px;
    border-radius: 10px;
}

strong {
    color: #004d40;
}
</style>
</head>
<body>

<div class="container">
    <h2>Instrucțiuni de utilizare</h2>

    <p>Bine ai venit în aplicația <strong>Curățenie CBEE</strong>! 🎉<br>
    Aici poți vedea data alocată pentru curățenie și poți face schimburi cu colegii.</p>

    <div class="box">
        <strong>➡️ Ce poți face în această aplicație?</strong>
        <ul>
            <li>✅ Vezi data ta programată pentru curățenie</li>
            <li>✅ Cauți datele colegilor în pagina <em>Orar</em></li>
                       <li>✅ Propui schimburi colegilor dacă dorești o altă dată</li>
            <li>✅ Accepți sau refuzi schimburile propuse de alții</li>
        </ul>
    </div>

    <h3 style="color:#004d40;">Cum funcționează datele:</h3>
    <ul>
        <li>La prima autentificare, sistemul îți atribuie o dată <strong>aleatorie</strong> din luna curentă.</li>
        <li>Data este unică — nimeni nu are aceeași zi ca tine.</li>
        <li>Dacă data ta a trecut, sistemul îți oferă automat o nouă zi liberă din lună.</li>
    </ul>

    <h3 style="color:#004d40;">Schimburi între colegi:</h3>
    <ul>
        <li>Poți face cereri de schimb în pagina <strong>Schimburi</strong></li>
        <li>Poți accepta sau refuza cererile primite</li>
        <li>La acceptare, datele celor doi utilizatori se schimbă automat ✅</li>
    </ul>

    <div class="box">
        ℹ️ Dacă ai întrebări sau probleme, te rugăm să contactezi responsabilul CBEE!<br>
        (Acesta nu e Mario , deci nu exista responsabil)
    </div>

</div>

</body>
</html>
