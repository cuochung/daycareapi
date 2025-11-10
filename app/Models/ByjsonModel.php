<?php
namespace App\Models;
use CodeIgniter\Model;

class ByjsonModel extends Model
{

  //載入指定的資料庫名
  function loadDatabse($databaseName){ 
      $db = \Config\Database::connect([
          'DSN'      => '',
          'hostname' => 'localhost',
          'username' => 'root',
          'password' => '2ugiagal',
          'database' => $databaseName,
          'DBDriver' => 'MySQLi',
          'DBPrefix' => '',
          'pConnect' => false,
          'DBDebug'  => (ENVIRONMENT !== 'production'),
          'cacheOn'  => false,
          'cacheDir' => '',
          'charset'  => 'utf8',
          'DBCollat' => 'utf8_general_ci',
          'swapPre'  => '',
          'encrypt'  => false,
          'compress' => false,
          'strictOn' => false,
          'failover' => [],
          'port'     => 3306,
      ]);
      return $db;

      // $query = $db->table($sheetName)->get();

      // // 将查询结果转换为模型实体对象数组
      // $results = $query->getResult($this->returnType);

      // // 返回模型实体对象数组
      // return $results;
  }

  function searchDataInclude($databaseName,$sheetName,$searchKey)
  {
    $db = $this -> loadDatabse($databaseName);

    $sql = "SELECT * FROM `".$sheetName."` WHERE";
    
    foreach($searchKey as $key => $value){
      if ($key == 0){
        $sql = $sql." JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
      }else{
        $sql = $sql." AND JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
      }
    }

    return $db -> query($sql);
  }

  //2023.11.25,判斷日期格式為 YYYY-MM-DD;資料的部分也全部調整日期格式
  function searchDataAndDate($databaseName,$sheetName,$data)
  {
    $db = $this -> loadDatabse($databaseName);

    $firstKey = 0; //設定第一個搜尋初始值,如果是1就不用加 And

    $sql = "SELECT * FROM `".$sheetName."` WHERE";

    //由於目前資料中的日期會因為資料庫不同.判斷的key就不同.所以建構一個陣列去指定對應的key
      $dateKey = [
        'export' => 'out_date',
        'import' => 'in_date',
        'twohand' => 'th_in_date',
        'change' => 'ch_date',
        'fix' => 'f_date',
      ];
    //判斷日期區間的部分
    if ($data['startDate'] != '' && $data['endDate'] != '' && isset($dateKey[$sheetName])){
      $sql = $sql." (
        STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(datalist, '$.".$dateKey[$sheetName]."')), '%Y-%c-%d') 
        BETWEEN '".date("Y-n-j", strtotime($data['startDate']))."' AND '".date("Y-n-j", strtotime($data['endDate']))."'
        OR
        STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(datalist, '$.".$dateKey[$sheetName]."')), '%Y-%c-%d') 
        BETWEEN '".$data['startDate']."' AND '".$data['endDate']
        ."')";

      $firstKey ++;  
    }

    //判斷搜尋KEY的部分
    if ($data['searchKey'] != ''){
      foreach($data['searchKey'] as $key => $value){
        if ($firstKey == 0){
          $sql = $sql." JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
          $firstKey ++;
        }else{
          $sql = $sql." AND JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
        }
      }
    }

    return $db -> query($sql);
  }
  //舊版,判斷日期格式為 YYYY/MM/DD 
  // function searchDataAndDate($databaseName,$sheetName,$data)
  // {
  //   $db = $this -> loadDatabse($databaseName);

  //   $firstKey = 0; //設定第一個搜尋初始值,如果是1就不用加 And

  //   $sql = "SELECT * FROM `".$sheetName."` WHERE";

  //   //由於目前資料中的日期會因為資料庫不同.判斷的key就不同.所以建構一個陣列去指定對應的key
  //     $dateKey = [
  //       'export' => 'out_date',
  //       'import' => 'in_date',
  //       'twohand' => 'th_in_date',
  //       'change' => 'ch_date',
  //       'fix' => 'f_date',
  //     ];
  //   //判斷日期區間的部分
  //   if ($data['startDate'] != '' && $data['endDate'] != '' && isset($dateKey[$sheetName])){
  //     $sql = $sql." (
  //       STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(datalist, '$.".$dateKey[$sheetName]."')), '%Y/%c/%d') 
  //       BETWEEN '".date("Y/n/j", strtotime($data['startDate']))."' AND '".date("Y/n/j", strtotime($data['endDate']))."'
  //       OR
  //       STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(datalist, '$.".$dateKey[$sheetName]."')), '%Y/%c/%d') 
  //       BETWEEN '".$data['startDate']."' AND '".$data['endDate']
  //       ."')";

  //     $firstKey ++;  
  //   }

  //   //判斷搜尋KEY的部分
  //   if ($data['searchKey'] != ''){
  //     foreach($data['searchKey'] as $key => $value){
  //       if ($firstKey == 0){
  //         $sql = $sql." JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
  //         $firstKey ++;
  //       }else{
  //         $sql = $sql." AND JSON_SEARCH(datalist, 'one', '%".$value."%') IS NOT NULL";
  //       }
  //     }
  //   }

  //   return $db -> query($sql);
  // }
  
  //依 key = value 去搜尋
  function searchByKeyValue($databaseName,$sheetName,$data)
  {
    $db = $this -> loadDatabse($databaseName);
    $sql = "SELECT * FROM `".$sheetName."` WHERE JSON_EXTRACT(datalist, '$.".$data['key']."') = '".$data['value']."';";

    return $db -> query($sql);
  }

  //依 key like value 去搜尋
  function searchIncludeByKeyValue($databaseName,$sheetName,$data)
  {
    $db = $this -> loadDatabse($databaseName);
    $sql = "SELECT * FROM `".$sheetName."` WHERE JSON_EXTRACT(datalist, '$.".$data['key']."') like '%".$data['value']."%';";

    return $db -> query($sql);
  }
}


