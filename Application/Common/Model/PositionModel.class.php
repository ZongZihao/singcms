<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/14
 * Time: 21:43
 */
namespace Common\Model;
use Think\Model;

class PositionModel extends Model{

    private $_db;

    public function __construct(){
        $this->_db = M('position');
    }

    //获取正常的推荐位内容
    public function getNormalPositions(){
        $conditions = array('status' => 1);
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }
}