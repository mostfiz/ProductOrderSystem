<?php


namespace App\Infrastructure\Traits;

use DateTime;

trait Helper {

    /**
     *     public function unprocessableEntityResponse()
     * 
     *
     * @return array
     * @access  public
     */
    public function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    /**
     *     public function notFoundResponse()
     * 
     *
     * @return array
     * @access  public
     */
    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $_response["status"]=404;
        $_response["message"]="Data not found!!";
        $response['body'] = json_encode($_response) ;
        return $response;
    }

    /**
     *     public function test_input()
     * 
     *
     * @return array
     * @access  public
     */
    public function test_input($data) {
	    $data = strip_tags($data);
	    $data = htmlspecialchars($data);
	    $data = stripslashes($data);
	    $data = trim($data);
	    return $data;
	}

}
