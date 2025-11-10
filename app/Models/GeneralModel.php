<?php
namespace App\Models;
use CodeIgniter\Model;

class GeneralModel extends Model
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

  //取得所有資料: $db是目前載入的資料庫名,$sheetName 是指定表單裡的 裡的所有資料
  function getAll($databaseName,$sheetName)
  {
    $db = $this -> loadDatabse($databaseName);
    return $db->table($sheetName)->get();
    // $query = $db->table($sheetName)->get();
    // $query = $db->query('SELECT * FROM '.$sheetName);
    // return $query->getResult();
  }

  //透過指定KEY 取得資料，一般是ID 或SNKEY
  function getByKey($databaseName,$sheetName,$snkey){
    $db = $this -> loadDatabse($databaseName);
    $builder = $db->table($sheetName);

    $builder->where('snkey', $snkey); //依狀況更動 Id或snkey
    return $builder->get();
  }

  //新增資料到指定 $sheetName 裡
  function add($databaseName,$sheetName,$data){
    $db = $this -> loadDatabse($databaseName);
    $builder = $db->table($sheetName);
    
    return $builder->insert($data);
  }

  //透過 id或snkey 直接比對資料,直接更換資料的內容 -> 不存在的欄位會被清空
  function replaceFn($databaseName,$sheetName,$data){
    $db = $this -> loadDatabse($databaseName);
    $builder = $db->table($sheetName);

    $rs['state'] = $builder->replace($data);
    return $rs;
  }

  //修改功能 -> 未指定到的欄位存在時不會被更動內容
  function edit($databaseName,$sheetName,$data){
    $db = $this -> loadDatabse($databaseName);
    $builder = $db->table($sheetName);

    $snkey = $data['snkey'];
    unset($data['snkey']);

    $builder->where('snkey', $snkey); //依狀況更動 Id或snkey
    return $builder->update($data);
  }

  //刪除指定ID 或SNKEY 的資料;
  function del($databaseName,$sheetName,$snkey){
    $db = $this -> loadDatabse($databaseName);
    $builder = $db->table($sheetName);

    $builder->where('snkey', $snkey); //依狀況更動
    return $builder->delete();
  }
  

}


