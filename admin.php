<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: cadmin.php");
    exit();
}


$stmt = $conn->prepare("SELECT COUNT(DISTINCT ip) AS nb_visiteurs_uniques FROM visites");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$visiteurs_uniques = $row['nb_visiteurs_uniques'];


$stmt = $conn->prepare("SELECT COUNT(*) AS nb_visiteurs_aujourd_hui FROM visites WHERE date_visite = CURDATE()");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$visiteurs_aujourdhui = $row['nb_visiteurs_aujourd_hui'];


$stmt = $conn->prepare("SELECT COUNT(*) AS nb_visiteurs_ce_mois_ci FROM visites WHERE MONTH(date_visite) = MONTH(CURDATE()) AND YEAR(date_visite) = YEAR(CURDATE())");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$visiteurs_ce_mois_ci = $row['nb_visiteurs_ce_mois_ci'];

$stmt = $conn->prepare("SELECT COUNT(*) AS nb_visiteurs_au_total FROM visites");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$visiteurs_au_total = $row['nb_visiteurs_au_total'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        .logout-button {
            background-color: #EF4444; /* Rouge Tailwind */
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="flex h-screen">

    <div class="bg-gray-800 text-white w-20 flex flex-col items-center">
        <span class="text-xs mt-4 font-medium">FreeEmploi</span>
        <a href="admin.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Travel%20and%20places/House.png" alt="House" width="25" height="25" />
        </a>
        <a href="message.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Receipt.png" alt="Receipt" width="25" height="25" />
        </a>
        <a href="archive.php" class="mt-2 p-2 hover:bg-gray-700">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Card%20Index%20Dividers.png" alt="Card Index Dividers" width="25" height="25" />
        </a>
        <a href="deconnexion.php" class="mt-auto p-2 hover:bg-gray-700 logout-button w-full flex items-center justify-center">
            <img src="https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Symbols/End%20Arrow.png" alt="End Arrow" width="25" height="25" class="mx-auto" />
        </a>
    </div>
    <div class="flex-1 flex flex-col items-center justify-center">
        <div class="text-black text-4xl">
            FreeEmploi-Board
        </div>
        <div class="mt-10 grid grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="text-2xl font-semibold">Visiteurs uniques</div>
                <div class="text-3xl font-bold"><?php echo $visiteurs_uniques; ?></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="text-2xl font-semibold">Visiteurs aujourd'hui</div>
                <div class="text-3xl font-bold"><?php echo $visiteurs_aujourdhui; ?></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="text-2xl font-semibold">Visiteurs ce mois-ci</div>
                <div class="text-3xl font-bold"><?php echo $visiteurs_ce_mois_ci; ?></div>
            </div>
        </div>
        <div class="mt-10 bg-white p-4 rounded-lg shadow-md">
            <div class="text-2xl font-semibold">Visiteurs au total</div>
            <div class="text-3xl font-bold"><?php echo $visiteurs_au_total; ?></div>
        </div>
    </div>
</div>
</body>
</html>




