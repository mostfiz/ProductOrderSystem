<?php 
    namespace App\Infrastructure\Services;

    use App\Infrastructure\Traits\Helper;
    use App\Infrastructure\Traits\Validator;
    use App\Models\ProductServiceModel;

class ProductService{
        use Helper,Validator;
        private $productServiceModel;
        private $db;
        private $jwt;

        /**
         * Constructor
         */
        public function __construct($db,$jwt)
        {
            $this->db = $db;
            $this->jwt = $jwt;
    
            $this->productServiceModel= new productServiceModel($db);
        }

        /**
         * Method getAllUsers from database.
         *
         * @return array
         * @access  public
         */
        public function getAllProducts()
        {
            $result = $this->productServiceModel->getAll();
            if (count($result)<=0) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        
        /**
         * Method getUser from database.
         *
         * @return array
         * @access  public
         */
        public function getProduct($id)
        {
            $result = $this->productServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    
        /**
         * Method createUserFromRequest in database.
         *
         * @return array
         * @access  public
         */
        public function createProductFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateProduct($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->productServiceModel->insert("product",$input);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode($result);
            return $response;
        }
    
        /**
         * Method updateUserFromRequest in database.
         *
         * @return array
         * @access  public
         */
        public function updateProductFromRequest($id)
        {
            $result = $this->productServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateProduct($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->productServiceModel->update($id, $input);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        
        /**
         * Method deleteUser in database.
         *
         * @return array
         * @access  public
         */
        public function deleteProduct($id)
        {
            $result = $this->productServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $result = $this->productServiceModel->delete('product',$id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    }
?>