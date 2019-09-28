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
            if(!$request->header("token")){
                return response()->json([
                    'message'=>'缺少必要参数token',
                    'returnCode' =>1005
                ]);
            }
            try {
                $userInfo = json_decode(decrypt($request->header("token")));
            } catch (DecryptException $e) {
                return response()->json([
                    'message'=>'token无效',
                    'returnCode' =>1013
                ]);
            };
            $request->headers->set('userInfo',$userInfo);
            return $next($request);
        }else{
            return $next($request);
        }
    }
}
