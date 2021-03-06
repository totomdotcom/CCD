<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use GEG\controler\connectionControler;
use GEG\view\VueAjouterCreneau;
use GEG\view\VueGenerale;
use GEG\controler\ListUserControler;
use GEG\controler\PrincipaleControler;
use GEG\controler\AjouterBesoinControler;
use GEG\controler\CreneauControleur;
use GEG\controler\ListBesoinControleur;
use GEG\controler\HomeControler;
use GEG\controler\InscriptionBesoinControler;

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));

$db->setAsGlobal();
$db->bootEloquent();

$app = new \Slim\Slim;

session_start();

//Affichage de toutes les listes

$app->get('/', function () {
    $controller = new HomeControler();
    $controller->render();
})->name('route_home');

$app->get('/users', function () {
    $c = new ListUserControler();
    $c->getAllUser();
})->name('route_listeUser');

$app->get('/users/:id', function ($id) {
    $c = new connectionControler();
    $c->getUser($id);
})->name('route_loginTestId');

$app->get("/connexion", function () {
    $controler = new connectionControler();
    $controler->seConnecter();
})->name('connexion');

$app->post("/connexion", function () {
    $controler = new connectionControler();
    $controler->authentifier();
});

$app->get('/deconnexion', function () use ($app){
    $c = new connectionControler();
    $c->deconnexion();
    $app->response->redirect($app->urlFor('route_home'),303);
})->name('route_deconnexion');

$app->get("/inscrire",function (){
    $c =new connectionControler();
    $c->renderInscription();
})->name("inscrire");

$app->post("/inscrire",function (){
    $c=new connectionControler();
    $c->inscrire();
});
$app->get("/ajouterCreneau", function () {
    $a = new CreneauControleur();
    $a->afficher();
})->name('route_ajoutCreneau_get');

$app->post("/ajouterCreneau", function () use($app) {
    $a = new CreneauControleur();
    $a->SaveCreneau();
    $app->response->redirect($app->urlFor('route_home'),303);
})->name('route_ajoutCreneau_post');

$app->get("/ajouterBesoin/:idCreneau", function ($idCreneau) {
    $controler = new AjouterBesoinControler();
    $controler->renderForm($idCreneau);
})->name('route_ajouterBesoinform');

$app->post("/ajouterBesoin/:idCreneau", function ($idCreneau) use ($app) {
    $controler = new AjouterBesoinControler();
    $controler->ajouterBesoin($idCreneau);

    $app->response->redirect($app->urlFor("route_home"),303);
})->name('route_ajoutBesoinIdCreneau');

$app->get("/listeBesoin",function(){
    $controler=new ListBesoinControleur();
    $controler->render();
})->name('route_listeBesoin');

$app->get("/inscriptionBesoin/:idBesoin", function($idBesoin) {
  $controler = new InscriptionBesoinControler();
  $controler->ajouterBesoinInscription($idBesoin);
});

$app->get("/supUser",function (){
    $c=new connectionControler();
    $c->supprimer($_SESSION['user']['id']);
})->name("supCompte");

$app->run();
