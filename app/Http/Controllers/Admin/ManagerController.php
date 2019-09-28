<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ManagerController extends Controller
{
    /**
     * 管理员列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if(!($request->input("offset") && $request->input("page"))){
            return response()->json([
                'message' => '缺少必要的参数page或者offset',
                'returnCode' => 1008,
            ]);
        }else{
            $list = DB::table('managers')->select('manager_id as id','manager_name as name','manager_email as email','manager_phone as phone','manager_truename as truename','manager_groups.group_name as groupNname', DB::raw('if(manager_isenabled =1,"启用","禁用") as isEnabled'),"manager_disabled_description as disabledDdescription","manager_disabled_time as disabledTime",'manager_lastlogin_time as lastLoginTime','manager_lastlogin_ip as lastLoginIP','manager_register_time as registerTime',"manager_update_time as updateTime")->leftJoin('manager_groups', 'managers.group_id', '=', 'manager_groups.group_id')->orderBy('manager_register_time', 'desc')->take($request->input("offset"))->skip(($request->input("page") -1) * $request->input("offset"))->get();  
            return response()->json([
                'message' => '成功',
                "dataInfo"=>array("list"=>$list,'total'=> DB::table('managers')->count(),'page'=>$request->input("page"),"offset"=>$request->input("offset")),
                'returnCode' => 1000,
            ]);
        } 
    }

    /**
     * 创建一个管理员
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insertDate = array();
        if($request->input("name")){
            $info = $this->isUnique('manager_name',$request->input("name"));
            if($info){
                return response()->json([
                    'message' => '你传入的参数name的值已存在',
                    'returnCode' => 1011,
                ]);
            }
            $insertDate['manager_name'] = $request->input("name");
        }else{
            return response()->json([
                'message' => '缺少必要的参数name',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("email")){
            $info = $this->isUnique('manager_email',$request->input("email"));
            if($info){
                return response()->json([
                    'message' => '你传入的参数email的值已存在',
                    'returnCode' => 1011,
                ]);
            }
            $insertDate['manager_email'] = $request->input("email");
        }else{
            return response()->json([
                'message' => '缺少必要的参数email',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("phone")){
            $info = $this->isUnique('manager_phone',$request->input("phone"));
            if($info){
                return response()->json([
                    'message' => '你传入的参数phone的值已存在',
                    'returnCode' => 1011,
                ]);
            }
            $insertDate['manager_phone'] = $request->input("phone");
        }else{
            return response()->json([
                'message' => '缺少必要的参数phone',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("password")){
            $insertDate['manager_password'] = md5($request->input("password"));
        }else{
            return response()->json([
                'message' => '缺少必要的参数password',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("truename")){
            $insertDate['manager_truename'] = $request->input("truename");
        }
        if($request->input("groupId")){
            $insertDate['group_id'] = $request->input("groupId");
        }else{
            return response()->json([
                'message' => '缺少必要的参数groupId',
                'returnCode' => 1008,
            ]);
        }
        $insertDate['manager_id'] = md5(Str::uuid());
        $insertDate['manager_isenabled'] = 1;
        $insertDate['manager_register_time'] = date('Y-m-d H:i:s', time());
        $result = DB::table('managers')->insert($insertDate);
        if($result){
            return response()->json([
                'message' => '添加管理员成功',
                'dataInfo'=>'',
                'returnCode' => 1000,
            ]);
        }else{
            return response()->json([
                'message' => '添加管理员失败',
                'dataInfo'=>'',
                'returnCode' => 1003,
            ]);
        }
    }

    /**
     * 更新管理员的信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $updateDate = array();
        if(!$request->input("id")){
            return response()->json([
                'message' => '缺少必要的参数id',
                'returnCode' => 1008,
            ]);
        }else{
            $userInfo = (Array)DB::table('managers')->select("manager_name","manager_email","manager_phone","manager_password","manager_truename","group_id","manager_isEnabled","manager_disabled_description","manager_disabled_time")->where('manager_id', $request->input("id"))->first();
            if(!$userInfo){
                return response()->json([
                    'message' => '你传入的id有误',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("name")){
                $updateDate['manager_name'] = $request->input("name");
            }
            if($request->input("email")){
                $updateDate['manager_email'] = $request->input("email");
            }
            if($request->input("phone")){
                $updateDate['manager_phone'] = $request->input("phone");
            }
            if($request->input("password")){
                $updateDate['manager_password'] = md5($request->input("password"));
            }
            if($request->input("truename")){
                $updateDate['manager_truename'] = $request->input("truename");
            }
            if($request->input("groupId")){
                $updateDate['group_id'] = $request->input("groupId");
            }
            if($request->input("isEnabled") || $request->input("isEnabled") == 0 ){
                $updateDate['manager_isEnabled'] = $request->input("isEnabled");
                if($updateDate['manager_isEnabled'] == 0){
                    if($request->input("disabledDescription")){
                        $updateDate['manager_disabled_description'] = $request->input("disabledDescription");
                        $updateDate['manager_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数disabledDescription',
                            'returnCode' => 1008,
                        ]);
                    }
                }else{
                    $updateDate['manager_disabled_description'] = null;
                    $updateDate['manager_disabled_time'] = null;
                }
            }
            $updateData = $this->isChange($updateDate,$userInfo);
            if(!count($updateData)){
                return response()->json([
                    'message' => '没有要修改的数据',
                    'dataInfo'=>'',
                    'returnCode' => 1000,
                ]);
            }else{
                foreach ($updateData as $key=>$value){
                    if(in_array($key,['manager_name','manager_email',"manager_phone"])){
                        $info = $this->isUnique($key,$value);
                        if($info){
                            return response()->json([
                                'message' => '你传入的参数'.substr($key,8).'的值已存在',
                                'returnCode' => 1011,
                            ]);
                        }
                    }
                }
                $updateData['manager_update_time'] = date('Y-m-d H:i:s', time());
                $result = DB::table('managers')->where('manager_id', $request->input("id"))->update($updateData);
                if($result){
                    return response()->json([
                        'message' => '修改成功',
                        'dataInfo'=>'',
                        'returnCode' => 1000,
                    ]);
                }else{
                    return response()->json([
                        'message' => '数据更新失败',
                        'dataInfo'=>'',
                        'returnCode' => 1003,
                    ]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->input("id")){
            return response()->json([
                'message' => '缺少必要的参数id',
                'returnCode' => 1008,
            ]);
        }else{
            $result = (Array)DB::table('managers')->where('manager_id', $request->input("id"))->first();
            if(!$result){
                if($result["manager_isSystem"] == 1){
                    return response()->json([
                        'message' => '你无法删除内置账户',
                        'dataInfo'=>'',
                        'returnCode' => 1003,
                    ]); 
                }else{
                    $res = DB::table('managers')->where('manager_id', $request->input("id"))->delete();
                    if($res){
                        return response()->json([
                            'message' => '删除成功',
                            'dataInfo'=>'',
                            'returnCode' => 1000,
                        ]);
                    }else{
                        return response()->json([
                            'message' => '删除失败',
                            'dataInfo'=>'',
                            'returnCode' => 1003,
                        ]);
                    }
                }
                
            }else{
                return response()->json([
                    'message' => '没有查询到相应的信息你传入的参数有误',
                    'dataInfo'=>'',
                    'returnCode' => 1001,
                ]);
            }
        }
    }
    
    protected function isUnique($field,$value){
        return !!DB::table('managers')->where($field, $value)->exists();
    }

    protected function isChange($arr1,$arr2){
        $result = array();
        foreach($arr1 as $key=>$value){
            if($arr2[$key] != $value){
                $result[$key] = $value;
            }
        }
        return $result;
    }

}
