<?php

class Connection
{
   private $server = "mysql:host=localhost;dbname=projtrac_website";
   private $user = "root";
   private $pass = "";
   private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
   protected $con;

   public function openConnection()
   {
      try {
         $this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
         return $this->con;
      } catch (PDOException $ex) {
         $this->customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
      }
   }

   public function closeConnection()
   {
      $this->con = null;
   }

   public function customErrorHandler($errno, $errstr, $errfile, $errline)
   {
      $message = "Error: [$errno] $errstr - $errfile:$errline";
      error_log($message . PHP_EOL, 3, "error_log.log");
   }
}
