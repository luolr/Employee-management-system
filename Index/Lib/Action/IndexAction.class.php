<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {

    //这是会自动运行的方法，先于其他方法
    public function _initialize(){
        $username = session('UserName');    //用户名
        $realname = session('RealName');    //真实姓名
        $department = session('Department');

        //首页_公司公告
        $company_notice = M('company_notice')->order('ID desc')->limit(1)->select();
        $this->assign("company_notice_title", $company_notice[0]['Title']);
        $this->assign("company_notice_content", $company_notice[0]['Content']);
        $this->assign("company_notice_time", $company_notice[0]['Time']);

        //首页_部门公告
        $department_notice = M('department_notice')->where(array('Department'=>$department))
            ->order('ID desc')->limit(1)->select();
        $this->assign("department_notice_title", $department_notice[0]['Title']);
        $this->assign("department_notice_content", $department_notice[0]['Content']);
        $this->assign("department_notice_time", $department_notice[0]['Time']);


        //首页_邮箱
        $myEmail = M('email')->where("recipient = '%s' or sender = '%s'",
            array($realname,$realname))->select();
//        var_dump($myEmail);
        $this->assign("myEmail",$myEmail);

    }

    public function index(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }

        $this->display();  //加载首页模板
    }

    //用户登出
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('Index/login');
    }

    //首页_邮件单数删除
    public function singular(){
        $time = $_POST['time'];
        $index = $_POST['index'];
        $count = M('email')->where(array('Time'=>$time))->delete();
        if($count!=0){
            $this->ajaxReturn(array('status' => 1,'index'=>$index), 'json');//删除成功
        }else{
            $this->ajaxReturn(array('status' => 2,'index'=>$index), 'json');//删除失败
        }


    }


    //首页_邮件复数删除
    public function plural(){
        $array = $_POST['array'];
        $count = 0;
        for($i=0; $i<count($array); $i++){
            $count += M('email')->where(array('Time'=>$array[$i]))->delete();
        }
        if($count!=0){
            $this->ajaxReturn(array('status' => 1), 'json');//删除成功
        }else{
            $this->ajaxReturn(array('status' => 2), 'json');//删除数目为0
        }

    }


    //首页_阅读邮件
    public function read(){
        $time = $_POST['time'];
        $email = M('email')->where(array('Time'=>$time))->find();
        if($email!=null && $email!=false){
            $Topic = $email['Topic'];
            $Recipient = $email['Recipient'];
            $Sender = $email['Sender'];
            $Type = $email['Type'];
            $Content = $email['Content'];
            $Time = $email['Time'];

            $this->ajaxReturn(array(
                'status'=>1,
                'Topic'=>$Topic,
                'Recipient'=>$Recipient,
                'Sender'=>$Sender,
                'Type'=>$Type,
                'Content'=>$Content,
                'Time'=>$Time
            ));//查找成功,返回数据
        }else{
            $this->ajaxReturn(array('status'=>2),'json');//查找失败
        }
    }



    //首页_发送邮件
    public function sendEmail(){

        $recipient = $_POST['recipient'];   //收信人
        $topic = $_POST['topic'];           //主题
        $type = $_POST['type'];             //类型
        $content = $_POST['content'];       //正文

        if($type=='option1'){
            $type='普通邮件';
        }else{
            $type='重要邮件';
        }
        $IsRealName = M('employees')
            ->where("RealName = '%s' or Email = '%s'",
            array($recipient,$recipient))->select();
        if($IsRealName==0){
            $this->error('收件人不存在');
        }else{
            $data = array(
                'Recipient'=>$IsRealName[0]['RealName'],
                'Topic'=>$topic,
                'Type'=>$type,
                'Content'=>$content,
                'Sender'=>session('RealName'),
                'Time'=>date('Y-M-d H:i:s',time()),
            );
//            var_dump($data);
            $email = M('email')->add($data);
        }
        $this->success('邮件发送成功');
    }


}




