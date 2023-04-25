<?php

namespace App\Http\Traits\GeneralResponse;

trait GeneralResponse {
    public function generalResponse($status = "" , $statusCode , $data)
    {
        return response()->json([
            'status'     =>$status,
            'statusCode' =>$statusCode,
            'data'       =>$data,
        ]);
    }
}

?>
