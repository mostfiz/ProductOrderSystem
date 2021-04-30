<?php 
    namespace App\Infrastructure\Services;

    use App\Infrastructure\Traits\Helper;
    use App\Infrastructure\Traits\Validator;
    use App\Models\CategoryServiceModel;

class CategoryService{
        use Helper,Validator;
        private $categoryServiceModel;
        private $db;
        private $jwt;

        /**
         * Constructor
         */
        public function __construct($db,$jwt)
        {
            $this->db = $db;
            $this->jwt = $jwt;
    
            $this->categoryServiceModel= new CategoryServiceModel($db);
        }

        /**
         * Method getAllUsers from database.
         *
         * @return array
         * @access  public
         */
        public function getAllCategories()
        {
            $result = $this->categoryServiceModel->getAll();
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
        public function getCategory($id)
        {
            $result = $this->categoryServiceModel->find($id);
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
        public function createCategoryFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateCategory($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->categoryServiceModel->insert("category",$input);
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
        public function updateCategoryFromRequest($id)
        {
            $result = $this->categoryServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateCategory($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->categoryServiceModel->update($id, $input);
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
        public function deleteCategory($id)
        {
            $result = $this->categoryServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $result = $this->categoryServiceModel->delete('category',$id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    }
?>