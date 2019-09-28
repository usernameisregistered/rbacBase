<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    protected $key = '';

    protected $tokenInfo = null;

    protected $expireTime = 30 * 60 * 1000;
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
            $user = DB::table('managers')->select("manager_id as id","manager_name as name","manager_email as email","manager_phone as phone","manager_truename as truename","manager_isSystem as isSystem","group_id as groupId","manager_isEnabled as isEnabled","manager_disabled_description as disabledDescription","manager_disabled_time as disabledTime","manager_lastlogin_time as lastLoginTime","manager_lastlogin_ip as lastLoginIP","manager_register_time as registerTime")->where(
                [
                    ['manager_name', $request->input("username")],
                    ['manager_password', md5($request->input("password"))],
                ]
            )->first();
            if ($user) {
                if ($user->isEnabled == 0) {
                    return response()->json([
                        'message' => '你的账户已被禁用,禁用原因：' . $user->disabledDescription . ';禁用时间：' . $user->disabledTime . '请联系管理员解除禁用',
                        'returnCode' => 1002,
                    ]);
                }else{
                    $clientIp = $request->getClientIp();
                    $currentTime = time();
                    $tokenInfo = array("createTime"=>$currentTime,'expireTime'=>$this->expireTime,'isLogin'=>true,'userInfo'=>array('manager_id' => $user->id,'manager_name' => $user->name,'manager_email' => $user->email,'manager_phone' => $user->phone,'group_id' => $user->groupId));
                    $this->tokenInfo = encrypt(json_encode($tokenInfo));
                    $key = Str::random(40);
                    Cache::put($key, $this->tokenInfo, now()->addMinutes(30));
                    $result = DB::table('managers')->where('manager_id', $user->id)->update(array('manager_lastlogin_time' => date('Y-m-d H:i:s', $currentTime), 'manager_lastlogin_ip' => $clientIp));
                    if ($result) {
                        return response()->json([
                            'message' => '成功',
                            'returnCode' => 1000,
                            "dataInfo"=>[
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                                'truename' => $user->truename,
                                'groupId' => $user->groupId,
                                'lastLoginTime' => $user->lastLoginTime,
                                'lastLoginIP' => $user->lastLoginIP,
                                'token' => $this->tokenInfo,
                                'publicArgs' => $key,
                                'registerTime' => $user->registerTime,
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
                    'message' => '请输入正确的账户和密码',
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
        Cache::forget($request->header("publicArgs"));
        return response()->json([
            'message' => '成功',
            'returnCode' => 1009,
            "dataInfo"=>'您已退出登录'
        ]);
    }
}
