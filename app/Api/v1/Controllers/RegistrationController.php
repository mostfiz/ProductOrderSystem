<?php

namespace App\Api\v1\Controllers;
use App\Infrastructure\Abstracts\Controller;
use App\Infrastructure\Services\UserService;
use App\Infrastructure\Traits\Helper;


class RegistrationController extends Controller
{
    use Helper;
    private $db;
    private $requestMethod;
    private $userId;

    private $userService;
    private $data;

    /**
     * Controller Constructor
     */
    public function __construct($db, $requestMethod, $userId,$data)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->data = $data;

        $this->userService= new UserService($db,$data);
    }

    /**
     * Main Method to call All the services for product order
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->userService->createUserFromRequest();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

   

    

    
}
