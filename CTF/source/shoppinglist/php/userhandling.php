<?php

class UserHandler
{
    private $dbName = "../datastore/users.dat";

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new UserHandler();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function getUsers()
    {
          if (file_exists($this->dbName))
            $users = unserialize(file_get_contents($this->dbName));           
          else
            $users = array();
            return $users;              
    }
    
    public function getUser($name)
    {
       $users = $this->getUsers();
        if (!array_key_exists($name, $users))
          return false;
        return $users[$name];
    }
    
    private function saveUsers($users)
    {
        file_put_contents_atomic($this->dbName, serialize($users));
    }
    
    public function tryRegister(User $user)
    {
        $lock = fopen($this->dbName.".LOCK", "w");
        while (true) {
          if (flock($lock, LOCK_EX)) {
              time_nanosleep(0, 50000000);
              break;
          }
        }
        
        $users = $this->getUsers();
        if (array_key_exists($user->username, $users))
          return false;
        $users[$user->username] = $user;
        $this->saveUsers($users);             
        return true;
    }
    
    public function login($username, $password)
    {
        $lock = fopen($this->dbName.".LOCK", "w");
        while (true) {
          if (flock($lock, LOCK_EX)) {
              time_nanosleep(0, 50000000);
              break;
          }
        }
        
        $users = $this->getUsers();
        if (!array_key_exists($username, $users))
          return false;
        $user = $users[$username];
        if ($user->password == md5($password))
        {
          return $user;
        }
        return false;
    }

    public function saveUser(User $user)
    {
        $lock = fopen($this->dbName.".LOCK", "w");
        while (true) {
          if (flock($lock, LOCK_EX)) {
              time_nanosleep(0, 50000000);
              break;
          }
        }
        
        $users = $this->getUsers();
        if (!array_key_exists($user->username, $users))
          return false;
        $users[$user->username] = $user;
        $this->saveUsers($users);             
        return true;
    }

}

define("FILE_PUT_CONTENTS_ATOMIC_TEMP", dirname(__FILE__)."../db/cache"); 
define("FILE_PUT_CONTENTS_ATOMIC_MODE", 0777); 

function file_put_contents_atomic($filename, $content) { 
   
    $temp = tempnam(FILE_PUT_CONTENTS_ATOMIC_TEMP, 'temp'); 
    if (!($f = @fopen($temp, 'wb'))) { 
        $temp = FILE_PUT_CONTENTS_ATOMIC_TEMP . DIRECTORY_SEPARATOR . uniqid('temp'); 
        if (!($f = @fopen($temp, 'wb'))) { 
            trigger_error("file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING); 
            return false; 
        } 
    } 
   
    fwrite($f, $content); 
    fclose($f); 
   
    if (!@rename($temp, $filename)) { 
        @unlink($filename); 
        @rename($temp, $filename); 
    } 
   
    @chmod($filename, FILE_PUT_CONTENTS_ATOMIC_MODE); 
   
    return true; 
   
} 


class User
{
 public $username;
 public $password;
 public $note;
}

?>