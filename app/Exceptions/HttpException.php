<?php
namespace App\Exceptions;
use Exception;

class HttpException extends Exception
{
    protected $code;
    protected $message;
    protected $data;
    
    public function __construct($message, $code = 0, $data = [])    {
        parent::__construct($message, $code);      
        $this->data = $data;
    }
    public function getData(){
        return $this->data;
    }


    
}