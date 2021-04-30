<?php
namespace App\Api\v1\Controllers;
use App\Infrastructure\Abstracts\Controller;
use App\Infrastructure\Services\AuthanticateService;
use App\Infrastructure\Traits\Helper;

class AuthanticateController extends Controller
{
    use Helper;
    private $db;
    private $requestMethod;

    private $authanticateService;

    /**
     * Controller constructor
     */
    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->authanticateService = new AuthanticateService($db);
    }

    /**
     * Main Method to call All the services for product order
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            // case 'GET':
            //     if ($this->categorytId) {
            //         $response = $this->categoryService->getCategory($this->categorytId);
            //     } else {
            //         $response = $this->categoryService->getAllCategories();
            //     };
            //     break;
            case 'POST':
                $response = $this->authanticateService->createTokenFromRequest();
                break;
            // case 'PUT':
            //     $response = $this->categoryService->updateCategoryFromRequest($this->categorytId);
            //     break;
            // case 'DELETE':
            //     $response = $this->categoryService->deleteCategory($this->categorytId);
            //     break;
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
