<?php
class Task
{
    const SHOW_BY_DEFAULT = 3;

    public static function checkEditText($id, $text){
      $db= DbConnection::getConnection();
      $query = 'SELECT text FROM tasks where id ='.$id;
      $result = $db->query($query);
      $row = $result->fetch_row();
      if(strcasecmp($row[0], $text)== 0){
        return false;
      }else{
        return true;
      }
    }

    public static function add($name,$email,$text){
      $db= DbConnection::getConnection();
      $status = "Выполняется";
      $query = 'INSERT INTO tasks(name,email,text,status) VALUES (?, ?, ?, ?)';
      $result = $db->prepare($query);
      $result->bind_param("ssss", $name, $email, $text, $status);
      return $result->execute();
    }

    public static function edit($id,$text,$status){
      $db= DbConnection::getConnection();
      $query = 'UPDATE tasks SET text = ?, status = ? WHERE id = ?';
      $result = $db->prepare($query);
      $result->bind_param("ssi", $text, $status, $id);
      return $result->execute();
    }

    public static function perform($id,$status){
      $db= DbConnection::getConnection();
      $query = 'UPDATE tasks SET status = ? WHERE id = ?';
      $result = $db->prepare($query);
      $result->bind_param("si", $status, $id);
      return $result->execute();
    }

    public static function getTotalElement(){
      $db= DbConnection::getConnection();
      $result = $db->query('SELECT count(id) AS count FROM tasks');
      $row = $result->fetch_row();
      return $row[0];
    }

    public static function getTasks($page = 1, $sortSelect = "id", $sortOrder = "DESC")
    {
      $db= DbConnection::getConnection();
      $taskList = array();
      $page = intval($page);
      $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
      $page = intval($page);
      $query = 'SELECT id,name,email,text,status '
                .'FROM tasks '
                .'ORDER BY '.$sortSelect.' '.$sortOrder
                .' LIMIT '. self::SHOW_BY_DEFAULT
                .' OFFSET '.$offset;

      $result = $db->query($query);

      $i = 0;
      while($row = $result->fetch_row()){
        $taskList[$i]['id'] = $row[0];
        $taskList[$i]['name'] = $row[1];
        $taskList[$i]['email'] = $row[2];
        $taskList[$i]['text'] = $row[3];
        $taskList[$i]['status'] = $row[4];
        $i++;
      }
      return $taskList;
    }

    public static function checkEmail($email){
      if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return true;
      }
      return false;
    }
    public static function checkName($name){
      if(strlen($name) > 1){
        return true;
      }
      return false;
    }
    public static function checkText($text){
      if(strlen($text) > 1){
        return true;
      }
      return false;
    }
    public static function isGuest(){
      if(isset($_SESSION['admin'])){
        return false;
      }else{
        return true;
      }
    }
}
