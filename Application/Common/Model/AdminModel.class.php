<?php

namespace Common\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 21:58
 */
class AdminModel extends Model
{

    private $_db = '';

    public function __construct()
    {
        $this->_db = M('admin');
    }


    public function getAdminByUsername($username)
    {
        $ret = $this->_db->where('username="' . $username . '"')->find();
        return $ret;

    }
}