<?php

namespace App\Infrastructure\Abstracts;
use PDO;

abstract class Model {

    /**
     * It represents a PDO instance
     *
     * @var object
     */
    private $pdo = null;

    /**
     * The name of the table in the database that the model binds
     *
     * @var string
     */
    private $_table;

    /**
     * The model construct
     *
     */
    public function __construct($pdo) {

        $this->pdo = $pdo;
    }

    /**
     * Abstract method for getting all records from database.
     *
     *
     * @return array
     * @access  public
     */
    abstract function getAll(): iterable;

    /**
     * The insert method.
     * 
     * This method makes it easy to insert data into the database 
     * in a quick and easy way. The data set must be associative. 
     * Index of array represents the field in the database.
     * 
     * For example: [ "fist_name" => "John" ]
     *
     * @param array $data A set of data to be added to the database.
     *
     * @return integer The last insert ID
     * @access  public
     */
    public function insert($table_name, array $data): array {
        try {
            if($table_name === ""){
                throw new \Exception("Attribute _table is empty string!");
            }
            
            // Question marks
            $marks = array_fill(0, count($data), '?');
            // Fields to be added.
            $fields = array_keys($data);
            // Fields values
            $values = array_values($data);

            // Prepare statement
            $stmt = $this->DB()->prepare("
                INSERT INTO " . $table_name . "(" . implode(",", $fields) . ")
                VALUES(" . implode(",", $marks) . ")
            ");

            // Execute statement with values
            $stmt->execute($values);

            // Return last inserted ID.
            $insertId = $this->DB()->lastInsertId();
            if($insertId != "" && $insertId >= 0)
            {
                $response["status"]=201;
                $response["message"]="Data Added";
            }
            else
            {
                $response["status"]=202;
                $response["message"]="Addition Failed";
            }

            return $response;
        } catch (\PDOException $e) {
            $response["status"]=202;
            $response["message"]=$e->getMessage();
            return $response;
        }    
    }


    /**
     * The Delete method.
     * 
     * This method makes it easy to delete data into the database 
     * in a quick and easy way. 
     * 
     *
     * @param array $data A  data to be delete to the database.
     * @return integer The rowCount
     * @access  public
     */
    public function delete($table_name,$id)
    {
        $statement = "
            DELETE FROM ".$table_name." 
            WHERE id = :id;
        ";

        try {
            $statement = $this->pdo->prepare($statement);
            $statement->execute(array('id' => $id));
            if($statement->rowCount())
            {
                $response["status"]=200;
                $response["message"]="Data Deleted";
            }
            else
            {
                $response["status"]=202;
                $response["message"]="Deletion Failed";
            }
    
            return $response;
        } catch (\PDOException $e) {
            $response["status"]=202;
            $response["message"]=$e->getMessage();
            return $response;
        }    
    }

    /**
     * The method return a PDO database connection.
     *
     * @return object
     * @access  public
     */
    protected function DB(): PDO {

        return $this->pdo;
    }

}

