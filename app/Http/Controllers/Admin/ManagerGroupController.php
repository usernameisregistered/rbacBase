<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ManagerGroupController extends Controller
{
    /**
     * 角色列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $groupList = DB::table('manager_groups')->select('group_id as groupId','group_name as groupName','group_desc as groupDesc',DB::raw('if(group_isEnabled =1,"启用","禁用") as isEnabled'),'group_create_time as createTime','group_disabled_description as disabledDescription','group_disabled_time as disabledTime','group_update_time as updateTime')->get();
        return response()->json([
            'message' => '成功',
            "dataInfo"=>$groupList,
            'returnCode' => 1000,
        ]);
    }

    /**
     * 添加角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insertDate = array();
        if($request->input("name")){
            $info = $this->isUnique('group_name',$request->input("name"));
            if($info){
                return response()->json([
                    'message' => '你传入的参数name的值已存在',
                    'returnCode' => 1011,
                ]);
            }
            $insertDate['group_name'] = $request->input("name");
        }else{
            return response()->json([
                'message' => '缺少必要的参数name',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("desc")){
            $insertDate['group_desc'] = $request->input("desc");
        }else{
            return response()->json([
                'message' => '缺少必要的参数desc',
                'returnCode' => 1008,
            ]);
        }
        $insertDate['group_isEnabled'] = 1;
        $insertDate['group_disabled_description'] = null;
        $insertDate['group_disabled_time'] = null;
        $insertDate['group_create_time'] = date('Y-m-d H:i:s', time());
        $insertDate['group_id'] =md5(Str::uuid());
        $result = DB::table('manager_groups')->insert($insertDate);
        if($result){
            return response()->json([
                'message' => '添加角色成功',
                'dataInfo'=>'',
                'returnCode' => 1000,
            ]);
        }else{
            return response()->json([
                'message' => '添加角色失败',
                'dataInfo'=>'',
                'returnCode' => 1003,
            ]);
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
        
        if(!$request->input("id")){
            return response()->json([
                'message' => '缺少必要的参数id',
                'returnCode' => 1008,
            ]);
        }else{
            $updateData = array();
            if($request->input("name")){
                $info = $this->isUnique('group_name',$request->input("name"));
                if(!$info){
                    $updateData['group_name'] = $request->input("name");
                }
            }
            if($request->input("group_desc")){
                $updateData['group_desc'] = $request->input("group_desc");
            }
            if($request->input("isEnabled") || $request->input("isEnabled") == 0){
                $updateData['group_isEnabled'] = $request->input("isEnabled");
                if($updateData['group_isEnabled'] == 0){
                    if($request->input("disabledDescription")){
                        $updateData['group_disabled_description'] = $request->input("disabledDescription");
                        $updateData['group_disabled_time'] = date('Y-m-d H:i:s', time());
                    }else{
                        return response()->json([
                            'message' => '缺少必要的参数disabledDescription',
                            'returnCode' => 1008,
                        ]);
                    }
                }else{
                    $updateData['group_disabled_description'] = null;
                    $updateData['group_disabled_time'] = null;
                }
            }
            $updateData['group_update_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('manager_groups')->where('group_id', $request->input("id"))->update($updateData);
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
        if(!$request->input("id")){
            return response()->json([
                'message' => '缺少必要的参数id',
                'returnCode' => 1008,
            ]);
        }else{
            $info = (Array)DB::table('manager_groups')->where('group_id', $request->input("id"))->first();
            if($info["group_isSystem"] == 1){
                return response()->json([
                    'message' => '你无法删除内置角色',
                    'dataInfo'=>'',
                    'returnCode' => 1003,
                ]); 
            }else{
                $result = DB::table('manager_groups')->where('group_id', $request->input("id"))->delete();
                if($result){
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
        }
        
    }

    protected function isUnique($field,$value){
        return !!DB::table('manager_groups')->where($field, $value)->exists();
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
