<?php
/**
 * Created by PhpStorm.
 * User: jvargas
 * Date: 19/02/16
 * Time: 14:39
 */

namespace Ximdex\API;


class Response
{
    const ERROR = -1;
    const OK = 0;

    private $status = 0;
    private $response = null;
    private $message = '';

    public function __construct(){

    }

    /**
     * Sets the status code
     *
     * @param $status
     * @return $this
     */
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }

    /**
     * Sets the message
     *
     * @param $message
     * @return $this
     */
    public function setMessage($message){
        $this->message = $message;
        return $this;
    }

    /**
     * Sets the response
     *
     * @param $response
     * @return $this
     */
    public function setResponse($response){
        $this->response = $response;
        return $this;
    }

    /**
     * Sends reponse and exists
     *
     * @param string $method
     * @return string
     */
    public function send(  ){
        $data = [
          'status' => $this->status,
          'message' => $this->message,
          'response' => $this->response,
        ];
        // TODO: Check CORS and filters 
        header( "Access-Control-Allow-Origin: *");
        header( "Access-Control-Allow-Credentials: true");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
       exit ;

    }
}