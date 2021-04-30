<?php
namespace App\Api\v1\Controllers;
use App\Infrastructure\Abstracts\Controller;
use App\Infrastructure\Services\CategoryService;
use App\Infrastructure\Traits\Helper;

class CategoryController extends Controller
{
    use Helper;
    private $db;
    private $requestMethod;
    private $categorytId;

    private $categoryService;
    private $jwt;

    /**
     * Controller constructor
     */
    public function __construct($db, $requestMethod, $categorytId,$jwt)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->categorytId = $categorytId;
        $this->jwt = $jwt;

        $this->categoryService = new CategoryService($db,$jwt);
    }

    /**
     * Main Method to call All the services for product order
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->categorytId) {
                    $response = $this->categoryService->getCategory($this->categorytId);
                } else {
                    $response = $this->categoryService->getAllCategories();
                };
                break;
            case 'POST':
                $response = $this->categoryService->createCategoryFromRequest();
                break;
            case 'PUT':
                $response = $this->categoryService->updateCategoryFromRequest($this->categorytId);
                break;
            case 'DELETE':
                $response = $this->categoryService->deleteCategory($this->categorytId);
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
