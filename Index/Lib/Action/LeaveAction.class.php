<?php

class LeaveAction extends Action
{

    //请假申请页面
    public function leave()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
        $this->display();
    }

    //短假审核页面
    public function leave_short()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='部门经理'){
                $this->error('只有部门经理可以访问该模块');
            }
        }

        $department = session('Department');
        $list = M('leave')->where(array('State' => 0, 'Department' => $department))->select();
        $this->assign('leave_short', $list);
        $this->display();
    }


    //长假审核页面
    public function leave_long()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='总经理'){
                $this->error('只有总经理可以访问该模块');
            }
        }

        $list = M('leave')->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))
            ->where(array('State' => 1))->select();
        $this->assign('leave_long', $list);
        $this->display();
    }

    //提交请假申请
    public function application()
    {

        $realName = session('RealName');    //申请人
        $department = session('Department');//部门
        $type = $_POST['type'];             //请假类型
        $days = $_POST['days'];             //请假天数
        $start = $_POST['start'];           //开始时间
        $end = $_POST['end'];               //结束时间
        $reason = $_POST['reason'];         //请假理由

        switch ($type) {
            case 1:
                $type = '病假';
                break;
            case 2:
                $type = '事假';
                break;
            case 3:
                $type = '出差';
                break;
            case 4:
                $type = '婚假';
                break;
            case 5:
                $type = '产假';
                break;
            case 6:
                $type = '年假';
                break;
            case 7:
                $type = '丧假';
                break;
            default:
                $type = '病假';
        }

        switch ($days) {
            case 1:
                $days = '短假';
                break;
            case 2:
                $days = '长假';
                break;
            case 3:
                $days = '重要事项';
                break;
            default:
                $days = '短假';
        }

        if ($start == null || $start . trim() == '' || $end == null || $end . trim() == '' ||
            $reason == null || $reason . trim() == ''
        ) {
            $this->error('提交的信息不完整');
        } else {

            $result1 = M('leave')->where(array('RealName' => $realName, 'State' => 0))->select();
            $result2 = M('leave')->where(array('RealName' => $realName, 'State' => 1))
                ->where("Days = '%s' or Days = '%s'",array('长假','重要事项'))->select();
            if ($result1 || $result2) {
                $this->error('你已提交了申请，请不要重复提交');
            } else {

                $data = array(
                    'RealName' => $realName,
                    'Department' => $department,
                    'Type' => $type,
                    'Days' => $days,
                    'Start' => $start,
                    'End' => $end,
                    'Reason' => $reason,
                    'Time' => date('Y/M/d H:i:s', time()),
                    'State' => 0
                );

                $result = M('leave')->add($data);
                if ($result) {
                    $this->success('申请提交成功');
                } else {
                    $this->error('申请提交失败');
                }

            }

        }

    }


    //获取请假信息
    public function getMessage()
    {
        $realName = $_POST['realName'];
        $state = $_POST['state'];

        if ($state == 0) {

            $result = M('leave')->where(array('RealName' => $realName, 'State' => 0,))->select();
            if ($result) {

                $this->ajaxReturn(array(
                    'status' => 1,
                    'realName' => $result[0]['RealName'],
                    'department' => $result[0]['Department'],
                    'type' => $result[0]['Type'],
                    'days' => $result[0]['Days'],
                    'start' => $result[0]['Start'],
                    'end' => $result[0]['End'],
                    'reason' => $result[0]['Reason'],
                ), 'json');

            } else {
                $this->ajaxReturn(array('state' => 2), 'json');
            }

        } elseif ($state == 1) {

            $result = M('leave')->where(array('RealName' => $realName, 'State' => 1))->select();
            if ($result) {

                $this->ajaxReturn(array(
                    'status' => 1,
                    'realName' => $result[0]['RealName'],
                    'department' => $result[0]['Department'],
                    'type' => $result[0]['Type'],
                    'days' => $result[0]['Days'],
                    'start' => $result[0]['Start'],
                    'end' => $result[0]['End'],
                    'reason' => $result[0]['Reason'],
                ), 'json');

            } else {
                $this->ajaxReturn(array('state' => 2), 'json');
            }

        }


    }

    //同意
    public function agree()
    {
        $realName = $_GET['realName'];
        $days = $_GET['days'];
        $role = session('Role');

        if ($days == 'S') {   //短假


            if ($role == '部门经理') {

                $result1 = M('leave')->where(array('RealName' => $realName,
                    'Days' => '短假', 'State' => 0))->select();
                if ($result1) {
                    $data = array(
                        'State' => 1,
                        'Checker1'=>session('RealName')
                    );
                    M('leave')->where(array('RealName' => $realName,
                        'days' => '短假', 'State' => 0))->save($data);

                    $email = array(
                        'Recipient' => $realName,
                        'Sender' => '系统',
                        'Topic' => '请假申请',
                        'Type' => '重要邮件',
                        'Content' => '您好，您提交的请假申请已通过审核',
                        'Time' => date('Y-M-d H:i:s', time())
                    );
                    M('email')->add($email);
                    $this->success('操作成功，已发送邮件给申请人');
                }

                $result2 = M('leave')->where(array('RealName' => $realName,'State' => 0))
                    ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->select();
                if($result2){

                    $data = array(
                        'State' => 1,
                        'Checker1'=>session('RealName')
                    );
                    M('leave')->where(array('RealName' => $realName,'State' => 0))
                        ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->save($data);

                    $this->success('操作成功,已将该请假申请转给总经理审核');

                }

            } else {

                $this -> error('只有部门经理有权利审核该请假');

            }


        }elseif ($days == 'L') {  //长假

            if ($role == '总经理') {

                $result = M('leave')->where(array('RealName' => $realName, 'State' => 1))
                    ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->select();
                if ($result) {
                    $data = array(
                        'State' => 2,
                        'Checker2'=>session('RealName')
                    );
                    M('leave')->where(array('RealName' => $realName, 'State' => 1))
                        ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->save($data);

                    $email = array(
                        'Recipient' => $realName,
                        'Sender' => '系统',
                        'Topic' => '请假申请',
                        'Type' => '重要邮件',
                        'Content' => '您好，您提交的请假申请已通过审核',
                        'Time' => date('Y-M-d H:i:s', time())
                    );
                    M('email')->add($email);
                    $this->success('操作成功，已发送邮件给申请人');

                }

            }else{

                $this -> error('只有总经理有权利审核该请假');

            }
        }


    }

    //不同意
    public function disagree(){
        $realName = $_GET['realName'];
        $days = $_GET['days'];
        $role = session('Role');

        if ($days == 'S') {   //短假


            if ($role == '部门经理') {

                $result1 = M('leave')->where(array('RealName' => $realName,'State' => 0))->select();
                if ($result1) {
                    $data = array(
                        'State' => 3,
                        'Checker1'=>session('RealName')
                    );
                    M('leave')->where(array('RealName' => $realName,'State' => 0))->save($data);

                    $email = array(
                        'Recipient' => $realName,
                        'Sender' => '系统',
                        'Topic' => '请假申请',
                        'Type' => '重要邮件',
                        'Content' => '您好，您提交的请假申请未能通过审核',
                        'Time' => date('Y-M-d H:i:s', time())
                    );
                    M('email')->add($email);
                    $this->success('操作成功，已发送邮件给申请人');
                }


            } else {

                $this -> error('只有部门经理有权利审核该请假');

            }


        }elseif ($days == 'L') {  //长假

            if ($role == '总经理') {

                $result = M('leave')->where(array('RealName' => $realName, 'State' => 1))
                    ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->select();
                if ($result) {
                    $data = array(
                        'State' => 3,
                        'Checker2'=>session('RealName')
                    );
                    M('leave')->where(array('RealName' => $realName, 'State' => 1))
                        ->where("Days = '%s' or Days='%s'", array('长假', '重要事项'))->save($data);

                    $email = array(
                        'Recipient' => $realName,
                        'Sender' => '系统',
                        'Topic' => '请假申请',
                        'Type' => '重要邮件',
                        'Content' => '您好，您提交的请假申请未能通过审核',
                        'Time' => date('Y-M-d H:i:s', time())
                    );
                    M('email')->add($email);
                    $this->success('操作成功，已发送邮件给申请人');

                }

            }else{

                $this -> error('只有总经理有权利审核该请假');

            }
        }
    }


}