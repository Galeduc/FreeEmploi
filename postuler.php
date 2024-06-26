<?php
session_start();

$annonce_id = isset($_GET['id']) ? $_GET['id'] : null;
$banniere_url = isset($_GET['banniere']) ? $_GET['banniere'] : 'default_banniere.jpg';

// Ensuite, vous pouvez vérifier si l'ID de l'annonce est null avant de faire la requête SQL
if ($annonce_id === null) {
    echo "Erreur : L'ID de l'annonce n'a pas été fourni.";
    exit;
}

// Récupérez le pro_id de l'annonce à partir de la base de données
include 'db.php'; // Assurez-vous de remplacer "votre_fichier_de_connexion
// Récupérez le pro_id et la bannière de l'annonce à partir de la base de données
$stmt = $conn->prepare("SELECT pro_id, banniere FROM annonces WHERE id = ?");
$stmt->bind_param("i", $annonce_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $annonce = $result->fetch_assoc();
    $pro_id = $annonce['pro_id'];
    $banniere_url = $annonce['banniere'];
} else {
    echo "Erreur : L'annonce n'a pas été trouvée.";
    echo "ID de l'annonce : " . $annonce_id;
    exit;
}

$stmt->close();
$pro_id = $annonce['pro_id'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cv = $_FILES['cv'];
    $coverLetter = $_FILES['coverLetter'];

    // Vérifiez si tous les champs sont remplis
    if (!empty($name) && !empty($email) && !empty($cv) && !empty($coverLetter)) {
        // Déplacer le fichier CV vers le dossier de destination
        $cvDestination = 'uploads/' . basename($cv['name']);
        if (!move_uploaded_file($cv['tmp_name'], $cvDestination)) {
            die('Erreur lors de l\'enregistrement du CV.');
        }

        // Déplacer la lettre de motivation vers le dossier de destination
        $coverLetterDestination = 'uploads/' . basename($coverLetter['name']);
        if (!move_uploaded_file($coverLetter['tmp_name'], $coverLetterDestination)) {
            die('Erreur lors de l\'enregistrement de la lettre de motivation.');
        }

        // Maintenant, vous pouvez utiliser $pro_id lors de l'insertion de la nouvelle réponse
        $stmt = $conn->prepare("INSERT INTO reponse (name, email, cv_path, cover_letter_path, pro_id, annonce_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $name, $email, $cvDestination, $coverLetterDestination, $pro_id, $annonce_id);
        $stmt->execute();
        $stmt->close();

        // Redirection vers une page de confirmation ou autre
        header("Location: emplois.php");
        exit;
    } else {
        // Redirection avec un message d'erreur si des champs sont manquants
        header("Location: postuler.php?error=missing_fields");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler</title>
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
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="grid md:grid-cols-2 gap-16 items-center relative overflow-hidden p-10 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] rounded-3xl max-w-6xl mx-auto bg-white text-[#333] my-6 font-[sans-serif] before:absolute before:right-0 before:w-[300px] before:bg-orange-200 before:h-full max-md:before:hidden">
    <div>
        <h2 class="text-3xl font-extrabold">Postuler</h2>
        <p class="text-sm text-gray-400 mt-3">Veuillez remplir le formulaire ci-dessous pour postuler à ce poste.</p>
        <form method="post" enctype="multipart/form-data">
            <div class="space-y-4 mt-8">
                <input type="text" name="name" placeholder="Nom et Prénom" class="px-2 py-3 bg-white w-full text-sm border-b-2 focus:border-[#333] outline-none" />
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['email'] : ''; ?>" class="px-2 py-3 bg-white text-black w-full text-sm border-b-2 focus:border-[#333] outline-none" />
                <div class="flex items-center space-x-2">
                    <label>CV</label>
                    <input type="file" name="cv" placeholder="CV" class="px-2 py-3 bg-white text-black w-full text-sm border-b-2 focus:border-[#333] outline-none" />
                </div>
               <div class="flex items-center space-x-2">
                    <label>Lettre de motivation</label>
                    <input type="file" name="coverLetter" placeholder="Lettre de motivation" class="px-2 py-3 bg-white text-black w-full text-sm border-b-2 focus:border-[#333] outline-none" />
            </div>
            <button type="submit" class="mt-8 flex items-center justify-center text-sm w-full rounded px-4 py-2.5 font-semibold bg-[#333] text-white hover:bg-[#222]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill='#fff' class="mr-2" viewBox="0 0 548.244 548.244">
                    <path fill-rule="evenodd" d="M392.19 156.054 211.268 281.667 22.032 218.58C8.823 214.168-.076 201.775 0 187.852c.077-13.923 9.078-26.24 22.338-30.498L506.15 1.549c11.5-3.697 24.123-.663 32.666 7.88 8.542 8.543 11.577 21.165 7.879 32.666L390.89 525.906c-4.258 13.26-16.575 22.261-30.498 22.338-13.923.076-26.316-8.823-30.728-22.032l-63.393-190.153z" clip-rule="evenodd" data-original="#000000" />
                </svg>
                Envoyer le message
            </button>
        </form>
    </div>
</div>
<div class="z-10 relative h-full max-md:min-h-[350px]">
    <img src="<?php echo htmlspecialchars($banniere_url); ?>" alt="Bannière" class="w-full h-auto object-contain">
</div>
</div>
</body>
<?php include "footer.php"?>
</html>