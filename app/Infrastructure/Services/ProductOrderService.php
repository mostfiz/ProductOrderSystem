<?php 
namespace App\Infrastructure\Services;

use App\Infrastructure\Traits\Helper;
use App\Infrastructure\Traits\Validator;
use App\Models\productOrderServiceModel;
use App\Infrastructure\Services\AuthanticateService;

class ProductOrderService{
        use Helper,Validator;
        private $productOrderServiceModel;
        private $db;
        private $jwt;
        private $authanticateService;
        private $data;


        /**
         * Constructor
         */
        public function __construct($db,$data)
        {
            $this->db = $db;
            $this->data = $data;
            $this->productOrderServiceModel= new ProductOrderServiceModel($db);
        }

        /**
         * Method getAllOrders from database.
         *
         * @return array
         * @access  public
         */
        public function getAllOrders()
        {
            $result = $this->productOrderServiceModel->getAll();  
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
           
        }
        
        /**
         * Method getOrder from database.
         *
         * @return array
         * @access  public
         */
        public function getOrder($id)
        {
            $result = $this->productOrderServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    
        /**
         * Method createOrderFromRequest in database.
         *
         * @return array
         * @access  public
         */
        public function createOrderFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateOrders($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->productOrderServiceModel->insert("orders",$input);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode($result);
            return $response;
        }
    
        /**
         * Method updateOrderFromRequest in database.
         *
         * @return array
         * @access  public
         */
        public function updateOrderFromRequest($id)
        {
            $result = $this->productOrderServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateOrders($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->productOrderServiceModel->update($id, $input);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        
        /**
         * Method deleteOrder in database.
         *
         * @return array
         * @access  public
         */
        public function deleteOrder($id)
        {
            $result = $this->productOrderServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $result = $this->productOrderServiceModel->delete('orders',$id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    }
?>