<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthToken
{
    use \App\Traits\Api\Response;
    public $data;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('sanctum')->check()) {
            $this->data=['status_code'=>200,
                'code' => 100401,
                "success"=>["token is invalid."],
                'data'=>[]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


//        if (!Auth::check()) {
//            $this->data=['status_code'=>200,
//                'code' => 100401,
//                "success"=>["token is incorrect"],
//                'data'=>[]
//            ];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }

        return $next($request);
    }
}
