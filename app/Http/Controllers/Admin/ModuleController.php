<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
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
        $list = DB::table('modules')->select('id as module_id','module_name','module_pid','module_desc','module_creator','module_accendant','module_version','module_operate','module_create_time','module_update_time')->toArray();        
        return response()->json([
            'message' => '成功',
            "dataInfo"=>$list,
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
        if(!$request->input("module_id")){
            return response()->json([
                'message' => '缺少必要的参数module_id',
                'returnCode' => 1008,
            ]);
        }else{
            if($request->input("module_name")){
                $this->isUnique('module_name',$request->input("module_name"));
                $insertDate['module_name'] = $request->input("module_name");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数module_name',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("module_pid")){
                $insertDate['module_pid'] = $request->input("module_pid");
            }
            if($request->input("module_desc")){
                $insertDate['module_desc'] = $request->input("module_desc");
            }
            if($request->input("module_creator")){
                $insertDate['module_creator'] = $request->input("module_creator");
            }
            if($request->input("module_accendant")){
                $insertDate['module_accendant'] = $request->input("module_accendant");
            }
            if($request->input("module_version")){
                $insertDate['module_version'] = $request->input("module_version");
            }
            if($request->input("module_operate")){
                $insertDate['module_operate'] = $request->input("module_operate");
            }
            $insertDate['module_create_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('modules')->insert($insertDate);
            if($result){
                return response()->json([
                    'message' => '添加模块成功',
                    'dataInfo'=>'',
                    'returnCode' => 1000,
                ]);
            }else{
                return response()->json([
                    'message' => '添加模块失败',
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
        if(!$request->input("module_id")){
            return response()->json([
                'message' => '缺少必要的参数module_id',
                'returnCode' => 1008,
            ]);
        }else{
            $moduleInfo = DB::table('modules')->where('id', $request->input("module_id"))->select('id as module_id','module_name','module_pid','module_desc','module_creator','module_accendant','module_version','module_operate','module_create_time','module_update_time')->first();
            if($moduleInfo){
                return response()->json([
                    'message' => '成功',
                    'dataInfo'=>$moduleInfo,
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
        if(!$request->input("module_id")){
            return response()->json([
                'message' => '缺少必要的参数module_id',
                'returnCode' => 1008,
            ]);
        }else{
            if($request->input("module_name")){
                $this->isUnique('module_name',$request->input("module_name"));
                $updateDate['module_name'] = $request->input("module_name");
            }else{
                return response()->json([
                    'message' => '缺少必要的参数module_name',
                    'returnCode' => 1008,
                ]);
            }
            if($request->input("module_pid")){
                $updateDate['module_pid'] = $request->input("module_pid");
            }
            if($request->input("module_desc")){
                $updateDate['module_desc'] = $request->input("module_desc");
            }
            if($request->input("module_creator")){
                $updateDate['module_creator'] = $request->input("module_creator");
            }
            if($request->input("module_accendant")){
                $updateDate['module_accendant'] = $request->input("module_accendant");
            }
            if($request->input("module_version")){
                $updateDate['module_version'] = $request->input("module_version");
            }
            if($request->input("module_operate")){
                $updateDate['module_operate'] = $request->input("module_operate");
            }
            $updateDate['module_update_time'] = date('Y-m-d H:i:s', time());
            $result = DB::table('modules')->where('id', $request->input("manager_id"))->update($updateDate);
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
        if(!$request->input("module_id")){
            return response()->json([
                'message' => '缺少必要的参数module_id',
                'returnCode' => 1008,
            ]);
        }else{
            $result = DB::table('modules')->where('id', $request->input("module_id"))->delete();
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
