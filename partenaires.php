<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeEmploi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            transition: background 0.3s ease;
        }
        .card:hover {
            background: rgba(255, 255, 255, 0.9);
        }
        .font-inter {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>

<?php
include 'navbar.php';

include 'db.php';

$stmt = $conn->prepare("SELECT * FROM professionnels");
$stmt->execute();
$result = $stmt->get_result();
$professionnels = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<body class="bg-gray-100 p-6">
<div class="mt-16 mb-16 max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg fade-in card-transparent">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold mb-4 fade-in">Des partenariats puissants</h2>
        <p class="text-xl font-inter fade-in">Découvrez nos partenaires de confiance. Nous travaillons avec les plus grands leaders de l'industrie pour vous offrir les meilleurs services.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center justify-center p-4 bg-orange-100 rounded-lg fade-in card">
            <img src="img/auchan.png" alt="" class="h-32">
        </div>
        <div class="flex items-center justify-center p-4 rounded-lg border fade-in card">
            <img src="img/strava.png" alt="" class="h-32">
        </div>
        <div class="flex items-center justify-center p-4 rounded-lg border fade-in card">
            <img src="img/carrefour.png" alt="" class="h-32">
        </div>
        <div class="flex items-center justify-center p-4 bg-orange-100 rounded-lg fade-in card">
            <img src="img/lidl.png" alt="" class="h-32">
        </div>
    </div>
</div>

<style>
    .card-transparent {
        background-color: rgba(255, 255, 255, 0.4); /* 80% opacity */
        border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border to highlight the card edges */
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add('visible');
            }, index * 200);
        });
    });
</script>
</body>

<?php include 'footer.php'?>
</body>
</html>