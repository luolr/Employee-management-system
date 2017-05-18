<?php
class LoginAction extends Action {
    public function login(){
        if(!IS_POST) halt('页面不存在');

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user =M('employees')->where("username = '%s' and password = '%s'",
            array($username,$password))->select();

//        var_dump($user[0]['RealName']);

        if(!$user){
            $this->error('账号或者密码错误');
        }else{

//            $id = M('employees')->where(array('UserName'=>$username))->getField('EmployeeID');
//            $department = M('employees')->where(array('RealName'=>$user[0]['RealName']))->getField('Department');

            session('UserName',$username);                  //用户名
            session('RealName',$user[0]['RealName']);       //真实姓名
            session('LoginID',$user[0]['EmployeeID']);      //用户ID
            session('Department',$user[0]['Department']);   //用户所属部门
            session('LoginTime',$user[0]['LoginTime']);     //上次登录时间
            session('Role',$user[0]['Role']);               //用户角色
            session('Telephone',$user[0]['Telephone']);     //联系电话
            session('Email',$user[0]['Email']);             //个人邮箱

            $data['LoginTime'] = date('Y-M-d H:i:s',time());
            M('employees')->where(array('username'=>$username))->save($data);

//            var_dump($username);
//            var_dump($id);
//            var_dump($user[0]['RealName']);
//            var_dump($username);
            $this->success('登录成功','../Index/index');
        }
    }

}