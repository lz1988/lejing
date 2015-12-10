<?php
/**
 * 菜单管理
 * author youxiang.zhang
 * date   2013-06-08
 */
class MenuAction extends CommonAction {
	//缺省页
	public function index(){
		$this->module_list();
	}
	
	//模块列表页
	public function module_list(){
		$admin_menu = M('admin_menu');
		$pid = $_REQUEST['pid'] ? $_REQUEST['pid'] : 0;
		$condition['pid'] = $pid;
		if($_REQUEST['menu_title']){
			$condition['menu_title'] = $_REQUEST['menu_title'];
		}
		if($_REQUEST['status'] != '' and $_REQUEST['status'] != 3){
			$condition['status'] = $_REQUEST['status'];
		}else{
			$_REQUEST['status'] = 3;
		}
		//$condition['level'] = 1;
		$list = $admin_menu->where($condition)->order('sort')->select();
		foreach($_REQUEST as $key=>$value){
			$request[$key] = $value;
		}
		$lres=$admin_menu->field('level,menu_title')->where("menu_id=$pid")->find();
		
		$this->assign('res',$lres);
		$this->assign('request',$request);
		$this->assign('list',$list);
		$this->display('module_list');
	}
	
	//菜单列表
	public function menu_list(){
		if($_REQUEST['id']){
			$admin_menu = M('admin_menu');
			if($_REQUEST['menu_title']){
				$condition['menu_title'] = $_REQUEST['menu_title'];
			}
			if($_REQUEST['status'] != '' and $_REQUEST['status'] != 3){
				$condition['status'] = $_REQUEST['status'];
			}else{
				$_REQUEST['status'] = 3;
			}
			$module_id = $_REQUEST['id'];
			$condition['pid'] = $module_id;
			$list = $admin_menu->where($condition)->select();
			foreach($_REQUEST as $key=>$value){
				$request[$key] = $value;
			}
			$this->assign('request',$request);
			$this->assign('module_id',$module_id);
			$this->assign('list',$list);
			$this->display();
		}
		else{
			$this->error('您访问的页面有误!');
		}
	}
	
	//操作列表
	public function operation_list(){
		if($_REQUEST['id']){
			$admin_menu = M('admin_menu');
			if($_REQUEST['menu_title']){
				$condition['menu_title'] = $_REQUEST['menu_title'];
			}
			if($_REQUEST['status'] != '' and $_REQUEST['status'] != 3){
				$condition['status'] = $_REQUEST['status'];
			}else{
				$_REQUEST['status'] = 3;
			}
			$menu_id = $_REQUEST['id'];
			$condition['pid'] = $menu_id;
			$list = $admin_menu->where($condition)->select();
			foreach($_REQUEST as $key=>$value){
				$request[$key] = $value;
			}
			$this->assign('request',$request);
			$this->assign('menu_id',$menu_id);
			$this->assign('list',$list);
			$this->display();
		}
		else{
			$this->error('您访问的页面有误!');
		}
	}
	
	//新增模块
	public function add(){
		$admin_menu = M('admin_menu');
		$module_id = $_REQUEST['module_id'] ? $_REQUEST['module_id'] : 0;
		if($this->isPost()){
			if($_REQUEST['func_name']){
				$data['func_name'] = $_REQUEST['func_name'];
			}
			if($_REQUEST['url']){
				$data['url']        = $_REQUEST['url'];
			}
			$data['menu_title']  = $_REQUEST['menu_title'];
			$data['remark']      = $_REQUEST['remark'];
			$data['status']      = $_REQUEST['status'];
			$data['sort']        = $_REQUEST['sort'];
			
			$data['pid']         = 0;
			$data['level'] 		 = 1;
			if($module_id){
				$data['pid']     = $module_id;
				$lres=$admin_menu->field('level')->where("menu_id=$module_id")->find();
				$data['level']=$lres['level']+1;
			}
			
			
			$data['create_time'] = time();
			$row = $admin_menu->add($data);
			if($row)
				$this->success('新增成功!',U('Menu/module_list').'/pid/'.$module_id);
			else
				$this->error('新增失败!');
			exit;
		}
		
		$this->assign('module_id',$module_id);
		$mres=$admin_menu->field('level')->where("menu_id=$module_id")->find();
		$this->assign('mres',$mres);
		$this->display();
	}
	
	//编辑
	public function edit(){
		$admin_menu = M('admin_menu');
		if($_REQUEST['id']){
			$id = $_REQUEST['id'];
			if($this->isPost()){
				$data['menu_title']  = $_REQUEST['menu_title'];
				if($_REQUEST['func_name']){
					$data['func_name'] = $_REQUEST['func_name'];
				}
				if($_REQUEST['url']){
					$data['url']        = $_REQUEST['url'];
				}
				$data['remark']      = $_REQUEST['remark'];
				$data['status']      = $_REQUEST['status'];
				$data['sort']        = $_REQUEST['sort'];
				$row = $admin_menu->where('menu_id='.$id)->save($data);
				if($row !== FALSE){
				    $res=$admin_menu->field('pid')->where("menu_id=$id")->find();
					$this->success('编辑成功!',U('Menu/module_list').'/pid/'.$res['pid']);
				}else{
					$this->error('编辑失败!');
				}
				exit;
			}
			
			$arr = $admin_menu->where('menu_id='.$id)->find();
			
			$this->assign('arr',$arr);
			$mres=$admin_menu->field('level')->where("menu_id=$id")->find();
			$this->assign('mres',$mres);
			$this->display();
		}
		else{
			$this->error('您访问的页面有误!');
		}
	}
	
	//删除
	public function del(){
		if($_REQUEST['menu_id']){
			$admin_menu = M('admin_menu');
			$id = $_REQUEST['menu_id'];
			
			$row = $admin_menu->where('menu_id in('.$id.')')->delete();
			if($row)
				$this->success('删除成功!');
			else
				$this->error('删除失败!');
		}
		else{
			$this->error('您访问的页面有误!');
		}
	}
	
	
	
	
	
	
	
	
	
	
} 

?>