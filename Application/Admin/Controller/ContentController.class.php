<?php

namespace Admin\Controller;
use Think\Controller;

class ContentController extends Controller{
    public function index(){
        $this->display();
    }

    public function add(){
        if($_POST){
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0, '标题不存在');
            }if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0, '短标题不存在');
            }if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0, '文章栏目不存在');
            }if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0, '关键字不存在');
            }if(!isset($_POST['content']) || !$_POST['content']){
                return show(0, 'content不存在');
            }

            $newsId = D('News')->insert($_POST);
            if($newsId){
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cid = D('NewsContent')->insert($newsContentData);
                if($cid){
                    return show(1, '新增成功');
                }else{
                    return show(0, '主表插入成功,副表插入失败');
                }
            }
        }else {
            $webSiteMenu = D('Menu')->getBarMenu();
            $titleFontColor = C('TITLE_FONT_COLOR');
            $copyFrom = C('COPY_FROM');
            $this->assign('webSiteMenu', $webSiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyFrom', $copyFrom);
            //        var_dump(array($webSiteMenu, $titleFontColor, $copyFrom));die;
            $this->display();
        }
    }
}