<?php
/**
 * @title 轮播图管理
 * @author lizhi
 * @create  on 2015-08-09
 */

class LunboAction extends CommonAction
{

    public function show()
    {
        $img = M("lunbo");
        $rs = $img->select();
        $this->assign('rs', $rs);

        $this->display('lunbo_show');
    }
    /**
     * @title 上传图片操作
     * @author lizhi
     */
    public function addmod()
    {


        if ($_FILES){
            foreach ($_FILES as $key=>$files)
            {
                /*echo '<pre>';
                print_r($_FILES);*/
                //die();
                if(empty($files['name'])){
                    $this->error('请选择需要上传的文件');
                }else{
                    $data = $this -> upload_img();

                    if(isset($data))
                    {
                        $img = M('lunbo');
                        $ymd = date("Ymd"); //图片路径地址
                        foreach($data as $v) {
                            $save['lunbo_images'] = "/".$v['savepath'].str_ireplace($ymd.'/',$ymd.'/thumb_',$v['savename']);;//$v['savepath'].$v['savename'];
                            $img->add($save);

                        }

                        $this->redirect("lunbo/show");
                    }else{
                        $this->error('插入到数据库失败');
                    }
                }
            }
        }

        $this->display("index");
    }

    function del()
    {
        $id = $_REQUEST['id'];
        $m = M("lunbo");
        $rs = $m->where('id = '.$id)->delete();
        if ($rs === false){
            $this->error("删除图片失败");
        }else{
            $this->redirect("/lunbo/show");
        }
    }

    /**
     * @return array
     * @title 上传图片
     */
    public function upload_img(){
        import("@.ORG.UploadFile");

        import("@.ORG.Image");
        //导入上传类
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxsize = 3145728;
        //设置上传文件类型
        $upload->allowExts = explode(',',"jpg,gif,jpeg,png");

        $ymd = date("Ymd"); //图片路径地址

        $upload->autoSub  = true ;

        $upload->thumbRemoveOrigin  = true ;
        //设置附近上传目录
        $upload->savePath = "Uploads/Lunbo/"; //注意 目录为入口文件的相对路径
        $thumbPath ='Uploads/Lunbo/'.$ymd.'/';//缩略图的路径
        $upload->thumbPath = $thumbPath;
        //设置需要生成缩略图他，仅对图片文件有效
        $upload->thumb = true;

        //if(!mk_dir($thumbPath)) $this->error("缩略图目录创建失败");
        //设置引用图片类库包路径
        $upload->imageClassPath = 'ORG.Net.Image';
        //设置需要生成缩略图他的文件后缀
        //$upload->thumbPrefix ='m_,s_'; //生成2张缩略图
        //设置缩略图最大宽度
        //$upload->thumbMaxWidth = '300,150';
        //设置缩略图最大高度
        // $upload->thumbMaxHeight = '300,150';

        $upload->thumbMaxWidth  = 400;
        $upload->thumbMaxHeight  = 800;
        //设置上传文件规则
        //$upload->saveRule = uniqid;
        //$upload->saveRule = time();
        $upload->saveRule = uniqid;

        $upload->subType = 'date';
        //删除原图
        //$upload->thumbRemoveOrigin = true;
        if(!$upload->upload()){
            $this->error($upload->getErrorMsg());
        }else{
            //取得成功上传文件信息
            $info   = $upload->getUploadFileInfo();
            //$info[0]['savename']=str_ireplace($ymd.'/',$ymd.'/thumb_',$info[0]['savename']);
            //$ary['src']= $info[0]['savepath'].$info[0]['savename'];
            return $info;
        }

    }
}