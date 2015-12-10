<?php

/**
 * Class RolemainAction
 * @title 角色管理
 * @author zhi.li
 * @create on 2015-09-24
 */
class RolemainAction extends CommonAction
{
    public function index()
    {
        $rolemain=M('rolemain');
        import('@.ORG.Page');
        $arr="";

        $count = $rolemain->where($arr)->count();    //计算总数
        $p = new Page($count, 20);
        $rolemain_arr = $rolemain->limit($p->firstRow . ',' . $p->listRows)->where($arr)->select();
        //echo $rolemain->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('rolemain_arr', $rolemain_arr);
        $this->display();
    }

    public function rolemain_add()
    {
        if ($_POST)
        {
            $role_name = $_POST['rolename'];
            $rolemain = M("rolemain");
            $data['role_name'] = $role_name;
            $rs = $rolemain->add($data);

            if ($rs !== false)
            {
                $this->redirect('/rolemain/index');
                exit;
            }
        }
        $this->display();
    }

    public function rolemain_update()
    {
        $roleid = $_GET['roleid'];
        $rolemain = M("rolemain");
        if ($_POST)
        {
            $rolename = $_POST['rolename'];
            $data['role_name'] = $rolename;
            $roleid = $_POST['roleId'];
            $rs = $rolemain->where('role_id = '.$roleid)->save($data);
            if ($rs !== false){
                $this->redirect('/rolemain/index');
            }
        }

        $result = $rolemain->where('role_id = '.$roleid)->find();
        $this->assign('result', $result);
        $this->display();
    }

    public function rolemain_del()
    {
        $roleId = $_GET['roleid'];
        $rolemain = M("rolemain");
        $rs = $rolemain->where('role_id = '.$roleId)->delete();
        if ($rs !== false)
        {
            $this->redirect('/rolemain/index');
        }
    }

    public function setmode()
    {
        $userid = $_GET['userid'];
		$shopid = $_GET['shopid'];
		//ECHO '<pre>';print_r($_GET);
        $role_mode = M("role_mode");
        if ($_POST)
        {
            $userId = $_POST['userid'];
			$shopId = $_POST['shopid'];
            $Bid = $_POST['bid'];
            $rp_idarr = array();
            foreach($Bid as $v){
                $arr['user_id'] = $userId;
				$arr['shop_id'] = $shopId;
                $arr['mode_id'] = $v;
                $rp_id = $role_mode->field('*')->where($arr)->find();
                //echo $role_mode->getLastSql();die();

                if (!$rp_id){
                    $role_mode->add($arr);
                    $rp_idarr[]=$v;
                }else{
                    $rp_idarr[]=$rp_id['mode_id'];
                }
            }

            if ($rp_idarr)
            {
                $del['mode_id'] =  array('NOT IN',implode(',',$rp_idarr));
                $del['user_id'] =  array('eq',$userId);
				$del['shop_id'] =  array('eq',$shopId);
                $role_mode->where($del)->delete();
            }
            //echo $role_mode->getLastSql();die();
            $this->redirect('/rolemain/setmode/userid/'.$userId.'/shopid/'.$shopId);
            exit;

        }
        //$roleid = 2;
        //var_dump($roleid);
        $this->assign('userid',$userid);
        $this->assign('shopid',$shopid);
		
		$shop = M("shop");
		$shoparr = $shop->where('shop_id = '.$shopid)->find();
		$this->assign('shopname',$shoparr['shop_name']);
		
		$member = M("member");
		$memberarr = $member->where('user_id = '.$userid)->find();
		$this->assign('membername',$memberarr['username']);


        $rolearr = $role_mode->where('user_id = '.$userid.' and shop_id = '.$shopid)->select();
		//echo $role_mode->getLastSql();
        $newrolearr = array();
        for($j = 0;$j < count($rolearr);$j++){
            $newrolearr[] = $rolearr[$j]['mode_id'];
        }

        $mode = M("mode")->alias('a');
        $rs = $mode->field('a.*,b.mode_type as modetype,b.id as bid')->join("left join cms_mode as b on b.pid=a.id")->where('a.pid = "0" ')->select();
        /* echo $mode->getLastSql();
        echo '<pre>'; */
        for($p = 0;$p <count($rs);$p++){
            $rs[$p]['flag'] = in_array($rs[$p]['bid'],$newrolearr) ? 1 : 0;
        }
        $newrs = array();
        foreach($rs as $k=>$v){
            $newrs[$v['mode_type']][] = $v;
        }
        //echo '<pre>';
        //print_r($newrolearr);
        $this->assign('arr', $newrs);
        $this->display();
    }
}
?>