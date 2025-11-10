<?php
namespace App\Models;
use CodeIgniter\Model;

class NewsModel extends Model
{
  protected $table = 'news';
  protected $allowedFields = ['title', 'content', 'insertTime', 'updateTime'];

  public function getNews($id = false)
  {
    if ($id === false) {
      return $this->findAll();
    }

    return $this->where(['id' => $id])->first();
  }

  
  public function delNews($id){
    $this->delete(['id' => $id]);
  }

}


