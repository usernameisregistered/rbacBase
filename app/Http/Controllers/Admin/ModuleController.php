<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    protected $moduleList = array();
    /**
     * 模块列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $list = DB::table('modules')->select('module_id as id','module_title as title','pid','module_desc as desc','module_operate as operate','module_create_time as createTime')->get();
        $tempList = array();
        foreach($list as $value){
            $tempList[] = (Array)$value;
        }
        return response()->json([
            'message' => '成功',
            "dataInfo"=>$this->getTree($tempList,0),
            'returnCode' => 1000,
        ]);
    }

    /**
     * 添加模块
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insertData = array();
        if($request->input("title")){
            $info = $this->isUnique('module_title',$request->input("title"));;
            if($info){
                return response()->json([
                    'message' => '你传入的参数name的值已存在',
                    'returnCode' => 1011,
                ]);
            }
            $insertData['module_title'] = $request->input("title");
        }else{
            return response()->json([
                'message' => '缺少必要的参数title',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("desc")){
            $insertData['module_desc'] = $request->input("module_desc");
        }else{
            return response()->json([
                'message' => '缺少必要的参数desc',
                'returnCode' => 1008,
            ]);
        }
        if($request->input("icon")){
            $insertData['module_icon'] = $request->input("icon");
        }
        if($request->input("path")){
            $insertData['module_path'] = $request->input("path");
        }
        if($request->input("operate")){
            $insertData['module_operate'] = $request->input("operate");
        }
        if($request->input("pid")){
            $insertData['pid'] = $request->input("pid");
        }else{
            $insertData['pid'] = 0;
        }
        $insertData['module_create_time'] = date('Y-m-d H:i:s', time());
        $insertData['module_id'] = md5($request->input("title").now());
        $result = DB::table('modules')->insert($insertData);
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

    /**
     * 修改模块
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
            if($request->input("title")){
                $info = $this->isUnique('module_title',$request->input("title"));;
                if(!$info){
                    $updateDate['module_title'] = $request->input("title");
                }
            }
            if($request->input("desc")){
                $updateDate['module_desc'] = $request->input("desc");
            }
            if($request->input("operate")){
                $updateDate['module_operate'] = $request->input("operate");
            }
            $result = DB::table('modules')->where('module_id', $request->input("id"))->update($updateDate);
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
                'message' => '缺少必要的参数module_id',
                'returnCode' => 1008,
            ]);
        }else{
            if(DB::table('modules')->where('pid', $request->input("id"))->exists()){
                return response()->json([
                    'message' => '请优先删除其子节点',
                    'dataInfo'=>'',
                    'returnCode' => 1003,
                ]);
            }
            $result = DB::table('modules')->where('module_id', $request->input("id"))->delete();
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
    
    protected function getTree($array,$pid=0){
        $items = array();
        foreach($array as $value){
            $items[$value['id']] = $value;
        }
        $tree = array();
        foreach($items as $key => $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']]['children'][] = &$items[$key];
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }

    protected function tree($field,$value){
        return !!DB::table('modules')->where($field, $value)->exists();
    }
}
