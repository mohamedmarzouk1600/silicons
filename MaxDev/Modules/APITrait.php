<?php

namespace MaxDev\Modules;

use MaxDev\Enums\ErrorCode;

trait APITrait
{
    public $systemLang;
    public $StatusCode = 200;
    public $Code = 100;

    /**
     * @param $StatusCode
     * @return $this
     */
    public function setStatusCode($StatusCode)
    {
        $this->StatusCode = $StatusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->StatusCode;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->Code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->Code;
    }

    /**
     * @param $condition
     * @param $truemsg
     * @param $falsemsg
     * @param bool $data
     * @return array
     */
    public function ReturnIfMethod($condition, $truemsg, $falsemsg, $data=false)
    {
        if ($condition) {
            return ['status'=>true,'message'=>$truemsg,'data'=>$data];
        } else {
            return ['status'=>false,'message'=>$falsemsg,'data'=>$data];
        }
    }

    /**
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWrongLogin($data, $message = 'Success')
    {
        return $this->setStatusCode(200)->setCode(101)->respondWithError($data, $message);
    }

    /**
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($data, $message = 'Success')
    {
        return $this->setStatusCode(200)->setCode(100)->respondWithoutError($data, $message);
    }

    /**
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responsePaginationSuccess($data, $message = 'Success')
    {
        return $this->setStatusCode(200)->setCode(100)->respondPaginationWithoutError($data, $message);
    }


    /**
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($data, $message = 'Not Found!')
    {
        return $this->setStatusCode(404)->setCode(104)->respondWithError($data, $message);
    }

    /**
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnAuthenticated($data, $message = 'Unauthenticated')
    {
        return $this->setStatusCode(401)->setCode(105)->respondWithError($data, $message);
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers=[])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $data
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondPaginationWithoutError($data, $message)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'code' => $this->getCode(),
            'data'   => $data->collection,
            'meta'   => [

                'current_page' => $data->currentPage(),
                'first_page_url' => $data->url(1),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'last_page_url' => $data->url($data->lastPage()),
                'next_page_url' => $data->nextPageUrl(),
                'per_page' => $data->perPage(),
                'prev_page_url' => $data->previousPageUrl(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ]
        ], $this->getStatusCode());
    }

    /**
     * @param $data
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithoutError($data, $message)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'code' => $this->getCode(),
            'data'=>!empty($data) ? $data : new \stdClass(),
        ], $this->getStatusCode());
    }

    public function respondAccountNotVerified($data=null)
    {
        return $this->setStatusCode(450)
            ->setCode(ErrorCode::ACCOUNT_NOT_VERIFIED)
            ->respondWithError($data, __('Account not verified'));
    }

    /**
     * @param $data
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($data, $message)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => $this->getCode(),
            'data'=>!empty($data) ? $data : new \stdClass()
        ], $this->getStatusCode());
    }

    /**
     * @param $validation
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function ValidationError($validation, $message)
    {
        $errorArray = $validation->errors()->messages();
        $data = array_column(array_map(function ($key, $val) {
            return ['key'=>$key,'val'=>implode('|', $val)];
        }, array_keys($errorArray), $errorArray), 'val', 'key');
        return $this->setCode(103)->respondWithError([$data], implode("\n", array_flatten($errorArray)));
    }
}
