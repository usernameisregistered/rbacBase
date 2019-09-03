<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $key = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->key = substr(config('app.key'),7);
    }

    /**
     * 管理员登录
     * @return array
     */
    public function login(Request $request)
    {
        if (!$request->input("username")) {
            return response()->json([
                'message' => '缺少必要的参数username',
                'returnCode' => 1008,
            ]);
        } else if (!$request->input("password")) {
            return response()->json([
                'message' => '缺少必要的参数password',
                'returnCode' => 1008,
            ]);
        } else {
            $users = DB::table('managers')->where(
                [
                    ['manager_name', $request->input("username")],
                    ['manager_password', md5($request->input("password"))],
                    ['manager_isdelete', 0],
                ]
            )->first();
            if ($users) {
                if ($users->manager_isenabled == 0) {
                    return response()->json([
                        'message' => '你的账户已被禁用,禁用原因：' . $users->manager_disabled_description . ';禁用时间：' . $users->manager_disabled_time . '请联系管理员解除禁用',
                        'returnCode' => 1002,
                    ]);
                } else if($users->is_login == 1){
                    return response()->json([
                        'message' => '你已登录，请勿重复登录',
                        'returnCode' => 1009,
                    ]);
                }else if ($users->manager_isenabled == 1) {
                    $manager_token = md5($users->id.$this->key);
                    $clientIp = $request->getClientIp();
                    $currentTime = time();
                    $result = DB::table('managers')->where('id', $users->id)->update(['manager_lastlogin_time' => date('Y-m-d H:i:s', $currentTime), 'manager_lastlogin_ip' => $clientIp, 'manager_token' => $manager_token,'is_login'=>1]);
                    if ($result) {
                        $_SERVER['publicArgs']= $manager_token;
                        return response()->json([
                            'message' => '成功',
                            'returnCode' => 1009,
                            "dataInfo"=>[
                                'manager_id' => $users->id,
                                'manager_name' => $users->manager_name,
                                'manager_email' => $users->manager_email,
                                'manager_phone' => $users->manager_phone,
                                'manager_truename' => $users->manager_truename,
                                'manager_group' => $users->manager_group,
                                'manager_lastlogin_time' => $currentTime,
                                'manager_lastlogin_ip' => $clientIp,
                                'manager_token' => $manager_token,
                                'manager_register_time' => $users->manager_register_time,
                            ]
                        ]);
                    } else {
                        return response()->json([
                            'message' => '数据更新失败',
                            'returnCode' => 1003,
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'message' => '没有查询到相应的信息',
                    'returnCode' => 1001,
                ]);
            }

        }
    }

    /**
     * 管理员退出
     * @return array
     */
    public function logout(Request $request)
    {
        if (!$request->input("userId")) {
            return response()->json([
                'message' => '缺少必要的参数userId',
                'returnCode' => 1008,
            ]);
        } else{
            $users = DB::table('managers')->where('id', $request->input("userId"))->first();
            if($users){
                if($users->is_login == 0){
                    return response()->json([
                        'message' => '您尚未登录',
                        'returnCode' => 1010,
                    ]);
                }else{
                    $result = DB::table('managers')->where('id', $users->id)->update(['manager_token' => '','is_login'=>0]);  
                    if ($result) {
                        return response()->json([
                            'message' => '成功',
                            'returnCode' => 1009,
                            "dataInfo"=>'您已退出登录'
                        ]);
                    } else {
                        return response()->json([
                            'message' => '数据更新失败',
                            'returnCode' => 1003,
                        ]);
                    }
                }
            }else {
                return response()->json([
                    'message' => '没有查询到相应的信息',
                    'returnCode' => 1001,
                ]);
            }
        } 
    }
}
