<?php
namespace app\admin\controller;
use think\Controller;
class Cate extends Controller
{
    public function lst()
    {
        $cate = db('cate')->order('id desc')->paginate(10);

        $this->assign('cate',$cate);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    
    		$add=db('cate')->insert($data);
    		if($add){
    			$this->success('添加分类成功！','lst');
    		}else{
    			$this->error('添加分类失败！');
    		}
    		return;
    	}

        $res = db('cate')->select();

            $this->assign('res',$res);  

        return view();
    }

    public function edit()
    {
         if(request()->isPost()){
            $data=input('post.');

            // dump($data);die;

            if($data['brand_url']&&stripos($data['brand_url'],'http://')===false){
                $data['brand_url'] = 'http://'.$data['brand_url'];

            }
            //处理图片上传
            if($_FILES['brand_img']['tmp_name']){

                $oldBrands = db('brand')->field('brand_img')->find($data['id']);
              // dump($oldBrands);die;
             $old = UPLOADS.$oldBrands['brand_img'];
             // dump($old);die;

             if(file_exists($old)){

                @unlink($old);
             }
             
                $data['brand_img']=$this->upload();
            }
            $save = db('brand')->update($data);

              $validate = validate('brand');

             if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

            if($save){
                $this->success('修改品牌成功！','lst');
            }else{
                $this->error('修改品牌失败！');
            }
            return;
        }
        $id = input('id');
        $brands = db('brand')->find($id);
          $this->assign('brands',$brands);
        return view();
    }

    public function del($id)
    {
        $res = db('brand')->delete($id);
      if($res){
                $this->success('删除成功！','lst',1);
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