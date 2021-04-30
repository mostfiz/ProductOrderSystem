<?php 
    namespace App\Infrastructure\Services;

    use App\Infrastructure\Traits\Helper;
    use App\Infrastructure\Traits\Validator;
    use App\Models\UserServiceModel;

class UserService{
        use Helper,Validator;
        private $userServiceModel;
        private $db;
        private $data;

        /**
         * Constructor
         */
        public function __construct($db,$data)
        {
            $this->db = $db;
            $this->data = $data;
    
            $this->userServiceModel= new UserServiceModel($db);
        }

        /**
         * Method getAllUsers from database.
         *
         * @return array
         * @access  public
         */
        public function getAllUsers()
        {
            $result = $this->userServiceModel->getAll();
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
        public function getUser($id)
        {
            $result = $this->userServiceModel->find($id);
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
        public function createUserFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateUsers($input)) {
                return $this->unprocessableEntityResponse();
            }
            if(isset($input['password']))
            {
                $input['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            $result = $this->userServiceModel->insert("user",$input);
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
        public function updateUserFromRequest($id)
        {
            $result = $this->userServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateUsers($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->userServiceModel->update($id, $input);
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
        public function deleteUser($id)
        {
            $result = $this->userServiceModel->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $result = $this->userServiceModel->delete('user',$id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
    }
?>