<?php
	require 'vendor/autoload.php';

	use Dotenv\Dotenv;

	use App\Infrastructure\Services\DatabaseConnection;
	$dotenv = new DotEnv(__DIR__);
	$dotenv->load();
	
	$dbConn = (new DatabaseConnection())->pdo;
	
	//echo getenv('OKTAAUDIENCE');
