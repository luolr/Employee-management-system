<?php

class FileAction extends Action{

    public function choose_manager(){
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
//        }else{
//            if($_SESSION['Role']!='部门经理'){
//                $this->error('只有部门经理可以访问该模块');
//            }
//        }

        $department = session('Department');
        $employees = M('employees')->where(array('Department'=>$department))->select();
        $this->assign('employees',$employees);
        $this->display();

    }

    public function file_manage(){
        $FileManager = M('department')->where(array('Name'=>session('Department')))
            ->getField('FileManager');
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
//        }else{
//            if($_SESSION['RealName']!=$FileManager){
//                $this->error('你不是部门文件管理员，无权访问该模块');
//            }
//        }

        $FileManager = M('department')->where(array('Name'=>session('Department')))
            ->getField('FileManager');
        if(session('RealName')==$FileManager) {

            $files = M('file')->where(array('Department' => session('Department')))->select();
            if ($files) {
                $this->assign('files', $files);
            }
        }
//        }else{
//            $this->error('只有指定的部门文件管理员可以访问该页面');
//        }

        $this->display();
    }

    //设置部门文件管理员
    public function setManager(){
        $realName = $_POST['realName'];
        $department = session('Department');
        $role = session('Role');

        if($role=='部门经理'){

            $data = array(
                'FileManager'=>$realName
            );
            M('department')->where(array('Name'=>$department))->save($data);

            $this->ajaxReturn(array('status'=>1),'json');


        }else{
            $this->error('只有部门经理可以指定人员负责部门文件管理工作');
        }


    }

    //文件上传
    public function upload()
    {
        import('@.Org/UploadFile');
        $config = array(
            'maxSize' => 3145728,
            'rootPath' => '',
            'savePath' => './Uploads/other/',
            'allowExts' => array('txt','doc', 'docx','wps','xls','xlsx','et','ppt','pptx','dps','pdf',),
            'autoSub' => false,
            'saveRule' => 'time',
        );

        $upload = new UploadFile($config);
        $info = $upload->upload();

        if (!$info) {     // 上传错误提示错误信息

            $this->error($upload->getErrorMsg());

        } else {          // 上传成功 获取上传文件信息

            $information = $upload->getUploadFileInfo();
            $saveInfo = $information[0]['savepath'] . $information[0]['savename'];
            ob_end_clean();

//                $file = D('File');

//                var_dump($information[0]);
//                var_dump($info['savepath'].$info['savename']);

            $topic = $_POST['topic'];
            $filename = $information[0]['name'];
            $filePath = $saveInfo;
            $uploader = session('RealName');
            $department = session('Department');
            $introduction = $_POST['introduction'];

            $data = array(
                'Topic'=>$topic,
                'FileName'=>$filename,
                'FilePath'=>$filePath,
                'Uploader'=>$uploader,
                'Department'=>$department,
                'Introduction'=>$introduction,
                'Time'=>date('Y-M-d H:i:s',time()),
            );

            $result = M('file')->add($data);
            if($result){
                $this->success('文件上传成功');
            }else{
                $this->error('文件上传失败');
            }
        }
    }


    //文件下载
    public function download(){
        $id = $_GET['id'];
        $FilePath = M('file')->where(array('ID'=>$id))->getField('FilePath');
        import('@.Org/Http');
        dump($id) ;
        $download = Http::download($FilePath);
    }

    //文件删除
    public function delete(){
        $id = $_POST['id'];
        $type = M('file')->where(array('ID'=>$id))->getField('Type');

        if(!$type=='fujian'){

            $filePath = M('file')->where(array('ID'=>$id))->getField('FilePath');
            $isDelete = unlink($filePath);

            if($isDelete){

                $result = M('file')->where(array('ID'=>$id))->delete();
                if($result&&$isDelete){
                    $this->ajaxReturn(array('status'=>1),'json');//文件删除成功
                }

            }else{
                $this->ajaxReturn(array('status'=>3),'json');//文件不存在
            }


        }else{
            $this->ajaxReturn(array('status'=>2),'json');//文件为公文附件，不能删除
        }

    }
}