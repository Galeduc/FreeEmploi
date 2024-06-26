<?php
// Remplacez ces valeurs par les informations de connexion à votre base de données
$host = 'localhost';
$dbname = 'freeemploi';
$username = 'mysql';
$password = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Définissez le mode d'erreur PDO sur exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, affichez l'erreur
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit(); // Arrêtez l'exécution du script
}

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// Inclure le fichier de configuration de la base de données
require_once '../../config/database.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// Log request method and endpoint
error_log("Request Method: " . $requestMethod);
error_log("Endpoint: " . $endpoint);

function loginUser($pdo)
{
    // Vérifier si les données de connexion sont présentes
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email']) && isset($data['password'])) {
        $email = $data['email'];
        $password = $data['password'];

        // Utiliser la variable $pdo passée en argument
        getUser($pdo, $email, $password);
    } else {
        echo json_encode(["message" => "Email et mot de passe requis", "success" => false]);
    }
}

function registerUser(PDO $pdo)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nom']) && isset($data['prenom']) && isset($data['email']) && isset($data['password'])) {
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $email = $data['email'];
        $password = $data['password'];

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mdp) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $prenom, $email, $hashed_password])) {
            echo json_encode(["message" => "Utilisateur enregistré avec succès", "success" => true]);
        } else {
            echo json_encode(["message" => "Erreur d'enregistrement", "success" => false]);
        }
    } else {
        echo json_encode(["message" => "Nom, prénom, email et mot de passe requis", "success" => false]);
    }
}

switch ($requestMethod) {
    case 'GET':
        if ($endpoint === 'annonces') {
            getAnnonces($pdo);
        } else {
            echo json_encode(["message" => "Endpoint non valide", "success" => false]);
        }
        break;
    case 'POST':
        if ($endpoint === 'login') {
            loginUser($pdo);
        } elseif ($endpoint === 'register') {
            registerUser($pdo);
        } else {
            echo json_encode(["message" => "Endpoint non valide", "success" => false]);
        }
        break;
    default:
        echo json_encode(["message" => "Méthode non autorisée", "success" => false]);
        break;
}

function getAnnonces($pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM annonces');
    if ($stmt->execute()) {
        $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["annonces" => $annonces, "success" => true]);
    } else {
        echo json_encode(["message" => "Erreur de base de données", "success" => false]);
    }
}

?>
