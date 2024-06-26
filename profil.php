<?php
session_start();

include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$user = $_SESSION['user'];
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $id = $_POST['id'];

    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['size'] > 0) {
        $target_dir = "img_profil/";
        $target_file = $target_dir . basename($_FILES["photo_profil"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image réelle ou une fausse image
        $check = getimagesize($_FILES["photo_profil"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }

        // Vérifier la taille du fichier
        if ($_FILES["photo_profil"]["size"] > 5000000) { // 5MB maximum
            echo "Désolé, votre fichier est trop volumineux.";
            $uploadOk = 0;
        }

        // Autoriser certains formats de fichiers
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            $uploadOk = 0;
        }

        // Vérifier si $uploadOk est défini à 0 par une erreur
        if ($uploadOk == 0) {
            echo "Désolé, votre fichier n'a pas été téléchargé.";
        } else {
            if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $target_file)) {
                $photo_profil = $target_file;
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        }
    } else {
        // Récupérer l'ancienne photo de profil de la base de données
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        $stmt->close();

        // Utiliser l'ancienne photo de profil dans la requête de mise à jour
        $photo_profil = $user['profil_image'];
    }

    $stmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, profil_image = ? WHERE id = ?");
    if ($stmt === false) {
        $login_error = "Erreur de connexion à la base de données.";
    } else {
        $stmt->bind_param("ssssi", $nom, $prenom, $email, $photo_profil, $id);

        // Exécuter la requête
        $stmt->execute();
        $stmt->close();

        // Mettre à jour la session utilisateur
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        $stmt->close();
        $conn->close();

        // Rediriger vers la page de profil
        header('Location: profil.php');
        exit();
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
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
</head>
<body>
<?php include 'navbar.php'; ?>

<?php
$user = $_SESSION['user'];

// Vérification des réponses acceptées
$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM reponse WHERE accepted = 1 AND pro_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$accepted_responses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($accepted_responses as $accepted_response) {
    echo '<div class="card">Félicitations, votre réponse a été acceptée !</div>';
}
?>

<section class="min-h-screen flex items-center justify-center">
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
        <form action="" method="post" enctype="multipart/form-data" class="w-full">
            <h2 class="text-2xl font-bold text-center mb-5">Modifier mon profil</h2>
            <div class="mb-5">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="mb-5">
                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="mb-5">
                <label for="photo_profil" class="block text-sm font-medium text-gray-700">Photo de profil</label>
                <input type="file" name="photo_profil" id="photo_profil" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit" name="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enregistrer</button>
        </form>
    </div>
</section>
</body>

<?php include "footer.php"?>
</html>
