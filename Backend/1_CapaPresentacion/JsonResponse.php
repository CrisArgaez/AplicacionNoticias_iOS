<?php

class JsonResponse{
    private $message; //Mensaje de respuesta
    private $data; //Datos de respuesta
    private $code; //Codigo de respuesta

    public function __construct() {}

    private function setResponse($message, $data, $code){
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
    }

    private function sendResponse($response){
        header('HTTP/1.1 ' . $response->code);
        header('Content-Type: application/json');
        $response = json_encode([
            'code' => $response->code,
            'message' => $response->message,
            'data' => $response->data
        ]);
        echo($response);
    }

    public function success($message, $data, $code  ){
        $this->setResponse($message, $data, $code);
        $this->sendResponse($this);
    }

    public function failed($message, $code){
        $this->setResponse($message, null, $code);
        $this->sendResponse($this);
    }
}
?>