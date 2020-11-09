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
/**
 * 基础model
 */
class BaseModel extends Model
{
    use BaseModelTrait;
    /**
     * 删除数据
     * @param   array   $map    where语句数组形式
     * @param   bool   $type     删除模式 true为真实删除 false为软删除
     * @return  boolean         操作是否成功
     */
    public function deleteData($map,$type=false){
        if (empty($map)) {
            return 50011;
        }
        $result = $this::destroy(
            function($query)use($map){
                $query->where($map);
            },$type);
        return $result;
    }

    /****
     * 恢复被软删除的数据
     */
    public function recDelete($map){
        if(empty($map)){
            return 50011;
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
     * 数据查询
     * @param array map 查询条件
     * @param int type  0仅查询未被软删除数据  1 仅查询被软删除的数据   2 查询所有数据（包括软删除）
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

    /****
     * 计数
     * @param array $map 查询条件
     * @param bool $delete true 只统计软删除数据 ，false不统计软删除数据
     * @return int
     */
    public function getCountData($map=[],$delete=false){
        if(!empty($map)){
            $where = $this::where($map);
        }else{
            $where = $this;
        }
        if($delete==true){
            $where->onlyTrashed()->count();
        }
        return $where->count();
    }



}