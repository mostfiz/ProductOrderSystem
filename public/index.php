<?php
require "../bootstrap.php";
use App\Api\v1\Controllers\ProductOrderController;
use App\Api\v1\Controllers\UserController;
use App\Api\v1\Controllers\ProductController;
use App\Api\v1\Controllers\CategoryController;
use App\Api\v1\Controllers\AuthanticateController;
use App\Api\v1\Controllers\RegistrationController;
use App\Infrastructure\Services\AuthanticateService;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
$jwt= "";


$requestMethod = $_SERVER["REQUEST_METHOD"];

$authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : "";
$token = null;
$headers = apache_request_headers();
if(isset($headers['Authorization'])){
	$arr = explode(" ", $headers['Authorization']);
	$jwt = $arr[1];
} 

// all of our endpoints start with /orders
// everything else results in a 404 Not Found

if ($uri[1] == 'auth') {
	
	// pass the request method and order ID to the ProductOrderController and process the HTTP request:
	$controller = new AuthanticateController($dbConn, $requestMethod);
	$controller->processRequest();
	
}
elseif($uri[1] == 'Registration')
{
	// the order id is, of course, optional and must be a number:
	$userId = null;
	if (isset($uri[2])) {
		$userId = (int) $uri[2];
	}
	// pass the request method and user ID to the UserController and process the HTTP request:
	$controller = new RegistrationController($dbConn, $requestMethod, $userId,array());
	$controller->processRequest();
}
elseif($jwt !="")
{
	$SECRET_KEY = "gAtXRx5UHgKx1vY4isYivNCJtS583Kuc";
	$result  = (new AuthanticateService($dbConn))->checkToken($jwt,$SECRET_KEY);
	if(isset($result))
	{
		if($result["status"] == 200)
		{
			if ($uri[1] == 'orders') {
				// the order id is, of course, optional and must be a number:
				$orderId = null;
				if (isset($uri[2])) {
					$orderId = (int) $uri[2];
				}
				// pass the request method and order ID to the ProductOrderController and process the HTTP request:
				$controller = new ProductOrderController($dbConn, $requestMethod, $orderId,$result['data']);
				$controller->processRequest();
			
			}
			elseif($uri[1] == 'users')
			{
				// the order id is, of course, optional and must be a number:
				$userId = null;
				if (isset($uri[2])) {
					$userId = (int) $uri[2];
				}
				// pass the request method and user ID to the UserController and process the HTTP request:
				$controller = new UserController($dbConn, $requestMethod, $userId,$result['data']);
				$controller->processRequest();
			}
			elseif($uri[1] == 'category')
			{
				// the category id is, of course, optional and must be a number:
				$categoryId = null;
				if (isset($uri[2])) {
					$categoryId = (int) $uri[2];
				}
				// pass the request method and order ID to the CategoryController and process the HTTP request:
				$controller = new CategoryController($dbConn, $requestMethod, $categoryId,$result['data']);
				$controller->processRequest();
			}
			elseif($uri[1] == 'product')
			{
				// the product id is, of course, optional and must be a number:
				$productId = null;
				if (isset($uri[2])) {
					$productId = (int) $uri[2];
				}
				// pass the request method and order ID to the ProductController and process the HTTP request:
				$controller = new ProductController($dbConn, $requestMethod, $productId,$result['data']);
				$controller->processRequest();
			}
			else{
				header("HTTP/1.1 404 Not Found");
                echo json_encode($result);
				exit();
			}
		}
		else
		{
			header("HTTP/1.1 401 Not Found");
			echo json_encode($result);
			exit();
		}
	}
	else
	{
		header("HTTP/1.1 401 Not Found");
		echo json_encode($result);
		exit();
	}

}
else
{
	header("HTTP/1.1 401 Not Found");
    exit();
}




