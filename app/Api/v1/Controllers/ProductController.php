<?php
namespace App\Api\v1\Controllers;
use App\Infrastructure\Abstracts\Controller;
use App\Infrastructure\Services\ProductService;
use App\Infrastructure\Traits\Helper;

class ProductController extends Controller
{
    use Helper;
    private $db;
    private $requestMethod;
    private $productId;

    private $productService;
    private $jwt;

    /**
     * Controller constructor
     */
    public function __construct($db, $requestMethod, $productId,$jwt)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->productId = $productId;
        $this->jwt = $jwt;

        $this->productService = new ProductService($db,$jwt);
    }

    /**
     * Main Method to call All the services for product order
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->productId) {
                    $response = $this->productService->getProduct($this->productId);
                } else {
                    $response = $this->productService->getAllProducts();
                };
                break;
            case 'POST':
                $response = $this->productService->createProductFromRequest();
                break;
            case 'PUT':
                $response = $this->productService->updateProductFromRequest($this->productId);
                break;
            case 'DELETE':
                $response = $this->productService->deleteProduct($this->productId);
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
