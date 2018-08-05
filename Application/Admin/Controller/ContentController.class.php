<?php

namespace Admin\Controller;
use Think\Controller;

class ContentController extends Controller{
    public function index(){
        $conds = array();
        $title = $_GET['title'];
        $catid = $_GET['catid'];
        if($title){
            $conds['title'] = $title;
        }
        if($catid){
            $conds['catid'] = intval($catid);
        }

        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize= 2;
        $conds['status'] = array('neq', -1);

        $newsCount = D('News')->getNewsCount($conds);
        $news = D('News')->getNews($conds, $page, $pageSize);

        $res = new \Think\Page($newsCount, $pageSize);
        $pageres = $res->show();

        $this->assign('news', $news);
        $this->assign('pageres', $pageres);
        $this->assign('webSiteMenu', D('Menu')->getBarMenus());
        $this->assign('conds', $conds);
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
            if($_POST['news_id']){
                return $this->save($_POST);
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
            $webSiteMenu = D('Menu')->getBarMenus();
            $titleFontColor = C('TITLE_FONT_COLOR');
            $copyFrom = C('COPY_FROM');
            $this->assign('webSiteMenu', $webSiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyFrom', $copyFrom);
            //        var_dump(array($webSiteMenu, $titleFontColor, $copyFrom));die;
            $this->display();
        }
    }

    public function edit(){
        $newsId = $_GET['id'];
        if(!$newsId){
            $this->redirect('admin/content');
        }
        $news = D('news')->find($newsId);
        if(!$news){
            $this->redirect('admin/content');
        }
        $newsContent = D('NewsContent')->find($newsId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }

        $webSiteMenu = D('Menu')->getBarMenus();
        $this->assign('webSiteMenu', $webSiteMenu);
        $this->assign('titleFontColor' ,C('TITLE_FONT_COLOR'));
        $this->assign('copyFrom', C('COPY_FROM'));
        $this->assign('news', $news);

        $this->display();
    }

    public function save($data){
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try {
            $id = D('News')->updateById($newsId, $data);
            $newsContentData['content'] = $data['content'];
            $condId = D('NewsContent')->updateNewsById($newsId, $newsContentData);
            if($id === false || $condId === false){
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        }catch(\Exception $e){
            return show(0, $e->getMessage());
        }
    }
}