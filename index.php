<?php
include 'db.php';
$ip = $_SERVER['REMOTE_ADDR'];

// Enregistrer la date et l'heure de la visite
$date_visite = date('Y-m-d H:i:s');

// Insérer les informations de visite dans la base de données
$stmt = $conn->prepare("INSERT INTO visites (ip, date_visite) VALUES (?, ?)");
$stmt->bind_param("ss", $ip, $date_visite);
$stmt->execute();
$stmt->close();
?>
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
        .scrolling-wrapper {
            display: flex;
            flex-wrap: nowrap;
            overflow: hidden;
        }
        .scrolling-wrapper div {
            flex: 0 0 auto;
        }
        .animate-scroll {
            display: flex;
            animation: scroll 30s linear infinite;
        }
        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .card-transparent {
            background-color: rgba(255, 255, 255, 0.4) !important; /* 60% opacity */
            border: 1px solid rgba(0, 0, 0, 0.1) !important; /* Subtle border to highlight the card edges */
        }

        @keyframes slide-in-left {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .slide-in-left {
            animation: slide-in-left 1s ease-out;
        }
    </style>
</head>
<body>
<?php
include 'navbar.php';
?>
<div class="container mx-auto flex flex-col md:flex-row items-center justify-center h-auto md:h-screen p-4 mt-4 md:mt-0">
    <div class="text-section w-full md:w-1/2 text-center md:text-left">
        <div class="relative w-full md:w-max overflow-hidden slide-in-left">
            <h1 class="font-bold text-4xl animate-typewriter">FreeEmploi le</h1>
            <h1 class="font-bold text-4xl animate-typewriter">Meilleur de l'emploi</h1>
            <h1 class="font-bold text-4xl animate-typewriter">En ligne !</h1>
        </div>
    </div>
    <div class="image-section w-full md:w-1/2">
        <img class="h-auto max-w-full rounded-lg mx-auto slide-in-left" src="img/4.jpg" alt="image description">
    </div>
</div>

<h1 class="text-center text-4xl font-bold mt-12 slide-in-left">Nos missions</h1>

<div class="container mx-auto flex flex-wrap justify-center items-center mt-8 slide-in-left">
    <?php
    $imagesAndTitles = [
        '1.jpg' => 'Simple',
        '2.jpg' => 'Rapide',
        '3.jpg' => 'Sûr',
        '4.jpg' => 'Efficace'
    ];

    $count = 0;
    foreach ($imagesAndTitles as $image => $title) {
        // Vérifier si l'écran est plus petit et si nous avons déjà affiché trois cartes
        if (count($imagesAndTitles) > 3 && $count >= 3) {
            break; // Arrêter la boucle si nous avons déjà affiché trois cartes sur un écran plus petit
        }

        echo '
        <div class="w-1/4 bg-center bg-cover rounded-lg h-48 mx-4 overflow-hidden transition-transform transform hover:-translate-y-2" style="background-image: url(\'img/' . $image . '\');">
            <div class="flex items-center justify-center h-full bg-black bg-opacity-50 rounded-lg">
                <div class="text-white text-xl font-bold">' . $title . '</div>
            </div>
        </div>
        ';
        $count++;
    }
    ?>
</div>

<h1 class="text-center text-4xl font-bold mt-12 slide-in-left">Nos partenaires</h1>

<!-- Section de défilement horizontal infini -->
<div class="overflow-hidden mt-12">
    <div class="scrolling-wrapper animate-scroll">
        <?php
        $partners = [
            'carrefour.png',
            'superu.png',
            'auchan.png',
            'casino.png',
            'lidl.png',
        ];

        foreach ($partners as $partner) {
            echo '<div class="flex-none"><img src="img/' . $partner . '" alt="Partner" class="h-24 mx-4"></div>';
        }
        ?>
    </div>
</div>

<h1 class="text-center text-4xl font-bold mt-12 slide-in-left">Avis de nos clients</h1>
<div class="container mx-auto flex flex-wrap justify-center items-center mt-8 slide-in-left">
    <?php
    $reviews = [
        [
            'name' => 'John Doe',
            'job' => 'Sans emploi',
            'review' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut metus nec odio ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt.',
            'image' => '1.jpg'
        ],
        [
            'name' => 'Jane Doe',
            'job' => 'Boucher',
            'review' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut metus nec odio ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt.',
            'image' => '2.jpg'
        ],
        [
            'name' => 'Jack Doe',
            'job' => 'Bûcheron',
            'review' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut metus nec odio ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt. Nullam nec nunc nec libero ultricies tincidunt.',
            'image' => '3.jpg'
        ],
    ];

    foreach ($reviews as $review) {
        echo '
        <div class="card-transparent bg-white rounded-lg shadow-lg m-4 w-80">
            <div class="flex items-center p-4">
                <img src="img/' . $review['image'] . '" alt="Profile" class="h-12 w-12 rounded-full">
                <div class="ml-4">
                    <h2 class="font-bold text-lg">' . $review['name'] . '</h2>
                    <p class="text-gray-500">' . $review['job'] . '</p>
                </div>
            </div>
            <div class="review-card-body p-4">
                <p class="text-gray-600">' . $review['review'] . '</p>
            </div>
        </div>
        ';
    }
    ?>
</div>

<?php include 'footer.php'; ?>

<script src="script/js.js"></script>
</body>
</html>
