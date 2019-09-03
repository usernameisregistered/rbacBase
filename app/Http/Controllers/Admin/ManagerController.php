<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    protected $groupList = null;
    public function __construct(){
        $this->groupList =  DB::table('manager_groups')->where('group_isenabled',1)->select('id','group_name')->get(); 
    }
    /**
     * Display a listing of the resource.
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
            $list = DB::table('managers')->where('manager_isdelete',0)->select('id as manager_id','manager_name','manager_email','manager_phone','manager_truename','manager_group', DB::raw('if(manager_isenabled =1,"启用","禁用") as manager_isenabled'),'manager_lastlogin_time','manager_lastlogin_ip','manager_register_time')->paginate($request->input("offset"))->toArray();        
            foreach($list['data'] as $value){
                $tempList = array();
                if(strpos($value->manager_group,',')){
                    $tempList = explode(',',$value->manager_group);
                }else{
                    array_push($tempList,$value->manager_group);
                }
                $result = array();
                foreach($tempList as $v){
                    foreach($this->groupList as $item){
                        if($item->id == $v ){
                            array_push($result,$item->group_name);
                        }
                    }
                }
                $value->manager_group = implode(',',$result);
            }
            return response()->json([
                'message' => '成功',
                "dataInfo"=>$list,
                'returnCode' => 1000,
            ]);
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insertDate = array();
        if(!$request->input("manager_id")){
            return response()->json([
                'message' => '缺少必要的参数manager_id',
                'returnCode' => 1008,
            ]);
        }else{
            if($request->input("manager_name")){
                $this->isUnique('manager_name',$request->input("manager_name"));
                $insertDate['updateDate'] = $request->input("manager_name");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数manager_name',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("manager_email")){
                $this->isUnique('manager_email',$request->input("manager_email"));
                $insertDate['manager_email'] = $request->input("manager_email");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数manager_email',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("manager_email")){
                $this->isUnique('manager_email',$request->input("manager_email"));
                $insertDate['manager_email'] = $request->input("manager_email");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数manager_email',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("manager_password")){
                $insertDate['manager_password'] = md5($request->input("manager_password"));
            }else{
                return response()->json([
                    'message' => '缺少必要的参数manager_password',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("manager_truename")){
                $insertDate['manager_truename'] = $request->input("manager_truename");
            }
            if($request->input("manager_group")){
                $insertDate['manager_group'] = $request->input("manager_group");
            }
            if($request->input("manager_isenabled")){
                $insertDate['manager_isenabled'] = $request->input("manager_isenabled");
                if($insertDate['manager_isenabled'] == 0){
                    if($request->input("manager_disabled_description")){
                        $insertDate['manager_disabled_description'] = $request->input("manager_disabled_description");
                        $insertDate['manager_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数manager_disabled_description',
                            'returnCode' => 1008,
                        ]);
                    }
                }
            }else{
                $insertDate['manager_isenabled'] = 1;
                $insertDate['manager_disabled_description'] = '';
                $insertDate['manager_disabled_time'] = '';
            }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(!$request->input("manager_id")){
            return response()->json([
                'message' => '缺少必要的参数manager_id',
                'returnCode' => 1008,
            ]);
        }else{
            $managerInfo = DB::table('managers')->where('id', $request->input("manager_id"))->select('id as manager_id','manager_name','manager_email','manager_phone','manager_truename','manager_group', DB::raw('if(manager_isenabled =1,"启用","禁用") as manager_isenabled'),'manager_lastlogin_time','manager_lastlogin_ip','manager_register_time','manager_update_time','manager_disabled_description','manager_disabled_time')->first();
            if($managerInfo){
                $tempList = array();
                if(strpos($managerInfo->manager_group,',')){
                    $tempList = explode(',',$managerInfo->manager_group);
                }else{
                    array_push($tempList,$managerInfo->manager_group);
                }
                $result = array();
                foreach($tempList as $v){
                    foreach($this->groupList as $item){
                        if($item->id == $v ){
                            array_push($result,$item->group_name);
                        }
                    }
                }
                $managerInfo->manager_group = implode(',',$result);
                return response()->json([
                    'message' => '成功',
                    'dataInfo'=>$managerInfo,
                    'returnCode' => 1000,
                ]);
            }else{
                return response()->json([
                    'message' => '没有查询到相应的信息',
                    'dataInfo'=>'',
                    'returnCode' => 1001,
                ]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $updateDate = array();
        if(!$request->input("manager_id")){
            return response()->json([
                'message' => '缺少必要的参数manager_id',
                'returnCode' => 1008,
            ]);
        }else{
            if($request->input("manager_name")){
                $this->isUnique('manager_name',$request->input("manager_name"));
                $updateDate['updateDate'] = $request->input("manager_name");
            }
            if($request->input("manager_email")){
                $this->isUnique('manager_email',$request->input("manager_email"));
                $updateDate['manager_email'] = $request->input("manager_email");
            }
            if($request->input("manager_phone")){
                $this->isUnique('manager_phone',$request->input("manager_phone"));
                $updateDate['manager_phone'] = $request->input("manager_phone");
            }
            if($request->input("manager_truename")){
                $updateDate['manager_truename'] = $request->input("manager_truename");
            }
            if($request->input("manager_group")){
                $updateDate['manager_group'] = $request->input("manager_group");
            }
            if($request->input("manager_isenabled")){
                $updateDate['manager_isenabled'] = $request->input("manager_isenabled");
                if($updateDate['manager_isenabled'] == 0){
                    if($request->input("manager_disabled_description")){
                        $updateDate['manager_disabled_description'] = $request->input("manager_disabled_description");
                        $updateDate['manager_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数manager_disabled_description',
                            'returnCode' => 1008,
                        ]);
                    }
                }else{
                    $updateDate['manager_disabled_description'] = '';
                    $updateDate['manager_disabled_time'] = '';
                }
            }
            $updateDate['manager_update_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('managers')->where('id', $request->input("manager_id"))->update($updateDate);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->input("manager_id")){
            return response()->json([
                'message' => '缺少必要的参数manager_id',
                'returnCode' => 1008,
            ]);
        }else{
            $result = DB::table('managers')->where('id', $request->input("manager_id"))->delete();
            if($result){
                return response()->json([
                    'message' => '删除成功',
                    'dataInfo'=>'',
                    'returnCode' => 1000,
                ]);
            }else{
                return response()->json([
                    'message' => '没有查询到相应的信息',
                    'dataInfo'=>'',
                    'returnCode' => 1001,
                ]);
            }
        }
    }
    
    protected function isUnique($field,$value){
        $result = DB::table('managers')->where($field, $value)->exists();
        if($result){
            return response()->json([
                'message' => '您要修改的数据已经存在',
                'returnCode' => 1012,
            ]);
        }
    }

}
