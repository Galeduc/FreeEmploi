<?php
session_start();

include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer et lier
    $stmt = $conn->prepare("SELECT * FROM professionnels WHERE email_pro = ?");
    if ($stmt === false) {
        $login_error = "Erreur de connexion à la base de données.";
    } else {
        $stmt->bind_param("s", $email);

        // Exécuter la requête
        $stmt->execute();
        $result = $stmt->get_result();
        $pro = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        if ($pro && password_verify($password, $pro['mdp'])) {
            $_SESSION['pro'] = $pro;
            header('Location: index.php');
            exit();
        } else {
            $login_error = "Email ou mot de passe incorrect.";
        }
    }
}

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
    </style>
<body>
<?php include 'navbar.php'; ?>

<section class="min-h-screen flex items-center justify-center">
    <!-- login container -->
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
        <!-- form -->
        <div class="md:w-1/2 px-8 md:px-16">
            <h2 class="font-bold text-2xl text-[#292524]">Espace Professionnel</h2>
            <p class="text-xs mt-4 text-[#292524]">Connectez-vous ci-dessous</p>

            <form action="" method="post" class="flex flex-col gap-4">
                <input class="p-2 mt-8 rounded-xl border" type="email" name="email" placeholder="Email Pro">
                <div class="relative">
                    <input class="p-2 rounded-xl border w-full" type="password" name="password" placeholder="Mot de passe">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                    </svg>
                </div>
                <button type="submit" name="submit"  class="bg-[#292524] rounded-xl text-white py-2 hover:scale-105 duration-300">Connexion</button>
            </form>

            <div class="mt-5 text-xs border-b border-[#292524] py-4 text-[#292524]">
                <a href="#">Mot de passe perdu ?</a>
            </div>

            <div class="mt-3 text-xs flex justify-between items-center text-[#292524]">
                <p>Pas encore de compte professionnel ?</p>
                <a href="registerpro.php">
                    <button class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">S'enregistrer</button>
                </a>
            </div>
        </div>

        <!-- image -->
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
</script>

<?php include 'footer.php'; ?>
</body>
</html>