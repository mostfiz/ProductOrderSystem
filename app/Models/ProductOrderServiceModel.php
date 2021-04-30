<?php 
namespace App\Models;

use App\Infrastructure\Abstracts\Model;
use App\Infrastructure\Traits\Validator;

class ProductOrderServiceModel extends Model{


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
       return $this->insert('orders',$data);
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
            UPDATE orders
            SET 
                product_id = :product_id,
                quantity  = :quantity,
                amount = :amount,
                order_status = :order_status,
                total_amount = :total_amount,
                updated_by = :updated_by
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'product_id' => $input['product_id'],
                'quantity'  => $input['quantity'],
                'amount' => $input['amount'] ?? 0.00,
                'order_status'  => $input['order_status'],
                'total_amount' => $input['total_amount'] ?? 0.00,
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
                        ->query('SELECT * FROM orders')
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
                        ->query('SELECT `product_id`,`quantity`,`amount`,`ordered_by`,`ordered_at`'
                                . 'FROM orders as o '
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
                        ->query('SELECT `product_id`,`quantity`,`amount`,`ordered_by`,`ordered_at`'
                                . 'FROM orders as o '
                                . 'WHERE (o.ordered_at >= ' . " '$from' AND o.ordered_at <= " . " '$to') 
                                OR o.ordered_by = "."'$user_id'" .' ORDER BY id DESC ')
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
            `product_id`,`quantity`,`amount`,`total_amount`,`order_status`,`ordered_by`,`ordered_at`
            FROM
                orders
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
        return $this->delete('orders',$id);
    }

}