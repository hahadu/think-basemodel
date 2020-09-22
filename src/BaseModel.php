<?php
/**
 *  +----------------------------------------------------------------------
 *  | Created by  hahadu (a low phper and coolephp)
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2020. [hahadu] All rights reserved.
 *  +----------------------------------------------------------------------
 *  | SiteUrl: https://github.com/hahadu
 *  +----------------------------------------------------------------------
 *  | Author: hahadu <582167246@qq.com>
 *  +----------------------------------------------------------------------
 *  | Date: 2020/9/19 下午10:02
 *  +----------------------------------------------------------------------
 *  | Description:   cooleAdmin
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkBaseModel;
use Think\Model;
use Hahadu\DataHandle\Data;
use think\model\concern\SoftDelete;
/**
 * 基础model
 */
class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = NULL;


    /**
     * 添加数据
     * @param  array $data  添加的数据
     * @return int          新增的数据id
     */
    public function addData($data){
        // 去除键值首尾的空格
        foreach ($data as $k => $v) {
            $data[$k]=trim($v);
        }
        $this->save($data);
        $id=$this->id;
        return $id;
    }

    /**
     * 删除数据
     * @param   array   $map    where语句数组形式
     * @param   bool   $type     删除模式 true为真实删除 false为软删除
     * @return  boolean         操作是否成功
     */
    public function deleteData($map,$type=false){
        if (empty($map)) {
            die(50011);
        }
        $this::destroy($map,$type);
        $this->delete();
        $result=100013;
        return $result;
    }

    /****
     * 恢复被软删除的数据
     */
    public function recDelete($map){
        if(empty($map)){
            die(500011);
        }
        $this->restore($map);
        $rec_status = $this::onlyTrashed()->where($map)->select();
        if($rec_status->isEmpty()){
            $result = 100021;
        }else{
            $result = 200021;
        }
        return $result;
    }

    /**
     * 修改数据
     * @param   array   $map    where语句数组形式
     * @param   array   $data   数据
     * @return  boolean         操作是否成功
     */
    public function editData($map,$data){
        // 去除键值首位空格
        foreach ($data as $k => $v) {
            $data[$k]=trim($v);
        }
        $result=$this->where($map)->save($data);
        return $result;
    }

    /**
     * 数据查询
     * @param array map 查询条件
     * @param int type  0仅查询未被软删除数据  1 仅查询软删除数据   2 查询所有数据（包括软删除）
     **/
    public function selectData($map,$type=0){
        switch ($type){
            case 1:
                return $this->selectDelData($map);
                break;
            case 2:
                return $this->selectDataAll($map);
                break;
            default:
                if(!empty($map)){
                    return $this::where($map)->select();
                }else{
                    return $this::select();
                }
        }
    }

    /**
     * 仅查询软删除的数据
     * @param array $map
     */
    public function selectDelData($map=[]){
        if(!empty($map)){
            $result = $this::onlyTrashed()->where($map)->select();
        }else{
            $result =  $this::onlyTrashed()->select();
        }
        return $result;
    }

    /**
     * 查询全部数据(包括软删除数据)
     * @param array  $map ;
     */
    public function selectDataAll($map=[]){
        if(!empty($map)){
            $result = $this::withTrashed()->where($map)->select();
        }else{
            $result = $this::withTrashed()->select();
        }
    }

    /**
     * 数据排序
     * @param array $data 数据源
     * @param string $id 主键
     * @param string $order 排序字段
     * @return boolean       操作是否成功
     */
    public function orderData(array $data, $id='id', $order='order_by'){
        foreach ($data as $k => $v) {
            $v=empty($v) ? null : $v;
            $this->where(array($id=>$k))->save(array($order=>$v));
        }
        return true;
    }

    /**
     * 获取全部层级数据
     * @param  string $type  tree获取树形结构 level获取层级结构
     * @param  string $order 排序方式
     * @return array         结构数据
     **/
    public function getTreeData($type='tree',$order='',$name='name',$child='id',$parent='pid'){
        // 判断是否需要排序
        if(empty($order)){
            $data=$this->select()->toArray();
        }else{
            $data=$this->order($order.' is null,'.$order)->select()->toArray();
        }
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }

    /**
     * 获取分页数据
     * @param  subject  $model  model对象
     * @param  array    $map    where条件
     * @param  string   $order  排序规则
     * @param  integer  $limit  每页数量
     * @param  integer  $field  $field
     * @return array            分页数据
     */
    public function getPage($model,$map,$order='',$limit=10,$field=''){
        $count=$model
            ->where($map)
            ->count();
        $page=new_page($count,$limit);
        // 获取分页数据
        if (empty($field)) {
            $list=$model
                ->where($map)
                ->order($order)
                ->limit($page->firstRow.','.$page->listRows)
                ->select();
        }else{
            $list=$model
                ->field($field)
                ->where($map)
                ->order($order)
                ->limit($page->firstRow.','.$page->listRows)
                ->select();
        }
        $data=array(
            'data'=>$list,
            'page'=>$page->show()
        );
        return $data;
    }





}