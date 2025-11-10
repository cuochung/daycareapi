<?php
namespace App\Controllers;
use App\Models\GeneralModel;
date_default_timezone_set("Asia/Shanghai"); //設制現在時間,不然會抓伺服器設定的時間
header("Access-Control-Allow-Origin: *");
// header("Content-type:application/json; charset=utf-8");
// header("Access-Control-Allow-Headers: Content-type");

class Login extends BaseController
{
    function logining($databaseName) {  //登入
        $model = new \App\Models\GeneralModel(); //載入指定Model
        $postData = $this->request->getPost();

        $personnels = $model->getAll($databaseName,'personnel');
		$outdata['state']  = 'nomatch';  //nomatch

		foreach ($personnels -> getResultArray() as $key => $value) {
            $personnelItem = json_decode($value['datalist'],true); //拿來判斷用的資料jsone轉array

			$personnelItem['snkey'] = $value['snkey'];
			if ($personnelItem['account'] == $postData['account'] && $personnelItem['password'] == $postData['password']){
				$personnelItem['last_login_time'] = date("Y-m-d H:i:s");

				//記錄人員最後登人時間
                $newPersonnel = array(
                    'snkey' => $personnelItem['snkey'],
                    'datalist' => json_encode($personnelItem),
                );
                $model->edit($databaseName,'personnel',$newPersonnel);

				//記錄最後登人時間
                $cData = array(
                    'snkey' => 1,
                    // 'company_name' => '安泰玻璃工程行(NB ci441)',
                    'last_login_time' => date("Y-m-d H:i:s")
                );
                $model->edit($databaseName,'other_data',$cData);

				$outdata['pData'] = $personnelItem;
				$outdata['state'] = 'logined';
			}
		}
        return $this->response->setJSON($outdata);
	}

}
