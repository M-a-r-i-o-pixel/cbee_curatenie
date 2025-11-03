<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Instrucțiuni - Curățenie CBEE</title>
<style>
body {
    margin: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: #f5f7f8;
}
.container {
    max-width: 800px;
    margin: 70px auto;
    background: #ffffff;
    padding: 25px 35px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
h1 {
    text-align: center;
    color: #004d40;
    margin-bottom: 20px;
}
p {
    line-height: 1.6;
    color: #333;
}
ul {
    margin-top: 10px;
    padding-left: 20px;
}
li {
    margin-bottom: 10px;
    font-size: 1.05rem;
}
.note {
    background: #e0f2f1;
    padding: 12px;
    border-left: 4px solid #00796b;
    border-radius: 6px;
    margin-top: 20px;
    font-size: 0.95rem;
}
.back {
    text-align: center;
    margin-top: 25px;
}
.back button {
    background-color: #00796b;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
}
.back button:hover {
    background-color: #009688;
}
</style>
</head>
<body>

<div class="container">
    <h1>Instrucțiuni de utilizare</h1>

    <p>Aplicația „Curățenie CBEE” te ajută să gestionezi zilele de curățenie într-un mod corect și organizat. Iată cum funcționează:</p>

    <ul>
        <li><strong>Autentificare / Înregistrare:</strong> Introdu numele complet și parola. Dacă nu ai cont, se va crea automat.</li>
        <li><strong>Atribuirea datei:</strong> Sistemul îți oferă o dată unică, mereu <strong>după ultima dată deja programată</strong> în baza de date.</li>
        <li><strong>Dacă data ta a expirat:</strong> Când te loghezi din nou, vei primi automat <strong>următoarea dată liberă</strong> din calendar.</li>
        <li><strong>Schimburi de date:</strong> Poți propune altor utilizatori schimbul de date și îl poți accepta/respinge.</li>
        <li><strong>Ștergerea contului:</strong> Îți poți șterge contul din pagina principală. Atenție, această acțiune este permanentă!</li>
    </ul>

    <div class="note">
        <strong>Important:</strong><br>
        - Datele altor utilizatori nu se schimbă atunci când cineva își șterge contul.<br>
        - Sistemul programează mereu <strong>în viitor</strong> pentru a nu afecta planurile celor existenți.<br>
        - Este recomandat să intri periodic în cont pentru a-ți menține data actualizată.
    </div>

    <div class="back">
        <?php if(isset($_SESSION["nume_complet"])): ?>
            <a href="main.php"><button>Înapoi</button></a>
        <?php else: ?>
            <a href="index.php"><button>Înapoi la autentificare</button></a>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
