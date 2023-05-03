<?php 
namespace App\Http\Controllers\Api;


trait ApiResponseTrait {

    public function apiResponse($data = '' , $msg = '' , $status = '')
    {
        $header = [
            'data'   => $data,
            'msg'    => $msg,
            'status' => $status,
        ];

        return response($header);
    }
}
?>