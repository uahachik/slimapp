<?php

class DB
{
    // Properties
    private $host = 'localhost';
    private $db_name = 'myblog';
    private $username = 'root';
    private $password = 'root';


    // db Connect
    public function connect()
    {
        try {
            // Set DSN (Data Source Name)
            $dsn = "mysql:host =$this->host;dbname=$this->db_name";

            // Create a PDO instance
            $pdo = new PDO($dsn, $this->username, $this->password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // using an object with default set without parameters
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // EMULATION OFF (it has a PDO substitute placeholder with
                                                                                  //  actual data instead of sending it separately  LIMIT 1 1 )
        } catch (PDOException $e) {
            echo 'Connection Error:' . $e->getMessage();
        }

        return $pdo;
    }

}