<?php
// 本类由系统自动生成，仅供测试用途
class ContactAction extends Action {

    //这是会自动运行的方法，先于其他方法
    public function _initialize(){

        $departments = M('department')->select();   //部门
        $role = M('role')->select();                //职位
        $this->assign("departments",$departments);
        $this->assign("role",$role);

    }

    public function contact(){

        //查询类型
        $type = $_GET['type'];
        if($type==null){
            $type = 1;
        }

        $id = session('LoginID');           //用户ID
        $contact = M('r_employees_employees')->where(array('EmployeeID'=>$id))->getField('EmployeeID2',true);
//            var_dump($contact);//用户联系人数组

        if($type==1){               //全部联系人

            $array['EmployeeID'] = array('IN',$contact);
            $all = M('employees')->where($array)->select();
            $this->assign('contactList',$all);

        }elseif($type==2){          //按部门查找

            $department = $_GET['department'];
            $array['EmployeeID'] = array('IN',$contact);
            $array['Department'] = $department;
            $all = M('employees')->where($array)->select();
            $this->assign('contactList',$all);

        }elseif($type==3){          //按职位查找

            $role = $_GET['role'];
            $array['EmployeeID'] = array('IN',$contact);
            $array['Role'] = $role;
            $all = M('employees')->where($array)->select();
            $this->assign('contactList',$all);

        }

        //联系人添加申请列表
        $addID = M('request_contact')->where(array('EmployeeID'=>$id))->getField('EmployeeID2',true);
        $Ids['EmployeeID'] = array('IN',$addID);
        $addList = M('employees')->where($Ids)->select();

        if($addList){
            $this->assign('addList',$addList);
        }

        $this->display();
    }


    //添加新的联系人
    public function add(){

        $contactName = $_POST['contactName'];
        $contactPhone = $_POST['contactPhone'];

        $UserID = session('LoginID');   //用户ID

        $isExist = M('employees')->where(array('RealName'=>$contactName,'Telephone'=>$contactPhone))->select();
        $id = M('employees')->where(array('RealName'=>$contactName,'Telephone'=>$contactPhone))->getField('EmployeeID');
        $isContact = M('r_employees_employees')->where(array('EmployeeID'=>$UserID,'EmployeeID2'=>$id))->select();


        if(!$isExist){
            $this->ajaxReturn(array('status'=>2),'json');   //用户不存在或信息错误
        }else{

            if($isContact){
                $this->ajaxReturn(array('status'=>3),'json');   //已经是联系人
            }else{

                $data = array(
                    'EmployeeID'=>$id,
                    'EmployeeID2'=>$UserID
                );
                M('request_contact')->add($data);

                $this->ajaxReturn(array('status'=>1),'json');   //成功
            }

        }
    }


    //发送邮件
    public function send(){

        $recipient = $_POST['recipient'];
        $topic = $_POST['topic'];
        $type = $_POST['type'];
        $realname = session('RealName');    //真实姓名
        $content = $_POST['content'];  //真实姓名

        if($type=='option1'){
            $type='普通邮件';
        }else{
            $type='重要邮件';
        }

        $data=array(
            'Sender'=>$realname,
            'Recipient'=>$recipient,
            'Topic'=>$topic,
            'Type'=>$type,
            'Content'=>$content,
            'Time'=> date('Y-M-d H:i:s',time())
        );

        $result = M('email')->add($data);

        if($result){
            $this->ajaxReturn(array('status'=>1),'json');   //邮件发送成功
        }else{
            $this->ajaxReturn(array('status'=>2),'json');   //邮件发送失败
        }

    }


    //删除联系人
    public function delete(){

        $contactName = $_POST['contactName'];   //联系人姓名

        $UserID = session('LoginID');           //登录用户的ID
        $ContactID = M('employees')->where(array('RealName'=>$contactName))->getField('EmployeeID');

        $count = M('r_employees_employees')->where(array('EmployeeID'=>$UserID,'EmployeeID2'=>$ContactID))->delete();

//        var_dump($count);

        if($count!=0){
            $this->ajaxReturn(array('status' => 1,'ContactID'=>$ContactID), 'json');//删除成功
        }else{
            $this->ajaxReturn(array('status' => 2,'ContactID'=>$ContactID), 'json');//删除失败
        }
    }


    //联系人添加申请
    public function request(){

        $isAgree = $_GET['isAgree'];
        $ContactName = $_GET['ContactName'];    //联系人真实姓名
        $UserID = session('LoginID');           //登录用户的ID

//        var_dump($ContactName);

        $ContactID = M('employees')->where(array('RealName'=>$ContactName))->getField('EmployeeID');


        if($isAgree==1){            //同意

            $data1 = array(
                'EmployeeID'=>$UserID,
                'EmployeeID2'=>$ContactID
            );
            $data2 = array(
                'EmployeeID'=>$UserID,
                'EmployeeID2'=>$ContactID
            );

            M('request_contact')->where(array('EmployeeID'=>$UserID,'EmployeeID2'=>$ContactID))->delete();
            M('request_contact')->where(array('EmployeeID'=>$ContactID,'EmployeeID2'=>$UserID))->delete();
            M('r_employees_employees')->add($data1);
            M('r_employees_employees')->add($data2);

            $this->success('通讯录已更新');

        }elseif($isAgree==2){       //忽略

            M('request_contact')->where(array('EmployeeID'=>$UserID,'EmployeeID2'=>$ContactID))->delete();

        }

    }


}




