<?php
namespace App\Controllers;
use App\Models\GeneralModel;
date_default_timezone_set("Asia/Shanghai"); //設制現在時間,不然會抓伺服器設定的時間
header("Access-Control-Allow-Origin: *");
// header("Content-type:application/json; charset=utf-8");
// header("Access-Control-Allow-Headers: Content-type");

class General extends BaseController
{
    function index($databaseName,$sheetName)
    {
        echo 'use general:'.$databaseName;
        $model = new GeneralModel();
        $data = $model->getAll($databaseName,$sheetName);
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    function getAll($databaseName,$sheetName){
        $model = new \App\Models\GeneralModel(); //載入指定Model
        $query = $model->getAll($databaseName,$sheetName); //需傳入指定資料庫 $db ,再透過 $sheetName 取得指定表單內容
        $data = $query -> getResultArray(); //用getResult()的話,在php裡無法直接取用
        return $this->response->setJSON($data);
    }

    function add($databaseName,$sheetName){
        $model = new \App\Models\GeneralModel(); //載入指定Model
        $data = $this->request->getPost();

        $rs['state'] = $model->add($databaseName,$sheetName,$data);
        return $this->response->setJSON($rs);
    }

    function edit($databaseName,$sheetName){
        $model = new \App\Models\GeneralModel(); //載入指定Model
        $data = $this->request->getPost();

        $rs['state'] = $model->edit($databaseName,$sheetName,$data);
        return $this->response->setJSON($rs);
    }

    function delv3($databaseName,$sheetName){
        $model = new \App\Models\GeneralModel(); //載入指定Model
        $data = $this->request->getPost();

        $snkey = $data['snkey'];
		unset($data['snkey']);

		//記錄刪除的人,時間,內容
		$outdata['del_state'] = $model->add($databaseName,'deldata',$data);

        $outdata['state'] = $model->del($databaseName,$sheetName,$snkey);
        return $this->response->setJSON($outdata);
    }

    //多筆型新增
    function addMulti($databaseName,$sheetName){
		$model = new \App\Models\GeneralModel(); //載入指定Model
        $data = $this->request->getPost();

		foreach ($data as $key => $value) {
			$outdata[$key]['state'] = $model->add($databaseName,$sheetName,$value);
		}

        return $this->response->setJSON($outdata);
	}


    function upload($databaseName,$sheetName) {  //上傳圖片
        $model = new \App\Models\GeneralModel(); //載入指定Model
        // $data = $this->request->getPost();

		//取得snkey的資料->判斷圖檔是否存在.如果存在就刪掉
		// $snkey = $data['snkey'];
		// $query = $model->getByKey($databaseName,$sheetName,$snkey);
		// $picData = json_decode($query->getRowArray()['datalist'],TRUE);

		//刪除已存在的上傳圖檔
		// if (isset($picData['picname'])){
		// 	$pic_name = $picData['picname'];
		// }else{
		// 	$pic_name = "";
		// }
		// $path = FCPATH.'upload/'.$sheetName.'/'.$pic_name;	        
		// $path = WRITEPATH.'upload/'.$sheetName.'/'.$pic_name;
        // FCPATH -> ci根目錄,WRITEPATH -> ci WRITEPATH目錄
		// if (is_file($path) && $pic_name != 'lazypic.jpg'){
		// 	unlink($path); 
		// }

        $fileInfo = $this->request->getFile('file'); //取得上傳檔案
        $newName = $fileInfo->getRandomName(); //設定新檔名
        $uploadPath = FCPATH .'upload';  //設定上傳暫存檔案位置
        
        //調整檔案size -> save到指定目錄中
        $image = \Config\Services::image()
            ->withFile($fileInfo)
            ->resize(480, 480, true, 'height');
            // ->save($uploadPath .'/'.$sheetName.'/'. $newName);

        if ($image->save($uploadPath .'/'.$sheetName.'/'. $newName)) {
            $result = array(
                'state' => 1,
                'newName' => $newName
            );
        }else{
            $result = array(
                'state' => 0,
                'newName' => ''
            );
        }
        // $fileInfo->move($uploadPath,$newName); //上傳到指定目錄中
        
        return $this->response->setJSON($result); //回傳狀態及檔名
	}
    
    function delPic($directory,$file)   //刪除資料庫中的實際圖片
	{
		if ($file == 'lazypic.jpg'){exit;}
		
		$path = FCPATH.'upload/'.$directory.'/'.$file;
        if (is_file($path)){
			unlink($path); 
            $result = array(
                'state' => 1,
                'msg' => 'del pic ok'
            );
		}else{
            $result = array(
                'state' => 1,
                'msg' => 'no match pic'
            );
        }
		return $this->response->setJSON($result); //回傳狀態及檔名
	}
}
