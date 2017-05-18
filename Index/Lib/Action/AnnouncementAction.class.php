<?php
class AnnouncementAction extends Action{

    //公司公告页面
    public function company(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
//        }else{
//            if($_SESSION['Role']!='总经理秘书'){
//                $this->error('只有总经理秘书可以访问该模块');
//            }
//        }

        $company_notice = M('company_notice')->select();

        $this->assign('company_notice',$company_notice);
        $this->display();
    }

    //内部公告页面
    public function department(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
//        }else{
//            if($_SESSION['Role']!='部门经理'){
//                $this->error('只有部门经理可以访问该模块');
//            }
//        }

        $department = session('Department');
        $department_notice = M('department_notice')->where(array('Department'=>$department))->select();

        $this->assign('department_notice',$department_notice);
        $this->display();
    }

    //发布部门公告
    public function de_publish(){
        $role = session('Role');
        if($role=='部门经理'){

            $title = $_POST['title'];
            $content = $_POST['content'];
            $time = date('Y-M-d H:i:s',time());
            $publisher = session('RealName');
            $department = session('Department');

            $result = M('department_notice')->where(array('Title'=>$title))->select();

            if($result) {
                $data = array(
                    'Content' => $content,
                    'Time' => $time,
                );

                $result = M('department_notice')->where(array('Title' => $title))->save($data);

                if ($result) {
                    $this->success('公告更新成功');
                } else {
                    $this->error('公告更新失败');
                }
            }else{

                $data = array(
                    'Title'=>$title,
                    'Content'=>$content,
                    'Time'=>$time,
                    'Publisher'=>$publisher,
                    'Department'=>$department,
                );

                $result = M('department_notice')->add($data);

                if($result){
                    $this->success('公告发布成功');
                }else{
                    $this->error('公告发布失败');
                }
            }

        }else{
            $this->error('只有部门经理才有权利发布部门公告');
        }
    }

    //发布公司公告
    public function co_publish(){
        $role = session('Role');
        if($role=='总经理秘书'){

            $title = $_POST['title'];
            $content = $_POST['content'];
            $time = date('Y-M-d H:i:s',time());
            $publisher = session('RealName');

            $result = M('company_notice')->where(array('Title'=>$title))->select();

            //查找是否是已存在的公告,存在则是更新公告
            if($result){
                $data = array(
                    'Content'=>$content,
                    'Time'=>$time,
                );

                $result = M('company_notice')->where(array('Title'=>$title))->save($data);

                if($result){
                    $this->success('公告更新成功');
                }else{
                    $this->error('公告更新失败');
                }
            }else{
                $data = array(
                    'Title'=>$title,
                    'Content'=>$content,
                    'Time'=>$time,
                    'Publisher'=>$publisher,
                );

                $result = M('company_notice')->add($data);

                if($result){
                    $this->success('公告发布成功');
                }else{
                    $this->error('公告发布失败');
                }
            }

        }else{
            $this->error('只有总经理秘书才有权利发布部门公告');
        }
    }

    //验证公告标题是否已存在
    public function Title(){
        $title = $_POST['title'];
        $type = $_POST['type'];

        if($type==1){   //公司公告

            $result = M('company_notice')->where(array('Title'=>$title))->select();

            if($result){
                $this->ajaxReturn(array('status'=>2),'json');   //标题已存在
            }else{
                $this->ajaxReturn(array('status'=>1),'json');   //标题可用
            }

        }else{          //部门公告

            $result = M('department_notice')->where(array('Title'=>$title))->select();

            if($result){
                $this->ajaxReturn(array('status'=>2),'json');   //标题已存在
            }else{
                $this->ajaxReturn(array('status'=>1),'json');   //标题可用
            }

        }

    }


    //获取正文
    public function getContent(){
        $title = $_POST['title'];
        $type = $_POST['type'];

        if($type==1){   //公司公告
            $content = M('company_notice')->where(array('Title'=>$title))->getField('Content');
            $this->ajaxReturn(array('content'=>$content));
        }elseif($type==2){
            $content = M('department_notice')->where(array('Title'=>$title))->getField('Content');
            $this->ajaxReturn(array('content'=>$content));
        }//部门公告
    }

    //删除公告
    public function delete(){
        $time = $_POST['time'];
        $type = $_POST['type'];

        if($type==1){
            $result = M('company_notice')->where(array('Time'=>$time))->delete();
            if($result){
                $this->ajaxReturn(array('status'=>1),'json');   //删除成功
            }else{
                $this->ajaxReturn(array('status'=>2),'json');   //删除失败
            }
        }else{
            $result = M('department_notice')->where(array('Time'=>$time))->delete();
            if($result){
                $this->ajaxReturn(array('status'=>1),'json');   //删除成功
            }else{
                $this->ajaxReturn(array('status'=>2),'json');   //删除失败
            }
        }
    }

}