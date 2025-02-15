<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $listaPacientes=$_pacientes->listaPacientes($pagina);
        header("Content-Type: aplication/json");
        echo json_encode($listaPacientes);
        http_response_code(200);
    }else if(isset($_GET["id"])){
        $pacienteid = $_GET["id"];
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteid);
        header("Content-Type: aplication/json");
        http_response_code(200);
        echo json_encode($datosPaciente);
    }
   
}else if($_SERVER['REQUEST_METHOD' =="POST"]){
   $postBody = file_get_contents("php://input");
   $resp = $_pacientes->post($postBody);

   header('content-type: aplication/json');
   if(isset($datosArray['result']["error_id"])){
       $responseCode = $datosArray["result"]["error_id"];
       http_response_code($responseCode);
   }else{
       http_response_code(200);
   }
   echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD' =="PUT"]){
    
    $postBody = file_get_contents("php://input");

    $datosArray = $_pacientes->put($postBody);
    header('content-type: aplication/json');
    if(isset($datosArray['result']["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);


}else if($_SERVER['REQUEST_METHOD' =="DELETE"]){

    $headers = getallheaders();
    if(isset($headers["token"]) && isset($headers["pacienteId"])){
        $send = [
            "token" => $headers["token"],
            "pacienteId" => $headers["pacienteId"]
        ];
        $postBody = json_encode($send);
    }else{
        $postBody = file_get_contents("php://input");
    }


    $datosArray = $_pacientes->delete($postBody);
    header('content-type: aplication/json');
    if(isset($datosArray['result']["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else{
    header("Content-Type: aplication/json");
    $datosArray = $_repuestas->error_405();
    echo json_encode($datosArray);  
}