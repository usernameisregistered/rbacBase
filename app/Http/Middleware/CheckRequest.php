<?php

namespace App\Http\Middleware;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Cache;
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
        $tokenInfo = null;
        if($request->header("content-type") != 'application/json'){
            return response()->json([
                'message'=>'不允许的请求头部类型content-type',
                'returnCode' =>1004
            ]);
        }else if(!Str::endsWith($request->path(),'login/login')){
            if(!$request->header("token")){
                return response()->json([
                    'message'=>'缺少必要参数token',
                    'returnCode' =>1005
                ]);
            }else if(!$request->header("publicArgs")){
                return response()->json([
                    'message'=>'缺少必要参数publicArgs',
                    'returnCode' =>1005
                ]);
            }else if(!Cache::has($request->header("publicArgs"))){
                return response()->json([
                    'message'=>'token无效,请重新登录',
                    'returnCode' =>1005
                ]);
            }else if(Cache::get($request->header("publicArgs")) != $request->header("token")){
                return response()->json([
                    'message'=>'token不正确',
                    'returnCode' =>1005
                ]);
            }
            Cache::put($request->header("publicArgs"), $request->header("token"), now()->addMinutes(30));
            return $next($request);
        }else{
            return $next($request);
        }
    }
}
