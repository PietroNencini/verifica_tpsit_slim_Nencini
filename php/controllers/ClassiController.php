<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClassiController
{
  public function index(Request $request, Response $response, $args){
    $db = Database::getInstance();
    $result = $db->select("classi");
    $response->getBody()->write(json_encode($result));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function showById(Request $request, Response $response, $args){
    $db = Database::getInstance();
    $id_classe = $args["classe_id"];
    if(isset($id_classe) && !empty($id_classe)){
      $result = $db->select("classi", "id = $id_classe");
      if(count($result) > 0) {
        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "classe non trovata", "status" => "not_found_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(404);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "data_error"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function create(Request $request, Response $response, $args){
    $db = Database::getInstance();
    $dati_classe = json_decode($request->getBody()->getContents(), true);
    $sezione = $dati_classe["sezione"];
    $anno = $dati_classe["anno"];
    if(isset($sezione) && !empty($sezione) && isset($anno) && !empty($anno)){
      $result = $db->insert("classi (sezione, anno) ", "('$sezione', '$anno')");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg" => "inserimento avvenuto", "status" => "200: OK"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg" => "errore nell'inserimento", "status" => "server_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "data_error"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function update(Request $request, Response $response, $args){
    $db = Database::getInstance();
    $id_classe = $args["classe_id"];
    $dati_classe = json_decode($request->getBody()->getContents(), true);
    $sezione = $dati_classe["sezione"];
    $anno = $dati_classe["anno"];
    if(isset($id_classe) && !empty($id_classe) && isset($sezione) && !empty($sezione) && isset($anno) && !empty($anno)) {
      $db->update("classi", "sezione = '$sezione', anno = $anno", "id = $id_classe");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg"=> "aggiornamento effettuato con successo", "status" => "200: OK"]));
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "aggiornamento non riuscito", "status" => "server_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "data_error"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }

  public function delete(Request $request, Response $response, array $args) {
    $db = Database::getInstance();
    $id_classe = $args["classe_id"];
    if(isset($id_classe) && !empty($id_classe)) {
      $db->delete("classi", "id = $id_classe");
      if($db->affected_rows > 0) {
        $response->getBody()->write(json_encode(["msg"=> "classe (id: $id_classe) eliminata con successo", "status" => "200: OK"]));
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
      } else {
        $response->getBody()->write(json_encode(["msg"=> "errore nella cancellazione", "status" => "data_error"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
      }
    } else {
      $response->getBody()->write(json_encode(["msg"=> "dati inseriti non validi", "status" => "server_error"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }
  }
}
