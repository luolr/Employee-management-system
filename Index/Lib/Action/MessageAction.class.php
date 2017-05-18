<?php

class MessageAction extends Action{

    //留言板
    public function message(){
        $messages = M('message')->select();
        $this->assign('messages',$messages);
        $this->display();
    }

    //留言管理
    public function manage(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='系统管理员'){
                $this->error('只有系统管理员可以访问该模块');
            }
        }

        $messages = M('message')->select();
        $this->assign('messages',$messages);
        $this->display();
    }

    //留言
    public function say(){

        $realName = session('RealName');
        $content = $_POST['content'];

        if($content==null||$content.trim()==''){
            $this->error('留言信息不能为空');
        }else{
            $content = replace_phiz($content);

            $data = array(
                'Speaker'=>$realName,
                'Content'=>$content,
                'Time'=>date('Y-M-d H:i:s',time()),
            );

            $add = M('message')->add($data);
            if($add){
                $this->success('留言发布成功');
            }else{
                $this->error('留言发布失败');
            }
        }

    }

    //删除留言
    public function delete(){
        $time = $_POST['time'];
        $role = session('Role');

        if($role=='系统管理员'){

            $result = M('message')->where(array('Time'=>$time))->delete();
            if($result){
                $this->ajaxReturn(array('status'=>1),'json');
            }else{
                $this->ajaxReturn(array('status'=>2),'json');
            }

        }else{
            $this->error('只有系统管理员有权限删除留言');
        }
    }
}