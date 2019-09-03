<?php

namespace App\Http\Middleware;
use Illuminate\Support\Str; 
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
        }else if(!(strpos($request->path(),'login/login') || strpos($request->path(),'login/register'))){
            if(!$request->input("userId")){
                return response()->json([
                    'message'=>'缺少必要参数userId',
                    'returnCode' =>1005
                ]);
            }else if(!$request->header("publicArgs")){
                return response()->json([
                    'message'=>'缺少公参publicArgs',
                    'returnCode' =>1005
                ]);
            }else if($request->header("publicArgs") != md5($request->input("userId").substr(config('app.key'),7))){
                return response()->json([
                    'message'=>'公参publicArgs信息内容有误',
                    'returnCode' =>1006
                ]);
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
    }
}
