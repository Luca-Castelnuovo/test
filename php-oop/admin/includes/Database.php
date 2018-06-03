<?php
/**
 * Created by PhpStorm.
 * User: LucaCastelnuovo
 * Date: 27-05-18
 * Time: 13:21
 */

class Database
{

    public $connection;

    function __construct()
    {
        $this->open_db_connection();
    }

    private function open_db_connection()
    {
        $this->connection = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

        if ($this->connection->connect_errno) {
            die("Database Connect error! " . $this->connection->connect_error);
        }
    }

    public function query($sql)
    {
        //sanitize
        $sql = $this->escape_string($sql);

        //make request
        $result = $this->connection->query($sql);

        //check request
        $this->confirm_query($result);

        return $result;
    }

    private function confirm_query($result)
    {
        if (!$result) {
            die("Query Failed! " . $this->connection->error);
        }
    }

    private function escape_string($string)
    {
        return $this->connection->real_escape_string($string);
    }

    public function the_insert_id()
    {
        return $this->connection->insert_id;
    }

}

$database = new Database;
