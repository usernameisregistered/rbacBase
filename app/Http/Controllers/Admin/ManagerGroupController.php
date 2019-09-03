<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManagerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $groupList = DB::table('manager_groups')->select('id as group_id','group_name','group_desc',DB::raw('if(group_isenabled =1,"启用","禁用") as group_isenabled'),'group_create_time','group_disabled_description','group_disabled_time','group_update_time')->get();
        return response()->json([
            'message' => '成功',
            "dataInfo"=>$groupList,
            'returnCode' => 1000,
        ]);
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
            if($request->input("group_name")){
                $this->isUnique('group_name',$request->input("group_name"));
                $insertDate['group_name'] = $request->input("group_name");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数group_name',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("group_desc")){
                $insertDate['group_desc'] = $request->input("group_desc");
            }
            if($request->input("manager_group")){
                $insertDate['manager_group'] = $request->input("manager_group");
            }
            if($request->input("group_isenabled")){
                $insertDate['group_isenabled'] = $request->input("group_isenabled");
                if($insertDate['group_isenabled'] == 0){
                    if($request->input("manager_disabled_description")){
                        $insertDate['group_disabled_description'] = $request->input("group_disabled_description");
                        $insertDate['group_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数group_disabled_description',
                            'returnCode' => 1008,
                        ]);
                    }
                }
            }else{
                $insertDate['group_isenabled'] = 1;
                $insertDate['group_disabled_description'] = '';
                $insertDate['group_disabled_time'] = '';
            }
            $insertDate['group_create_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('manager_groups')->insert($insertDate);
            if($result){
                return response()->json([
                    'message' => '添加管理员组成功',
                    'dataInfo'=>'',
                    'returnCode' => 1000,
                ]);
            }else{
                return response()->json([
                    'message' => '添加管理员组失败',
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
        if(!$request->input("group_id")){
            return response()->json([
                'message' => '缺少必要的参数group_id',
                'returnCode' => 1008,
            ]);
        }else{
            $managerInfo = DB::table('manager_groups')->where('id',$request->input("group_id"))->select('id as group_id','group_name','group_desc',DB::raw('if(group_isenabled =1,"启用","禁用") as group_isenabled'),'group_create_time','group_disabled_description','group_disabled_time','group_update_time')->first();
            if($managerInfo){
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
            if($request->input("group_name")){
                $this->isUnique('group_name',$request->input("group_name"));
                $updateDate['group_name'] = $request->input("group_name");
            }
            if($request->input("group_desc")){
                $updateDate['group_desc'] = $request->input("group_desc");
            }
            if($request->input("group_isenabled")){
                $updateDate['group_isenabled'] = $request->input("group_isenabled");
                if($updateDate['group_isenabled'] == 0){
                    if($request->input("group_disabled_description")){
                        $updateDate['group_disabled_description'] = $request->input("group_disabled_description");
                        $updateDate['group_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数group_disabled_description',
                            'returnCode' => 1008,
                        ]);
                    }
                }else{
                    $updateDate['group_disabled_description'] = '';
                    $updateDate['group_disabled_time'] = '';
                }
            }
            $updateDate['group_update_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('manager_groups')->where('id', $request->input("id"))->update($updateDate);
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
        if(!$request->input("group_id")){
            return response()->json([
                'message' => '缺少必要的参数group_id',
                'returnCode' => 1008,
            ]);
        }else{
            $this->isUsed($request->input("group_id"));
            $result = DB::table('manager_groups')->where('id', $request->input("group_id"))->delete();
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

    /**
     * 当前组是否被使用
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function isUsed($id){
        $result = DB::table('managers')->where('manager_group', 'like' ,$id)->exists();
        if($result){
            return response()->json([
                'message' => '您要修改的角色正在被使用',
                'returnCode' => 1013,
            ]);
        }
    }

    protected function isUnique($field,$value){
        $result = DB::table('manager_groups')->where($field, $value)->exists();
        if($result){
            return response()->json([
                'message' => '您要修改的数据已经存在',
                'returnCode' => 1012,
            ]);
        }
    }

}
