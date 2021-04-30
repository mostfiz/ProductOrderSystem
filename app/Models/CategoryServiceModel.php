<?php 
namespace App\Models;

use App\Infrastructure\Abstracts\Model;
use App\Infrastructure\Traits\Validator;

class CategoryServiceModel extends Model{


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
       return $this->insert('category',$data);
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
            UPDATE category
            SET 
                category_name = :category_name,
                updated_by = :updated_by
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'category_name' => $input['category_name'],
                'updated_by' => $input['updated_by'] ?? 0
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
                        ->query('SELECT * FROM category')
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
                        ->query('SELECT `category_name`,`entry_by`,`entry_at`'
                                . 'FROM category as o '
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
                        ->query('SELECT `category_name`,`entry_by`,`entry_at`'
                                . 'FROM category as o '
                                . 'WHERE (o.entry_at >= ' . " '$from' AND o.entry_at <= " . " '$to') 
                                OR o.entry_by = "."'$user_id'" .' ORDER BY id DESC ')
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
            `category_name`,`entry_by`,`entry_at`
            FROM
                category
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

    public function remove($id)
    {
        return $this->delete('category',$id);
    }

}