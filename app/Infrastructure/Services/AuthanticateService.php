<?php 
    namespace App\Infrastructure\Services;

    use App\Infrastructure\Traits\Helper;
    use App\Infrastructure\Traits\Validator;
    use App\Models\UserServiceModel;
    use \Firebase\JWT\JWT;

class AuthanticateService{
        use Helper,Validator;
        private $userServiceModel;
        private $db;

        /**
         * Constructor
         */
        public function __construct($db)
        {
            $this->db = $db;
    
            $this->userServiceModel= new UserServiceModel($db);
        }
        
    
        /**
         * Method createUserFromRequest in database.
         *
         * @return array
         * @access  public
         */
        public function createTokenFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateUserData($input)) {
                return $this->unprocessableEntityResponse();
            }
            $result = $this->userServiceModel->findByIndividual($this->test_input($input["username"]));
            if (! $result) {
                return $this->notFoundResponse();
            }
            $result = $this->generateToken($input['password'],$result[0]);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 Created';
            $response['body'] = $result;
            return $response;
        }
        
        /**
         * Method generateToken in database.
         *
         * @return array
         * @access  public
         */
        private function generateToken($password,$row)
        {
            $id = $row['id'];
            $firstname = $row['first_name'];
            $lastname = $row['last_name'];
            $email = $row['email'];
            $usertype = $row['user_type'];
            $password2 = $row['password'];
        
            if(password_verify($password, $password2))
            {
                $secret_key = "gAtXRx5UHgKx1vY4isYivNCJtS583Kuc";
                $issuer_claim = "mostafiz"; // this can be the servername
                $audience_claim = "Rony";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 600; // expire time in seconds
                $token = array(
                    "iss" => $issuer_claim,
                    "aud" => $audience_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "id" => $id,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "email" => $email,
                        "usertype"=> $usertype
                ));
        
        
                $jwt = JWT::encode($token, $secret_key);
                return json_encode(
                    array(
                        "status" => 200,
                        "message" => "Successful login.",
                        "jwt" => $jwt,
                        "expireAt" => $expire_claim
                    ));
            }
            else{
                return json_encode(array("status" => 401,"message" => "Login failed."));
            }
        }

        /**
         * Method checkToken in database.
         *
         * @return array
         * @access  public
         */
        public function checkToken($jwt,$secret_key)
        {
            try {

                $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        
                // Access is granted. Add code of the operation here 
        
                return array(
                    "status" => 200,
                    "message" => "Access granted:",
                    "data"=> $decoded->data
                );
        
            }catch (\Exception $e){
        
        
            return array(
                "status" => 401,
                "message" => "Access denied.",
                "error" => $e->getMessage()
            );
            }
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