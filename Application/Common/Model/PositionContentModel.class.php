<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/14
 * Time: 21:43
 */
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model{

    private $_db;

    public function __construct(){
        $this->_db = M('position_content');
    }

    public function select($data =array(), $limit=0){
        if($data['title']){
            $data['title'] = array('like', '%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder desc, id desc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }

    public function find($id){
        $data = $this->_db->where('id='.$id)->find();
        return $data;
    }

    public function insert($res=array()){
        if($res || !is_array($res)){
            return 0;
        }
        if($res['create_time']){
            $res['create_time'] = time();
        }
        return $this->_db->add($res);
    }

    public function updateById($id, $data){
        if(!$id || !is_numeric($id)){
            E('ID不合法');
        }
        if(!$data || !is_array($data)){
            E('更新的数据不合法');
        }
        return $this->_db->where('id='.$id)->save($data);
    }

    public function updateStatusById($id, $status){
        if(!is_numeric($status)){
            E('status不能为非数字');
        }
        if(!$id || !is_numeric($id)){
            E('id不合法');
        }
        $data['status'] = $status;

        return $this->_db->where('id=' . $id)->save($data);
    }

    //排序
    public function updateListorderById($id, $listorder){
        if(!$id || !is_numeric($listorder)){
            E('ID不合法');
        }

        $data = array('listorder' => intval($listorder));
        return $this->_db->where('id='.$id)->save($data);
    }
}