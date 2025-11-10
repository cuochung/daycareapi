<?php
namespace App\Controllers;
use App\Models\ByjsonModel;
date_default_timezone_set("Asia/Shanghai"); //設制現在時間,不然會抓伺服器設定的時間
header("Access-Control-Allow-Origin: *");
header("Content-type:application/json; charset=utf-8");
header("Access-Control-Allow-Headers: Content-type");

class Byjson extends BaseController
{
  //搜尋datalist中,包含的key,同時傳入兩個以上的key時,要同時滿足才會回傳值 
  //v1 只透過 searchKey (Array) 去搜尋
  function searchDataInclude($databaseName,$sheetName)
  {
    $byjsonModel = new ByjsonModel();
    $data = $this->request->getPost('searchKey');

    $getData = $byjsonModel->searchDataInclude($databaseName,$sheetName,$data)->getResultArray();
    
    return $this->response->setJSON($getData);
  }

  //v2 判斷資料及日期區間
  function searchDataAndDate($databaseName,$sheetName)
  {
    $byjsonModel = new ByjsonModel();
    $data = $this->request->getPost();

    $getData = $byjsonModel->searchDataAndDate($databaseName,$sheetName,$data)->getResultArray();
    
    return $this->response->setJSON($getData);
  }
    
    
  //依 key = value 去搜尋
  function searchByKeyValue($databaseName,$sheetName)
  {
    $byjsonModel = new ByjsonModel();
    $data = $this->request->getPost();

    $getData = $byjsonModel->searchByKeyValue($databaseName,$sheetName,$data)->getResultArray();
    
    return $this->response->setJSON($getData);
  }

  //依 key like value 去搜尋
  function searchIncludeByKeyValue($databaseName,$sheetName)
  {
    $byjsonModel = new ByjsonModel();
    $data = $this->request->getPost();

    $getData = $byjsonModel->searchIncludeByKeyValue($databaseName,$sheetName,$data)->getResultArray();
    
    return $this->response->setJSON($getData);
  }
}
