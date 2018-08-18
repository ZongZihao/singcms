<?php
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model{

    private $_db;

    public function __construct(){
        $this->_db->M('PositionContent');
    }

    public function insert($data){
        if(!$data){
            E('未找到参数');
        }
        $map['news_id'] = $data['news_id'];
        $map['status'] = 1;
        $position = $this->_db->where($map)->find();
        if($position){
            $data['update_time'] = time();
            return $this->_db->where('id=%d', $position['id'])->save($data);
        }else{
            $data['create_time'] = time();
            return $this->_db->add($data);
        }

    }

}