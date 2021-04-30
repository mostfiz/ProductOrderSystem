<?php
namespace App\Api\v1\Controllers;
use App\Infrastructure\Abstracts\Controller;
use App\Infrastructure\Services\ProductOrderService;
use App\Infrastructure\Services\AuthanticateService;
use App\Infrastructure\Traits\Helper;

class ProductOrderController extends Controller
{
    use Helper;
    private $db;
    private $requestMethod;
    private $orderId;

    private $productOrderService;


    /**
     * Controller constructor
     */
    public function __construct($db, $requestMethod, $orderId,$data)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->orderId = $orderId;

        $this->productOrderService = new ProductOrderService($db,$data);
    }

    /**
     * Main Method to call All the services for product order
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->orderId) {
                    $response = $this->productOrderService->getOrder($this->orderId);
                } else {
                    $response = $this->productOrderService->getAllOrders();
                };
                break;
            case 'POST':
                $response = $this->productOrderService->createOrderFromRequest();
                break;
            case 'PUT':
                $response = $this->productOrderService->updateOrderFromRequest($this->orderId);
                break;
            case 'DELETE':
                $response = $this->productOrderService->deleteOrder($this->orderId);
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
