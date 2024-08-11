<?php

namespace Framework;

use PDO;

class Database
{

    public $conn;

    /** 
     * Database constructor database class
     * @param array $config
     */

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";


        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }

    }

    /**
     * query the database
     * 
     * @return PDOStatement
     * @throws PDO
     */

    public function query($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);

            // bind 

            foreach ($params as $key => $value) {
                $stmt->bindValue(":" . $key, $value);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed:
            " . $e->getMessage());

        }
    }


}