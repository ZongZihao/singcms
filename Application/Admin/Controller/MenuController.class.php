<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 18:06
 */

namespace Admin\Controller;

use Think\Controller;


class MenuController extends CommonController
{
    public function index()
    {

        $data = array();
        if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], array(0, 1))) {
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type', $data['type']);
        } else {
            $this->assign('type', -1);
        }

        //分页操作逻辑
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
        $menus = D('Menu')->getMenus($data, $page, $pageSize);
        $menusCount = D('Menu')->getMenusCount($data);

        $res = new \Think\Page($menusCount, $pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);
        $this->assign('menus', $menus);

        $this->display();
    }

    public function add()
    {

        if ($_POST) {
//            print_r($_POST);
            if (!isset($_POST['name']) || !$_POST['name']) {
                show(0, '菜单名不能为空');
            }
            if (!isset($_POST['m']) || !$_POST['m']) {
                show(0, '模块名不能为空');
            }
            if (!isset($_POST['c']) || !$_POST['c']) {
                show(0, '控制器不能为空');
            }
            if (!isset($_POST['f']) || !$_POST['f']) {
                show(0, '方法名不能为空');
            }
            //如果post包含id,执行更新操作
            if ($_POST['menu_id']) {
                return $this->save($_POST);
            }
            $menuId = D('Menu')->insert($_POST);
            if ($menuId) {
//                print_r($menuId);
                return show(1, '新增成功', $menuId);
            }
            return show(0, '新增失败', $menuId);

        } else {
            $this->display();
        }
    }

    /**
     * 跳转到更新页面
     */
    public function edit()
    {
        if (!isset($_GET['id']) || !$_GET['id']) {
            return;
        }
        $menuId = $_GET['id'];
        $menu = D('Menu')->getMenuById($menuId);
//        var_dump($menu);die;
        $this->assign('menu', $menu);
        $this->display();
    }

    /**
     * 执行更新操作
     * @param $data
     */
    public function save($data)
    {
        $menuId = $data['menu_id'];
        unset($data['menu_id']);
        try {
            $id = D('Menu')->updateMenuById($menuId, $data);
            if ($id === false) {
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * 执行删除操作
     */
    public function setStatus()
    {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                //执行更新操作
                $res = D('Menu')->updataStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }

        return show(0, '没有提交数据');
    }

    /**
     * 执行排序操作
     */
    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if($listorder){
            try {
                foreach ($listorder as $menuId => $v) {
                    //执行更新
                    $id = D("Menu")->updateMenuListorderById($menuId, $v);
                    if ($id === false) {
                        $error[] = $menuId;
                    }
                }
            }catch(Exception $e){
                return show(0, $e->getMessage(), array('jump_url'=>$jumpUrl));
            }
            if($errors){
                return show(0, '排序失败' . implode(',', $errors), array('jump_url'=>$jumpUrl));
            }
            return show(1, '排序成功', array('jump_url'=>$jumpUrl));
        }
        return show(0, '排序数据失败', array('jump_url'=>$jumpUrl));
    }
}