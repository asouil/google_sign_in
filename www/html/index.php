<?php
require 'config.php';
$url = "http://localhost:8080";
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;
require_once $basePath . '/vendor/autoload.php';
session_start();

// algorithme pour la connexion via google
/**  TÉLÉCHARGEMENT du google API
 * créer un identifiant sur developpers.google.com/apis/credentials
 * ne pas oublier de require le autoload
 */

// entrer le google account credential

$client = new Google_Client();
$client->setApplicationName("Canaldemo");
$client->setAccessType('offline');
$client->setClientId($idegoogle);
$client->setClientSecret($csecret);
$client->setRedirectUri($url);
$client->setScopes("email");

// créer l'url

$auth=$client->createAuthUrl();

// obtenir le code d'authentification

$code = isset($_GET['code']) ? $_GET['code'] : NULL ;
// obtenir le token d'accès

if(isset($code)) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($code);
        // assigne le token au client
        $client->setAccessToken($token);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    try {
        $_SESSION['user']= $client->verifyIdToken();
    } 
    catch (Exception $e){
        echo $e->getMessage();
    }
} else {
    $_SESSION["user"] = NULL;
}
// enregistrer dans SESSION["user"] son mail
// $_SESSION["user"] si connecté 

if (isset($_SESSION["user"])) {
    header('Location: ' . $url . "/protected.php");
}

include 'header.php';
?>

<div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
        <h1 class="display-4 font-italic">Protected Zone</h1>
        <p class="lead my-3"><a href="<?=$auth ?>" class="btn btn-primary">Login Through Google </a></p>
    </div>
</div>

<?php
include 'footer.php';
