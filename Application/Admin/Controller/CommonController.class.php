<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class CommonController extends Controller {


	public function __construct() {
		
		parent::__construct();
		$this->_init();
	}
	/**
	 * 初始化
	 * @return
	 */
	private function _init() {
		// 如果已经登录
		$isLogin = $this->isLogin();
		if(!$isLogin) {
			// 跳转到登录页面
			$this->redirect('/admin/login/index');
		}
	}

	/**
	 * 获取登录用户信息
	 * @return array
	 */
	public function getLoginUser() {
		return session("adminUser");
	}

	/**
	 * 判定是否登录
	 * @return boolean 
	 */
	public function isLogin() {
		$user = $this->getLoginUser();
		if($user && is_array($user)) {
			return true;
		}

		return false;
	}

    /**
     * 更改状态
     * @param $data
     * @param $models
     */
    public function setStatus($data, $models){
        try {
            if ($data) {
                $id = $data['id'];
                $status = $data['status'];
                if (!$id) {
                    return show(0, 'id不存在');
                }
                $res = D($models)->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
            return show(0, '没有提交内容');
        }catch(\Exception $e){
            return show(0, $e->getMessage());
        }
    }

    /**
     * 排序实现
     */
    public function listorder($model='') {
        $listorder = $_POST['listorder'];
        $jumpUrl =$_SERVER['HTTP_REFERER'];
        $errors = array();
        try {
            if ($listorder) {
                foreach ($listorder as $id => $v) {
                    // 执行更新操作
                    $id = D($model)->updateListorderById($id, $v);
                    if ($id === false) {
                        $error[] = $id;
                    }
                }
                if ($error) {
                    return show(0, '排序失败-' . implode(',', $errors), array('jump_url' => $jumpUrl));
                }
                return show(1, '排序成功', array('jump_url' => $jumpUrl));
            }
        }catch(\Exception $e){
            return show(0, $e->getMessage());
        }
        return show(0, '排序数据失败', array('jump_url' => $jumpUrl));
    }




}