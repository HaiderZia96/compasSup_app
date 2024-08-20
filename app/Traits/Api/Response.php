<?php

namespace App\Traits\Api;

use Illuminate\Support\Facades\Config;

trait Response
{
    public $response;
    public $status;

    public function setResponse($data)
    {


        $appName = Config::get('apiResponse.message' . '.' . $data["status_code"]);

        if (isset($data["code"])) {
            $appName = Config::get('apiResponse.message' . '.' . $data["code"]);
        }


        $this->status = ['status_code' => (!isset($data['status_code'])) ? '' : $data['status_code']];


        if (isset($data['auth_token'])) {
            $this->response = [
                'success'=> (isset($data['code']) && ($data['code'] == '100200'))? true : false,
                'message' =>(!isset($data['success']))?[]:$data['success'][0],
                'token' => (!isset($data['auth_token'])) ? [] :$data['auth_token'],
                'data' => (!isset($data['data'])) ? [] : $data['data']
            ];
        }
        else if (isset($data['notification'])) {
            $this->response = [
                'success'=> (isset($data['code']) && ($data['code'] == '100200'))? true : false,
                'message' =>(!isset($data['success']))?[]:$data['success'][0],
                'data' => (!isset($data['data'])) ? [] : $data['data'],
                'notification' => (!isset($data['notification'])) ? [] : $data['notification']
            ];
        }
        else{
            $this->response = [
                'success'=> (isset($data['code']) && ($data['code'] == '100200'))? true : false,
                'message' =>(!isset($data['success']))?[]:$data['success'][0],
                'data' => (!isset($data['data'])) ? [] : $data['data']
            ];

        }

        return $this->response;

    }

    public function getResponse()
    {
        return response()->json($this->response, $this->status['status_code']);

    }


}
