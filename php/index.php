<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';
require __DIR__ . '/controllers/includes/Database.php';
require __DIR__ . '/controllers/ClassiController.php';

$app = AppFactory::create();

//!CLASSI
//*Ottengo tutte le classi -> curl http://localhost:8080/classi
$app->get("/classi","ClassiController:index");

//*Ottengo la classe con id specificato -> curl http://localhost:8080/classi/1
$app->get("/classi/{classe_id}", "ClassiController:showById");

//*Creo una classe -> curl -X POST http://locahost:8080/classi -H "Content-Type: application/json" -d '{"sezione": "5C", "anno": 2025}
$app->post("/classi", "ClassiController:create");

//* Aggiorno le informazioni della classe con id specificato -> curl -X PUT http://localhost:8080/classi/2 -H "Content-Type: application/json" -d '{"sezione": "5B", "anno": 2025}'
$app->put("/classi/{classe_id}","ClassiController:update");

//*Elimino dal DB la classe con id specificato -> curl -X DELETE http://localhost:8080/classi/4
$app->delete("/classi/{classe_id}", "ClassiController:delete");

//!ALUNNI

$app->get("/alunni", "AlunniController:index");

//*Ottengo TUTTI gli alunni della classe con id specificato
$app->get('/classi/{classe_id}/alunni', "AlunniController:indexByClass");
    
//*Ottengo un alunno tramite id
$app->get("/classi/{classe_id}/alunni/{alunno_id}","AlunniController:showById");

//*Creo un alunno per una certa classe
$app->post("/classi/{classe_id}/alunni", "AlunniController:create");

//*Aggiorno le informazioni su un alunno
$app->put("/classi/{classe_id}/alunni/{alunno_id}","AlunniController:update");

//*Elimino un alunno
$app->delete("/classi/{classe_id}/alunni/{alunno_id}","AlunniController:delete");

$app->run();
