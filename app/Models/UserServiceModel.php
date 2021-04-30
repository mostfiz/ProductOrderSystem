<?php 
namespace App\Models;

use App\Infrastructure\Abstracts\Model;
use App\Infrastructure\Traits\Validator;

class UserServiceModel extends Model{


    private $db = null;
    /**
     * The model construct
     *
     */
  
    public function __construct($db)
    {
        $this->db = $db;

        parent::__construct($db);
    }
    
    /**
     * Method save order records from database.
     * [Implemented method from the Model class]
     *
     * @return array
     * @access  public
     */
    public function save($data){
        return $this->insert('user',$data);
    }

    /**
     * Method update records from database.
     *
     * @return array
     * @access  public
     */
    public function update($id, Array $input)
    {
        $statement = "
            UPDATE user
            SET 
                first_name = :first_name,
                last_name  = :last_name,
                user_type  = :user_type,
                email = :email,
                status = :status
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'first_name' => $input['first_name'],
                'last_name'  => $input['last_name'],
                'user_type'  => $input['user_type'],
                'email' => $input['email'] ?? null,
                'status' => $input['status'] ?? null
            ));
            if($statement->rowCount())
            {
                $response["status"]=200;
                $response["message"]="Data Updated";
            }
            else
            {
                $response["status"]=202;
                $response["message"]="Updation Failed";
            }
            return $response;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }


    /**
     * Method getting all records from database.
     * [Implemented method from the Model class]
     *
     * @return array
     * @access  public
     */
    public function getAll(): iterable {

        return $this->DB()
                        ->query('SELECT * FROM user')
                        ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Method getting last 10 records from database.
     *
     * @return array
     * @access  public
     */
    public function getLastTen(): iterable {

        return $this->DB()
                        ->query('SELECT `first_name`,`last_name`,`user_type`,`email`,`status`,`last_login_at`,`created_at`'
                                . 'FROM user as o '
                                . 'ORDER BY id DESC '
                                . 'LIMIT 10')
                        ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array  
     * @access  public
     */
    public function getSearchData(string $from = NULL, string $to = NULL, string $user_id = NULL): iterable {

        if (Validator::dates(compact(["from", "to"])) === false) {

            $from = date("Y-m-01");
            $to = date("Y-m-t");
        }

        return $this->DB()
                        ->query('SELECT `first_name`,`last_name`,`user_type`,`email`,`status`,`last_login_at`,`created_at`'
                                . 'FROM user as o '
                                . 'WHERE (o.created_at >= ' . " '$from' AND o.created_at <= " . " '$to') 
                                OR o.created_by = "."'$user_id'" .' ORDER BY id DESC ')
                        ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array  
     * @access  public
     */
    public function find($id)
    {
        $statement = "
            SELECT 
            `first_name`,`last_name`,`user_type`,`email`,`status`,`last_login_at`,`created_at` 
            FROM
                user
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * @return array  
     * @access  public
     */
    public function findByIndividual($username)
    {
        $statement = "
            SELECT 
            `id`,`first_name`,`last_name`,`user_type`,`email`,`password`,`status`,`last_login_at`,`created_at` 
            FROM
                user
            WHERE email = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($username));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * @return array  
     * @access  public
     */
    public function remove($id)
    {
        return $this->delete('user',$id);
    }

}