<?php
// Placez ce code au début du fichier pour initialiser la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'db.php'; // Inclure votre fichier de connexion à la base de données

// Initialisation de la variable $photo_profil avec une image par défaut
if (isset($_SESSION['user']['profil_image']) && $_SESSION['user']['profil_image'] != '') {
    $photo_profil = $_SESSION['user']['profil_image'];
} elseif (isset($_SESSION['pro']['profil_image']) && $_SESSION['pro']['profil_image'] != '') {
    $photo_profil = $_SESSION['pro']['profil_image'];
} else {
    $photo_profil = 'img_profil/default.jpeg';
}

include 'chatbot.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Questrial&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .notification-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            border-radius: 5px;
            z-index: 1000;
        }

        .notification-menu.active {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: red;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="lg:hidden">
    <div class="container mx-auto py-10 px-4">
        <div class="flex items-center justify-between">
            <a href="index.php" class="flex items-center">
                <img src="img/logo.png" alt="logo" class="h-12 w-auto">
            </a>

            <div>
                <button id="menu-toggle" class="text-gray-900 focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden mt-4">
            <a href="index.php" class="text-gray-700 font-inter text-lg font-medium block my-4">Accueil</a>
            <a href="emplois.php" class="text-gray-700 font-inter text-lg font-medium block my-4">Nos emplois</a>
            <a href="partenaires.php" class="text-gray-700 font-inter text-lg font-medium block my-4">Nos Partenaires</a>
            <a href="contact.php" class="text-gray-700 font-inter text-lg font-medium block my-4">Contact</a>
            <?php if (isset($_SESSION['user'])) : ?>
                <a href="profil.php" class="block my-4">
                    <img src="<?php echo htmlspecialchars($photo_profil); ?>?<?php echo time(); ?>" alt="" class="rounded-full w-8 h-8">
                </a>
                <a href="like.php" class="block my-4">
                    <i class="fa-solid fa-heart text-gray-700 text-lg"></i>
                </a>
                <a href="notification.php" class="block my-4">
                    <i class="fas fa-bell text-gray-700 text-lg"></i>
                </a>
                <a href="deconnexion.php" class="block my-4">
                    <i class="fas fa-sign-out-alt text-gray-700 text-lg"></i>
                </a>
            <?php elseif (isset($_SESSION['pro'])) : ?>
                <a href="profil_pro.php" class="block my-4">
                    <img src="<?php echo htmlspecialchars($photo_profil); ?>?<?php echo time(); ?>" alt="" class="rounded-full w-8 h-8">
                </a>
                <a href="deconnexion.php" class="block my-4">
                    <i class="fas fa-sign-out-alt text-gray-700 text-lg"></i>
                </a>
            <?php else : ?>
                <a href="connexion.php" class="block my-4">
                    <i class="fas fa-user text-gray-700 text-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="hidden lg:block">
    <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center py-10 px-4 lg:px-0">
        <div class="flex items-center lg:mr-8">
            <a href="index.php" class="flex items-center">
                <img src="img/logo.png" alt="logo" class="h-18 w-40">
            </a>
        </div>
        <div class="flex flex-col lg:flex-row lg:items-center lg:flex-grow lg:justify-center lg:space-x-8 mt-6 lg:mt-0" style="margin-right: 20px;">
            <a href="index.php" class="text-gray-700 font-inter text-lg font-medium">Accueil</a>
            <a href="emplois.php" class="text-gray-700 font-inter text-lg font-medium">Nos emplois</a>
            <a href="partenaires.php" class="text-gray-700 font-inter text-lg font-medium">Nos Partenaires</a>
            <a href="contact.php" class="text-gray-700 font-inter text-lg font-medium">Contact</a>
        </div>

        <?php if (isset($_SESSION['user'])) : ?>
            <a href="profil.php" class="block mr-6 my-2">
                <img src="<?php echo htmlspecialchars($photo_profil); ?>?<?php echo time(); ?>" alt="" class="rounded-full w-8 h-8">
            </a>
            <a href="like.php" class="block mr-6 my-2">
                <i class="fa-solid fa-heart text-gray-700 text-lg"></i>
            </a>
            <a href="notification.php" class="block mr-6 my-4">
                <i class="fas fa-bell text-gray-700 text-lg"></i>
            </a>
        <?php elseif (isset($_SESSION['pro'])) : ?>
            <a href="profil_pro.php" class="block mr-6 my-2">
                <img src="<?php echo htmlspecialchars($photo_profil); ?>?<?php echo time(); ?>" alt="" class="rounded-full w-8 h-8">
            </a>
            <a href="like.php" class="block mr-6 my-2">
                <i class="fa-solid fa-heart text-gray-700 text-lg"></i>
            </a>
        <?php else : ?>
            <a href="connexion.php" class="block mr-6 my-2">
                <i class="fas fa-user text-gray-700 text-lg"></i>
            </a>
            <?php
            // Débogage pour vérifier si personne n'est connecté
            echo '<script>console.log("Personne n\'est connecté.");</script>';
            ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['user']) || isset($_SESSION['pro'])) : ?>
            <a href="deconnexion.php" class="block my-2">
                <i class="fas fa-sign-out-alt text-gray-700 text-lg"></i>
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
    $('#menu-toggle').click(function () {
        $('#mobile-menu').toggle();
    });

    $('#search-button').click(function () {
        $('.search-bar').toggle();
    });

</script>

</body>
</html>

