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
 *  | Date: 2020/10/6 下午12:08
 *  +----------------------------------------------------------------------
 *  | Description:   ImAdminThink
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkBaseModel;


use Hahadu\DataHandle\Data;

trait BaseModelTrait
{
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
     * 添加数据
     * @param  array $data  添加的数据
     * @return int          新增的数据id
     */
    public function addData($data){
        // 去除键值首尾的空格
        foreach ($data as $k => $v) {
            $data[$k]=trim($v);
        }
        $this::create($data);
        $id=$this->id;
        return $id;
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
     * 获取全部层级数据
     * @param  string $type  tree获取树形结构 level获取层级结构
     * @param  string $order 排序方式
     * @param string $name
     * @param string $child
     * @param string $parent
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
     * @param  array    $map    where条件
     * @param  string   $order  排序规则
     * @param  integer  $limit  每页数量
     * @param  integer  $field  $field
     * @return array            分页数据
     */
    public function getPage($map,$order='',$limit=10,$field=''){
        // 获取分页数据
        if (empty($field)) {
            $list=$this::where($map)
                ->order($order)
                ->paginate($limit);
        }else{
            $list=$this::field($field)
                ->where($map)
                ->order($order)
                ->paginate($limit);
        }
        $data = [
            'list' => $list,
            'page' => $list->render()
        ];
        return $data;
    }


}