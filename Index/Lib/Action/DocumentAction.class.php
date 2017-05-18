<?php

class DocumentAction extends Action
{

    //公文撰写
    public function document_w()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }

        $this->display();
    }

    //公文阅读
    public function document_r()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }

        $realName = session('RealName');
        $department = session('Department');

        $result1 = M('document')->where(array('State'=>'已审核'))->where("Target1 = '%s' or Target2 = '%s'",array($realName,$realName))->select();
        $result2 = M('document')->where(array('State'=>'已审核'))->where("Target1 = '%s' or Target2 = '%s'",array($department,$department))->select();

        $this->assign("result1",$result1);
        $this->assign("result2",$result2);

        $this->display();
    }

    //公文审核
    public function document_s()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }else{
            if($_SESSION['Role']!='总经理'){
                $this->error('只有总经理可以访问该模块');
            }
        }

        $result = M('document')->where(array('State'=>'未审核'))->select();

        $this->assign("result",$result);
        $this->display();
    }

    //公文归档
    public function document_m()
    {
        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }
//        }else{
//            if($_SESSION['Role']!='总经理秘书'){
//                $this->error('只有总经理秘书可以访问该模块');
//            }
//        }

        if (!isset($_SESSION['RealName'])) {
            $this->error('请先登录', U('Index/login'), 1);
        }

        $result = M('document')->select();

        $this->assign("result",$result);
        $this->display();
    }


    //公文撰写——提交公文
    public function upload()
    {
        $role = session('Role');
        if($role!='部门经理'||$role!='总经理秘书'){
            $this->error('只有部门经理和总经理秘书可以撰写公文','Index/index');
        }else{

            if (!empty($_FILES)) {

                import('@.Org/UploadFile');
                $config = array(
                    'maxSize' => 3145728,
                    'rootPath' => '',
                    'savePath' => './Uploads/',
                    'allowExts' => array('doc', 'docx'),
                    'autoSub' => false,
                    'saveRule' => 'time',
                );

                $upload = new UploadFile($config);
                $info = $upload->upload();

                if (!$info) {     // 上传错误提示错误信息

                    $this->error($upload->getErrorMsg());

                } else {          // 上传成功 获取上传文件信息

                    $information = $upload->getUploadFileInfo();
                    $saveInfo = $information[0]['savepath'].$information[0]['savename'];

    //                $file = D('File');

    //                var_dump($information[0]);
    //                var_dump($info['savepath'].$info['savename']);

                    $filename = $information[0]['name'];
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $author = $_POST['author'];
                    $target1 = $_POST['target1'];
                    $target2 = $_POST['target2'];
                    $keyword = $_POST['keyword'];
                    $introduction = $_POST['introduction'];

                    //主送目标
                    if($target1==0){
                        $this->error('主送目标不能为空');
                    }elseif($target1==1){
                        $target1 = $_POST['department1'];
                    }elseif($target1==2){
                        $target1 = $_POST['targetName1'];
                    }

                    //抄送目标
                    if($target2==1){
                        $target2 = $_POST['department2'];
                    }elseif($target2==2){
                        $target2 = $_POST['targetName2'];
                    }


                    $data1 = array(
                        'Number'=>$id,
                        'Title'=>$title,
                        'Author'=>$author,
                        'Target1'=>$target1,
                        'Target2'=>$target2,
                        'Keyword'=>$keyword,
                        'Introduction'=>$introduction,
                        'FilePath'=>$saveInfo,
                        'FileName'=>$filename,
                        'Time'=>date('Y-M-d H:i:s',time()),
                        'State'=>'未审核',
                        'IsRead'=>'未读',
                    );
                    M('document')->add($data1);

                    $data2 = array(
                        'Topic'=>$title.'——附件',
                        'FileName'=>$filename,
                        'FilePath'=>$saveInfo,
                        'Department'=>session('Department'),
                        'Uploader'=>$author,
                        'Time'=>date('Y-M-d H:i:s',time()),
                        'Type'=>'fujian',
                        'Introduction'=>$introduction
                    );
                    M('file')->add($data2);
                    $this->success('公文提交成功');

                }

            } else {
                $this->error('必须上传公文附件');
            }

        }

    }


    //验证输入的目标是否存在
    public function validation(){
        $name = $_POST['name'];
        $result = M('employees')->where(array('RealName'=>$name))->select();

        if($result){
            $this->ajaxReturn(array('status'=>1),'json');//真实存在
        }else{
            $this->ajaxReturn(array('status'=>2),'json');//不存在
        }
    }

    //验证公文编号是否被使用
    public function Number(){
        $DocumentID = $_POST['DocumentID'];
        $result = M('document')->where(array('Number'=>$DocumentID))->select();

        if($result){
            $this->ajaxReturn(array('status'=>1),'json');//公文编号已被使用
        }else{
            $this->ajaxReturn(array('status'=>2),'json');//公文编号可以使用
        }
    }



    //阅读——弹窗
    public function read(){
        $documentId = $_POST['documentId'];
        $document = M('document')->where(array('Number'=>$documentId))->find();

        if($document){
            M('document')->where(array('Number'=>$documentId))->save(array('IsRead'=>'已读'));

            $checker = $document['Checker'];
            $opinion = $document['Opinion'];
            if($checker==null){
                $checker = '';
            }
            if($opinion['Checker']==null){
                $opinion = '';
            }

            $this->ajaxReturn(array(
                'status'=>1,
                'Title'=>$document['Title'],
                'Author'=>$document['Author'],
                'Target1'=>$document['Target1'],
                'Target2'=>$document['Target2'],
                'Keyword'=>$document['Keyword'],
                'Introduction'=>$document['Introduction'],
                'FilePath'=>$document['FilePath'],
                'FileName'=>$document['FileName'],
                'Time'=>$document['Time'],
                'State'=>$document['State'],
                'Checker'=>$checker,
                'Opinion'=>$opinion,
            ),'json');   //公文存在
        }else{
            $this->ajaxReturn(array('status'=>2),'json');   //公文不存在
        }
    }


    //附件下载
    public function download(){
        $documentId = $_GET['documentId'];
        $FilePath = M('document')->where(array('Number'=>$documentId))->getField('FilePath');
        import('@.Org/Http');
        $download = Http::download($FilePath);
    }

    //公文审核通过
    public function pass(){
        $RealName = session('RealName');
        $documentId = $_GET['documentId'];
        $opinion = $_GET['opinion'];
        $role = M('employees')->where(array('RealName'=>$RealName))->getField('Role');
        if($role=='总经理'){
            $result = M('document')->where(array('Number'=>$documentId))->save(array('Checker'=>$RealName,'State'=>'已审核','Opinion'=>$opinion));
            if($result){
                $this->success('该公文审核通过，目标用户可以阅读','',3);
            }else{
                $this->error('未知错误');
            }
        }else{
            $this->error('只有总经理有权限审核公文');
        }

    }

    //公文审核不通过
    public function back(){
        $RealName = session('RealName');
        $documentId = $_GET['documentId'];
        $role = M('employees')->where(array('RealName'=>$RealName))->getField('Role');
        if($role=='总经理'){
            $result = M('document')->where(array('Number'=>$documentId))->save(array('State'=>'未通过'));
            if($result){
                $this->error('该公文已标记为未通过');
            }else{
                $this->error('未知错误');
            }
        }else{
            $this->error('您没有权限审核公文');
        }

    }




}