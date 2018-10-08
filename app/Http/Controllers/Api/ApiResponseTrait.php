<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;

trait ApiResponseTrait{

    public function apiResponse($data = null, $error = null, $code = 200)
    {
        $array = [
            'data' => $data,
            'status' => in_array($code, $this->successCode()) ? true : false,
            'error' =>$error
        ];

        return response($array, $code);
    }

    public function successCode()
    {
        return [
            200, 201, 202
        ];
    }

    public function notFoundResponse()
    {
        return $this->apiResponse(null, 'not found', 404  );
    }

    public function createdResponse($data)
    {
        return $this->apiResponse($data, null, 201);
    }

    public function deleteResponse()
    {
        return $this->apiResponse(true, null, 200);
    }

    public function apiValidation($request, $array)
    {
        //validate update request
        $validate = Validator::make($request->all(), $array);
        //if request fails return to create post
        if($validate->fails()){
            return $this->apiResponse(null, $validate->errors(), 422);

        }
    }

}