<?php
namespace app\admin\controller;
use think\Controller;
class Brand extends Controller
{
    public function lst()
    {
        $brands = db('brand')->order('id desc')->paginate(10);

        $this->assign('brands',$brands);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');

            if(stripos($data['brand_url'],'http://')===false){
                $data['brand_url'] = 'http://'.$data['brand_url'];

            }
    		//处理图片上传
    		if($_FILES['brand_img']['tmp_name']){
    			$data['brand_img']=$this->upload();
    		}
    		$add=db('brand')->insert($data);
    		if($add){
    			$this->success('添加品牌成功！','lst');
    		}else{
    			$this->error('添加品牌失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
        $id = input('id');
        $brands = db('brand')->find($id);
        $this->assign('brands',$brands);
        return view();
    }

    public function del($id)
    {
        $res = db('brand')->delete($id);
      if($res){
                $this->success('删除成功！','lst');
            }else{
                $this->error('删除失败！');
            }
    }

    //上传图片
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('brand_img');
    
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS .'uploads');
        if($info){
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
            die;
        }
    }
}


}