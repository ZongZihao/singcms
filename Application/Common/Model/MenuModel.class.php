<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends Model{
    private $_db = '';

    public function __construct(){
        $this->_db = M('Menu');
    }

    public function insert($data = array()){
        if(!data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }

    /**
     * 获取菜单数据
     * @param array $data
     * @param $page
     * @param int $pageSize
     * @return mixed
     */
    public function getMenus($data = array(), $page, $pageSize=10){
        $data['status'] = array('neq', -1);
        $offset = ($page - 1) * $pageSize;
        $list = $this->_db->where($data)->order('listorder desc, menu_id desc')->limit($offset, $pageSize)->select();
        return $list;
    }

    /**
     * 获取菜单数量
     * @param array $data
     * @return mixed
     */
    public function getMenusCount($data = array()){
        $data['status'] = array('neq', -1);
        $count = $this->_db->where($data)->count();
        return $count;
    }

    /**
     * 通过id获取menu
     * @param $id
     * @return array
     */
    public function getMenuById($id){
        if(!$id || !is_numeric($id)){
            return array();
        }
        $menu = $this->_db->where("menu_id=%d", $id)->find();
        return $menu;
    }

    /**
     * 更新菜单数据
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateMenuById($id, $data){
        if(!$id || !is_numeric($id)){
            throw_exception('id不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
        $res = $this->_db->where('menu_id=%d', $id)->save($data);
        return $res;
    }

    public function updataStatusById($id, $status){
        if(!isset($id) || !is_numeric($id)){
            throw_exception('id不合法');
        }
        if(!isset($status) || !is_numeric($status)){
            throw_exception('状态不合法');
        }

        $data['status'] = $status;
        return $this->_db->where('menu_id=%d', $id)->save($data);
    }

    public function updateMenuListorderById($id, $listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }

        $data= array(
            'listorder' => intval($listorder)
        );
        return $this->_db->where('menu_id=%d', $id)->save($data);
    }

    public function getAdminMenus(){
        $data = array(
            'status' => array('neq', -1),
            'type' => 1
        );
        return $this->_db->where($data)->order('listorder desc, menu_id desc')->select();
    }

    public function getBarMenu(){
        $data = array(
            'status' => array('neq', -1),
            'type' => 0,
        );
        return $this->_db->where($data)->order('listorder desc, menu_id desc')->select();
    }
}