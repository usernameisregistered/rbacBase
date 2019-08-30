<?php

namespace App\Http\Middleware;

use Closure;

class CheckRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header("content-type") != 'application/json'){
            return response()->json([
                'message'=>'不允许的请求头部类型content-type',
                'returnCode' =>1004
            ]);
        }else if(strpos($request->path(),'login/login') || strpos($request->path(),'login/register') ){
            if(!$request->header("publicArgs")){
                return response()->json([
                    'message'=>'缺少公参publicArgs',
                    'returnCode' =>1005
                ]);
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
    }
}
