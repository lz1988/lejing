<?php
class ReplyinfoAction extends CommonAction
{
    public function index()
    {
        $config = M("config");

        if (!empty($_POST['replay_info']))
        {
            $replay_info = $_POST['replay_info'];
            $save['replay_info'] = $replay_info;
            $config->where('id = 1')->save($save);
        }

        $rs = $config->where('id = 1')->find();
        $this->assign('info', $rs);
        $this->display();
    }
}