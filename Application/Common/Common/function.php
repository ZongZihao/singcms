<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 21:23
 */

function show($status, $message, $data = array())
{
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data
    );
    exit(json_encode($result));
}

function getMd5Password($password)
{
    return md5($password . C('MD5_PRE'));
}

function getMenuType($type)
{
    return $type == 1 ? '后端菜单' : '前端导航';
}

function status($status)
{
    if ($status == 0) {
        $str = '关闭';
    } else if ($status == 1) {
        $str = '正常';
    } else if ($status == -1) {
        $str = '删除';
    }
    return $str;
}

function getAdminMenuUrl($nav)
{
    $url = '/admin/' . $nav['c'] . '/' . $nav['f'];
    return $url;
}

//判断是否高亮显示导航项
function getActive($navc)
{
    $c = strtolower(CONTROLLER_NAME);
    if (strtolower($navc == $c)) {
        return 'class="active"';
    }
    return '';
}

function showKind($status, $data)
{
    header('Content-type:application/json;charset=UTF-8');
    if ($status == 0) {
        exit(json_encode(array('error' => 0, 'url' => $data)));
    }
    exit(json_encode(array('error' => 1, 'message' => '上传失败')));
}

function getLoginUsername(){
    return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username'] : '';
}

function getCatName($navs, $catid){
    foreach($navs as $nav){
        if ($nav['menu_id'] == $catid){
            return $nav['name'];
        }
    }
}

function getCopyFrom($id){
    $fromList = C('COPY_FROM');
    return $fromList[$id] ? $fromList[$id] : '';
}

function isThumb($thumb){
    if($thumb){
        return '<span style="color:red">有</span>';
    }else{
        return '无';
    }
}