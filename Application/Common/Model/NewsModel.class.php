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

    public function getNews($data, $page, $pageSize=10) {
        $conditions = $data;
        if(isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like', '%' . $data['title'] . '%');
        }
        if(isset($data['cat_id']) && $data['cat_id']){
            $conditions['cat_id'] = intval($data['cat_id']);
        }

        $offset = ($page - 1) * $pageSize;
        $list = $this->_db->where($conditions)
            ->order('listorder desc, news_id desc')
            ->limit($offset, $pageSize)
            ->select();

        return $list;
    }

    public function getNewsCount($data=array()){
        $conditions = $data;
        if(isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like', '%' . $data['title'] . '%');
        }
        if(isset($data['cat_id']) && $data['cat_id']){
            $conditions['cat_id'] = intval($data['cat_id']);
        }
        return $this->_db->where($conditions)->count();
    }

}