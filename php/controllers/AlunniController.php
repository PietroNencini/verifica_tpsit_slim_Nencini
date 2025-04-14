<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{

  public function indexByClass(Request $request, Response $response,$args) {
    $db = Database::getInstance();
    $class_id = $args["classe_id"];
    if(isset($class_id) && !empty($class_id)) {
      $result = $db->select("alunni", "classe_id = $class_id");
      if(count($result) > 0) {
        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-Type","application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "Alunno non trovato", "status" => "404: not_found_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(404);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "400:  bad_request"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function showById(Request $request, Response $response, $args){
    $db = Database::getInstance();
    $id_classe = $args["classe_id"];
    $id_alunno = $args["alunno_id"];
    if(isset($id_alunno) && !empty($id_alunno) && isset($id_classe) && !empty($id_classe)) {
      $result = $db->select("alunni", "id = $id_alunno");
      if(count($result) > 0) {
        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "alunno non trovato", "status" => "404: not_found_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(404);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => " 400: bad_request"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function create(Request $request, Response $response, $args) {
    $db = Database::getInstance();
    $dati_alunno = json_decode($request->getBody()->getContents(), true);
    $nome = $dati_alunno["nome"];
    $cognome = $dati_alunno["cognome"];
    $id_classe = $dati_alunno["id_classe"];
    if(isset($nome) && !empty($nome) && isset($cognome) && !empty($cognome) && isset($id_classe) && !empty($id_classe)){
      $result = $db->insert("alunni (nome, cognome, classe_id) ", "('$nome', '$cognome', $id_classe)");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg" => "inserimento avvenuto", "status" => "200: OK"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg" => "errore nell'inserimento", "status" => "500: server_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "400:  bad_request"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function update(Request $request, Response $response, $args) {
    $id_alunno = $args["alunno_id"];
    $id_classe = $args["classe_id"];    // Id della classe a cui appartiene l'alunno
    $db = Database::getInstance();
    $dati_alunno = json_decode($request->getBody()->getContents(), true);
    $nome = $dati_alunno["nome"];
    $cognome = $dati_alunno["cognome"];
    $id_classe_alunno = $dati_alunno["id_classe"]; // Id eventualmente aggiornato
    if(isset($id_classe) && !empty($id_classe) && isset($nome) && !empty($nome) && isset($cognome) && !empty($cognome) && isset($id_classe_alunno) && !empty($id_classe_alunno) ) {
      $result = $db->update("alunni", "nome = $nome, cognome = $cognome, classe_id = $id_classe_alunno", "id = $id_alunno");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg" => "Aggiornamento avvenuto", "status" => "200: OK"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg" => "errore nell'aggiornamento", "status" => "500: erver_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "400:  bad_request"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function delete(Request $request, Response $response, array $args) {
    $db = Database::getInstance();
    $id_alunno = $args["alunno_id"];
    if(isset($id_alunno) && !empty($id_alunno)) {
      $db->delete("alunni", "id = $id_alunno");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg"=> "alunno (id: $id_alunno) eliminato con successo", "status" => "200: OK"]));
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "errore nella cancellazione", "status" => "500: server_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "400:  bad_request"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }
  
  /*public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni where classe_id = " .$args['classe_id'] );
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }*/
}
