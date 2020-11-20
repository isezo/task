<?php

class MainController
{

  //проверка на авторизацию админа
  public function checkAdmin(){
    if(isset($_SESSION['admin'])){
      return $_SESSION['admin'];
    }else{
      header("Location: /login");
    }
  }

  //Функция добавление задачи
  public function add(){
    $name = '';
    $email = '';
    $text = '';
    $errors = false;
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $text = htmlspecialchars($_POST['text']);
    //валидация
    if(!Task::checkName($name)){
      $errors[] = 'Короткое имя';
    }
    if(!Task::checkEmail($email)){
      $errors[] = 'Неправильный email';
    }
    if(!Task::checkText($text)){
      $errors[] = 'Короткая задача';
    }

    //перечисление ошибок
    if(isset($errors)&&is_array($errors)){
      $alertText = '';
      foreach ($errors as $error) {
        $alertText .= $error.'\n';
      }
    }else{
      //обращение к модулю
      $result = Task::add($name,$email,$text);
      if($result ==true){
        echo '<script language="javascript">alert("Задача успешно добавлена")</script>';
      }
    }
    if(isset($error)){
      echo '<script language="javascript">alert("'.$alertText.'")</script>';
    }
  }

  //Функция изменение задачи
  public function edit(){
    $checkAdmin = $this->checkAdmin();
    $id = htmlspecialchars($_POST['id']);
    $text = htmlspecialchars($_POST['text']);
    $status = htmlspecialchars($_POST['status']);
    //проверка на совпадение строк
    if(Task::checkEditText($id,$text)){
      $pos = strrpos($status, "отредактировано администратором");
      if($pos === false){
        $status.=' отредактировано администратором';
      }
    }
    if(!Task::checkText($text)){
      $errors[] = 'Короткая задача';
    }
    //перечисление ошибок
    if(isset($errors)){
      foreach ($errors as $error) {
        $alertText .=$error.'/n';
      }
    }else{
      //обращение к модулю
      $result = Task::edit($id,$text,$status);
      if($result ==true){
        echo '<script language="javascript">alert("Задача успешно изменена")</script>';
      }
    }

    //валидация полей
    if(isset($error)){
      echo '<script language="javascript">alert("'.$error.'")</script>';
    }
  }

  public function perform(){
    $checkAdmin = $this->checkAdmin();
    $id = htmlspecialchars($_POST['id']);
    $status = "Выполнено";
    $pos = strrpos($_POST['status'], "отредактировано администратором");
    if($pos !== false){
      $status.=' отредактировано администратором';
    }
    $result = Task::perform($id,$status);
    if($result ==true){
      echo '<script language="javascript">alert("Задача выполнена")</script>';
    }
  }

  public function actionIndex($page = 1){
    header("Location: /1");
  }

  //Функция отображения страницы
  public function actionIndex($page = 1){
    $alertText = '';
    //добавление задачи
    if(isset($_POST['add']))
      $this->add();

    //Изменение задачи
    if(isset($_POST['edit']))
      $this->edit();

    //Выполнение задачи
    if(isset($_POST['perform']))
      $this->perform();

    $taskList = array();

    if(isset($_GET['sortSelect'])){
      $sortSelect = $_GET['sortSelect'];
    }else{
      $sortSelect = "name";
    }
    if(isset($_GET['sortOrder'])){
      $sortOrder = $_GET['sortOrder'];
    }else{
      $sortOrder = "DESC";
    }

    //получение таблицы задач
    $taskList = Task::getTasks($page,$sortSelect,$sortOrder);

    //получение количество элементов
    $total = Task::getTotalElement();

    $pagination = new Pagination($total,$page,Task::SHOW_BY_DEFAULT);
    require_once ('/views/index.php');
    echo $pagination->get($sortSelect,$sortOrder);
    return true;
  }

  //авторизация
  public function actionLogin(){
    $login = '';
    $password = '';
    if(isset($_POST['submit'])){
      $error = false;
      if($_POST['login'] ==''){
        $error = 'Все поля обязательны к заполнению';
      }else{
        $login = htmlspecialchars($_POST['login']);
      }

      if($_POST['password']==''){
        $error = 'Все поля обязательны к заполнению';
      }else{
        $password = htmlspecialchars($_POST['password']);
      }
      if($error == false){
        if($login != 'admin'){
          $error = 'Неправильный логин';
        } else{
          if($password !='123'){
            $error = 'Неправильный пароль';
          }else{
            $_SESSION['admin'] = true;
            header("Location: /1");
          }
        }
      }
      //валидация полей
      if(isset($error)){
        echo '<script language="javascript">alert("'.$error.'")</script>';
      }
    }
    require_once ('views/login.php');
    return true;
  }

  //выход из аккаунта
  public function actionLogout(){
    unset($_SESSION['admin']);
    header("Location: /1");
  }

}
