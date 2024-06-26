<?php
session_start();
require_once 'db.php'; // inclure le fichier de connexion
require_once 'google/vendor/autoload.php'; // inclure l'autoloader de Google API Client

$register_error = ''; // Message d'erreur d'enregistrement initialisé à vide

// Affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}

// Check if the user submitted the Google Sign-In response
if (isset($_POST['google_token'])) {
    $client = new Google_Client(['client_id' => '365654958864-vkrkqatdu7h88b7frge4cjnhl6p38b9j.apps.googleusercontent.com']);
    $payload = $client->verifyIdToken($_POST['google_token']);
    if ($payload) {
        $email = $payload['email'];
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            echo json_encode(['error' => 'Email déjà enregistré. Veuillez vous connecter.']);
        } else {
            // Enregistrement de l'utilisateur
            $prenom = $payload['given_name'];
            $nom = $payload['family_name'];
            $password = password_hash($payload['sub'], PASSWORD_BCRYPT); // Hash the Google ID as password

            $stmt = $conn->prepare("INSERT INTO utilisateurs (email, prenom, nom, mdp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $prenom, $nom, $password);
            if ($stmt->execute()) {
                $_SESSION['user'] = ['email' => $email, 'prenom' => $prenom, 'nom' => $nom];
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Erreur lors de l\'enregistrement.']);
            }
            $stmt->close();
        }
    } else {
        echo json_encode(['error' => 'Erreur de vérification du jeton Google.']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    if ($stmt === false) {
        $register_error = "Erreur de connexion à la base de données.";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user) {
            $register_error = "Email déjà enregistré. Veuillez vous connecter.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO utilisateurs (prenom, nom, email, mdp) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $prenom, $nom, $email, $hashed_password);
                if ($stmt->execute()) {
                    $_SESSION['user'] = ['email' => $email, 'prenom' => $prenom, 'nom' => $nom];
                    header('Location: index.php');
                    exit();
                } else {
                    $register_error = "Erreur lors de l'enregistrement.";
                }
            } else {
                $register_error = "Erreur de préparation de la requête.";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeEmploi - Enregistrement</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="min-h-screen flex items-center justify-center">
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
        <div class="md:w-1/2 px-8 md:px-16">
            <h2 class="font-bold text-2xl text-[#292524]">Enregistrement</h2>
            <p class="text-xs mt-4 text-[#292524]">Enregistrez-vous ci-dessous</p>
            <?php if ($register_error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p><?php echo $register_error; ?></p>
                </div>
            <?php endif; ?>
            <form action="" method="post" class="flex flex-col gap-4">
                <input class="p-2 mt-8 rounded-xl border" type="text" name="prenom" placeholder="Prénom" required>
                <input class="p-2 mt-2 rounded-xl border" type="text" name="nom" placeholder="Nom" required>
                <input class="p-2 mt-2 rounded-xl border" type="email" name="email" placeholder="Email" required>
                <div class="relative">
                    <input class="p-2 mt-2 rounded-xl border w-full" type="password" name="password" placeholder="Mot de passe" required>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </div>
                <button type="submit" name="submit" class="bg-[#292524] rounded-xl text-white py-2 hover:scale-105 duration-300">S'enregistrer</button>
            </form>

            <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                <hr class="border-gray-400">
                <p class="text-center text-sm">OU</p>
                <hr class="border-gray-400">
            </div>

            <div id="g_id_onload" data-client_id="365654958864-vkrkqatdu7h88b7frge4cjnhl6p38b9j.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse"></div>
            <div class="mt-4 g_id_signin" data-type="standard" data-shape="rectangular" data-theme="filled_blue" data-text="signin_with" data-size="large" data-logo_alignment="left"></div>

            <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                <hr class="border-gray-400">
                <p class="text-center text-sm">Espace Professionel</p>
                <hr class="border-gray-400">
            </div>
            <button class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 duration-300 text-[#292524]">
                <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"/></svg>
                <a href="pro.php">Espace Professionnel</a>
            </button>
            <div class="mt-5 text-xs border-b border-[#292524] py-4 text-[#292524]">
                <a href="#">Mot de passe perdu ?</a>
            </div>
            <div class="mt-3 text-xs flex justify-between items-center text-[#292524]">
                <p>Déjà un compte ?</p>
                <a href="connexion.php">
                    <button class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">Connexion</button>
                </a>
            </div>
        </div>

        <div class="md:block hidden w-1/2">
            <img class="rounded-2xl" src="img/bannerr.jpeg">
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('.bi-eye').click(function () {
            $(this).toggleClass('bi-eye-slash');
            var input = $($(this).prev());
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    });

    function handleCredentialResponse(response) {
        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: {
                google_token: response.credential
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    alert(data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }
</script>

<?php include 'footer.php'; ?>

</body>
</html>


