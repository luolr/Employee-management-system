<?php
class SystemAction extends Action{

    //部门管理
    public function department_m(){

        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='系统管理员'){
                $this->error('只有系统管理员可以访问该模块');
            }
        }

        $department_list = M('department')->select();
        $this->assign('department_list',$department_list);

        $this->display();

    }

    //员工管理
    public function employees_m(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='系统管理员'){
                $this->error('只有系统管理员可以访问该模块');
            }
        }

        $array['Role'] = array('IN',array('总经理','总经理秘书','部门经理','普通员工'));
        $employees = M('employees')->where($array)->select();
        $department = M('department')->select();
        $this->assign('employees',$employees);
        $this->assign('department',$department);

        $this->display();
    }

    //删除部门
    public function de_delete(){

        $departmentName = $_POST['departmentName'];
        $result = M('department')->where(array('Name'=>$departmentName))->delete();

        if($result){
            $this->ajaxReturn(array('status'=>1),'json');//删除成功
        }else{
            $this->ajaxReturn(array('status'=>2),'json');//删除失败
        }

    }

    //删除员工
    public function em_delete(){

        $employeeName = $_POST['employeeName'];
        $employeeId = M('employees')->where(array('RealName'=>$employeeName))
            ->getField('EmployeeID');
        $result = M('employees')->where(array('RealName'=>$employeeName))->delete();

        //删除请求联系人表里的相关信息
        M('r_request_contact')
            ->where("EmployeeID= '%s' or EmployeeID2 = '%s'",array($employeeId,$employeeId))
            ->delete();
        //删除联系人表里的相关信息
        M('r_employees_employees')
            ->where("EmployeeID= '%s' or EmployeeID2 = '%s'",array($employeeId,$employeeId))
            ->delete();

        if($result){
            $this->ajaxReturn(array('status'=>1),'json');//删除成功
        }else{
            $this->ajaxReturn(array('status'=>2),'json');//删除失败
        }

    }

    //确认部门是否已存在
    public function checkDeName(){
        $name = $_POST['name'];
        $result = M('department')->where(array('Name'=>$name))->select();
        if($result) {
            $this->ajaxReturn(array('status' => 1), 'json'); //存在
        }else{
            $this->ajaxReturn(array('status'=>2),'json'); //不存在
        }
    }

    //确认用户是否存在
    public function checkName(){
        $name = $_POST['name'];
        $result = M('employees')->where(array('RealName'=>$name))->select();
        if($result) {
            $this->ajaxReturn(array('status' => 1), 'json'); //用户存在
        }else{
            $this->ajaxReturn(array('status'=>2),'json'); //用户不存在
        }
    }

    //确认用户名是否可用
    public function checkUserName(){
        $username = $_POST['username'];
        $result = M('employees')->where(array('UserName'=>$username))->select();
        if($result) {
            $this->ajaxReturn(array('status' => 1), 'json'); //用户名存在
        }else{
            $this->ajaxReturn(array('status'=>2),'json'); //用户名不存在
        }
    }

    //部门编辑
    public function de_edit(){

        $oldName = $_POST['oldName'];
        $newName = $_POST['newName'];

        $data1 = array(
            'Department'=>$newName
        );
        $result1 =M('employees')->where(array('Department'=>$oldName))->save($data1);

        $data2 = array(
            'Name'=>$newName
        );
        $result2 =M('department')->where(array('Name'=>$oldName))->save($data2);

        if($result1&&$result2){
            $this->ajaxReturn(array('status' => 1), 'json'); //编辑成功
        }else{
            $this->ajaxReturn(array('status'=>2),'json'); //编辑失败
        }

    }

    //员工编辑
    public function em_edit(){

        $name = $_POST['name'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $department = $_POST['department'];

        if($role==0){//不修改角色

            $data = array(
                'Telephone'=>$telephone,
                'Email'=>$email,
                'Department'=>$department,
            );

            M('employees')->where(array('RealName'=>$name))->save($data);

            $this->ajaxReturn(array('status' => 1), 'json'); //编辑成功


        }else{//修改角色

            switch ($role){
                case 1:$role='总经理';break;
                case 2:$role='总经理秘书';break;
                case 3:$role='部门经理';break;
                case 4:$role='普通员工';break;
            }

            $data = array(
                'Telephone'=>$telephone,
                'Email'=>$email,
                'Department'=>$department,
                'Role'=>$role
            );

            $result = M('employees')->where(array('RealName'=>$name))->save($data);
            if($result) {
                $this->ajaxReturn(array('status' => 1), 'json'); //编辑成功
            }else{
                $this->ajaxReturn(array('status'=>2),'json'); //编辑失败
            }

        }

    }

    //新增部门
    public function addDepartment(){

        $de_name = $_POST['de_name'];
        $de_manager = $_POST['de_manager'];

        if($de_name==''||$de_name==null){
            $this->error('部门名称不能为空！');
        }else{
            if($de_manager==''||$de_manager==null){

                $data = array(
                    'Name'=>$de_name,
                    'Manager'=>$de_manager,
                );
                $result = M('department')->add($data);
                if($result){
                    $this->success('部门创建成功！');
                }else{
                    $this->success('部门创建失败！');
                }

            }else{

                $data1 = array(
                    'Name'=>$de_name,
                    'Manager'=>$de_manager,
                );
                $result1 = M('department')->add($data1);

                $data2 = array(
                    'Department'=>$de_name,
                    'Role'=>'部门经理',
                );
                $result2 = M('employees')->where(array('RealName'=>$de_manager))->save($data2);

                if($result1&&$result2){
                    $this->success('部门新增成功！');
                }else{
                    $this->success('部门新增失败！');
                }

            }

        }

    }

    //新增员工
    public function addEmployee(){

        $name = $_POST['name'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $department = $_POST['department'];
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        if($name==''||$name==null||$role==0||$userName==''
            ||$userName==null||$password==''||$password==null){
            $this->error('信息填写不完整');
        }else{

            switch ($role){
                case 1:$role='总经理';break;
                case 2:$role='总经理秘书';break;
                case 3:$role='部门经理';break;
                case 4:$role='普通员工';break;
            }


            $data = array(
                'RealName'=>$name,
                'Telephone'=>$telephone,
                'Email'=>$email,
                'Role'=>$role,
                'Department'=>$department,
                'UserName'=>$userName,
                'Password'=>$password,
            );

            $result = M('employees')->add($data);
            if($result){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }

        }

    }

    //修改个人信息
    public function saveinfo(){
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $realName = session('RealName');

        if($telephone==null||$telephone==''||$email==null||$email==''){
            $this->error('信息提交的不完整');
        }else{
            if($telephone==session("Telephone")&&$email==session("Email")){
                $this->error('个人信息没有要修改的地方');
            }else{
                $data = array(
                    'Telephone'=>$telephone,
                    'Email'=>$email,
                );
                $result = M('employees')->where(array('RealName'=>$realName))->save($data);

                if($result){
                    session('Telephone',$telephone);
                    session('Email',$email);
                    $this->success('个人信息修改成功');
                }else{
                    $this->error('个人信息修改失败');
                }
            }
        }

    }

    //修改个人密码
    public function changePwd(){
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $againPassword = $_POST['againPassword'];
        $realName = session('RealName');

        if($oldPassword==null||$oldPassword==''||$newPassword==null
            ||$newPassword==''||$againPassword==null||$againPassword==''){
            $this->error('信息提交的不完整');
        }else{
            $password = M('employees')->where(array('RealName'=>$realName))
                ->getField('Password');
            if($oldPassword!=$password){
                $this->error('旧密码不正确');
            }else{
                if($newPassword!=$againPassword){
                    $this->error('新密码不一致');
                }else{
                    $data = array(
                        'Password'=>$newPassword,
                    );
                    $result=M('employees')->where(array('RealName'=>$realName))->save($data);

                    if($result){
                        $this->success('密码修改成功');
                    }else{
                        $this->error('密码修改失败');
                    }
                }
            }
        }
    }


}