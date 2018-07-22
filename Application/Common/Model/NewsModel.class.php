<?php
namespace Common\Model;
use Think\Model;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 21:01
 */

class NewsModel extends Model {

    private $_db;

    public function __construct(){
        $this->_db = M('news');
    }

    public function insert($data){
        if(!is_array($data) || !$data){
            return 0;
        }

        $data['create_time'] = time();
        $data['username'] = getLoginUsername();

        return $this->_db->add($data);
    }

}