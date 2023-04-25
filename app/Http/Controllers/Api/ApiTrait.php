<?php

namespace App\Http\Controllers\Api;

trait ApiTrait{

    public function ApiResponse($msg =[] , $statusCode , $data)
    {
        return response()->json([

            'Response' => [
            'msg'=>$msg,
            'statusCode' =>$statusCode,
            'data'       =>$data,
            ]
        ]);
    }
}

?>
