<?php 
namespace App\Models;

use App\Infrastructure\Abstracts\Model;
use App\Infrastructure\Traits\Validator;

class ProductServiceModel extends Model{


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
       return $this->insert('product',$data);
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
            UPDATE product
            SET 
                product_name = :product_name,
                sku  = :sku,
                description = :description,
                category = :category,
                price = :price,
                image_link = :image_link,
                updated_by = :updated_by
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'product_name' => $input['product_name'],
                'sku'  => $input['sku'],
                'description'  => $input['description'],
                'category'  => $input['category'],
                'price' => $input['price'] ?? 0.00,
                'image_link' => $input['image_link'],
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
                        ->query('SELECT * FROM product')
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
                        ->query('SELECT `product_name`,`sku`,`description`,`category`,`price`,`image_link`,`entry_by`,`entry_at`,`updated_by`,`updated_at`'
                                . 'FROM product as o '
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
                        ->query('SELECT `product_name`,`sku`,`description`,`category`,`price`,`image_link`,`entry_by`,`entry_at`,`updated_by`,`updated_at`'
                                . 'FROM product as o '
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
            `product_name`,`sku`,`description`,`category`,`price`,`image_link`,`entry_by`,`entry_at`,`updated_by`,`updated_at`
            FROM
                product
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
        return $this->delete('product',$id);
    }

}