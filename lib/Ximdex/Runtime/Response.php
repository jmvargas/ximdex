<?php

namespace Ximdex\Runtime;

class Response extends \Illuminate\Http\Response
{
    /**
     * Response constructor.
     */
    function __construct($content = '', $status = 200, $headers = array()) {
        parent::__construct( $content, $status, $headers );
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->header($key, $value);
        return $this;
    }

    /**
     *
     */
    public function sendHeaders()
    {
        /*echo ob_get_clean(); // asegura que no ha habido escritura antes de enviar las cabeceras
        $keys = $this->_headers->getKeys();
        foreach ($keys as $key) {
            $values = $this->get($key);
            if (is_array($values)) {
                foreach ($values as $value) {
                    header($key . ":" . $value);
                }
            } else {
                header($key . ": " . $values);
            }
        }*/
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->headers->get($key);
    }

    public function sendStatus($string, $replace, $status)
    {
        echo ob_get_clean(); // asegura que no ha habido escritura antes de enviar las cabeceras

        if (is_numeric($status)) {
            header($string, $replace, $status);
//			die();
        }
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * <p>Sends the header with the specified status code</p>
     * @staticvar string $status_codes Keeps the status codes
     */
    /**
     * @param $statusCode
     */
    public function header_status($statusCode)
    {
        static $status_codes = null;

        if ($status_codes === null) {
            $status_codes = array(
                100 => 'Continue',
                101 => 'Switching Protocols',
                102 => 'Processing',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                207 => 'Multi-Status',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                426 => 'Upgrade Required',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',
                507 => 'Insufficient Storage',
                509 => 'Bandwidth Limit Exceeded',
                510 => 'Not Extended'
            );
        }

        if ($status_codes[$statusCode] !== null) {
            $status_string = $statusCode . ' ' . $status_codes[$statusCode];
            $this->header($_SERVER['SERVER_PROTOCOL'], $status_string);
            $this->setStatusCode($statusCode);
        }
    }

}
