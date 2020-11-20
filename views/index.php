<html>
<head>
  <link rel = "stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel = "stylesheet" href="/views/css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<center class="mt-2 mb-5">

  <ul>
    <li id="user">
      <div>
        <?php
	$isGuest =Task::isGuest();
        if($isGuest){
          echo '<a id="out" href="/login">Авторизация</a>';
        }  else{
    echo '<a class="" id="out" href="/logout">Выход</a>';
  }
     ?>
      </div>
    </li>
  </ul>
  <div class="form-group col-md-2">

    <form action = "#" method="get">
      <div class="d-f">
        <div class="desc-form">
          <select class="form-control mb-2 saveS" name = "sortSelect">
            <option value ="name">Имя пользователя</option>
            <option value ="email">Email</option>
            <option value ="status">Статус</option>
          </select>
        </div>
        <div class="desc-form">
          <input class = "saveC" type="radio" name="sortOrder" id="up" value="DESC" checked/> По возрастанию <br>
          <input class = "saveC" type="radio" name="sortOrder" id="down" value="ASC" /> По убыванию <br>
        </div>
      </div>
      <input class = "btn btn-success" type="submit" name="sortButton" value="Сортировать"/>
    </form>
  </div>


<table>
<tr><th hidden></th><th>Имя пользователя</th><th>Email</th><th>Текст задачи</th><th>Статус</th><th>Кнопка</th></tr>
<?php
echo "<tr><form id ='form' method='post'>
<td hidden> <input type='text' name='id'></td>
<td><input type='text' name='name' required></td>
<td><input type='text' name='email' pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$' required></td>
<td><input type = 'text' name = 'text' required multiple/></td>
<td></td>
<td><button  class='tblchange' name='add' type='submit' >Добавить</button></td></form></tr>";
foreach ($taskList as $newItem) {
   echo "<tr><form id='form{$newItem['id']}' method='post'>
   <td hidden> <input type='text' name='id' value='{$newItem['id']}'></td>
   <td>{$newItem['name']}</td>
   <td>{$newItem['email']}</td>";
   if$isGuest){
     echo "<td>{$newItem['text']}</td>";
     echo "<td><input type='text' name='status' value='{$newItem['status']}' hidden>{$newItem['status']}";
   }else{
      echo "<td><input type = 'text' value='{}' name = 'text' multiple/></td>";
      echo "<td><input type='text' name='status' value='{$newItem['status']}' hidden>{$newItem['status']}";
      $pos = strrpos($newItem['status'], "Выполнено");
      if($pos === false){
        echo " <br><button  class='tblchange' name= 'perform' type='submit'> Выполнить</td>";
      }
    }
    if($isGuest){
    	echo "<td><button  class='tblchange' name= 'edit' type='submit'> Сохранить</td></form></tr>";
    }
 }
?>
</table>
</center>

<script type="text/javascript">
  var radioElements= document.querySelectorAll('input[type="radio"]');
  var inputElements = document.querySelectorAll('select');
  $("body").on("input", ".saveC", function () {
    var name = $(this).attr('name');
    var id = $(this).attr('id');
    sessionStorage.setItem(name, id);
  });

  $("body").on("input", ".saveS", function () {
    var id = $(this).attr('id');
    sessionStorage.setItem(id, $(this).val());
  });

  for (i=0; i<inputElements.length; i++) {
   (function(inputElements) {
     var id = inputElements.getAttribute('id');
     if(sessionStorage.getItem(id)){
       inputElements.value = sessionStorage.getItem(id); // обязательно наличие у элементов id
     }
   })(inputElements[i]);
  }

  for (i=0; i<radioElements.length; i++) {
   (function(radioElements) {
     var name = radioElements.getAttribute('name');
     if(sessionStorage.getItem(name)){
       var id = sessionStorage.getItem(name);
       if(radioElements.id == id){
         radioElements.checked = true;
       }else{
         radioElements.checked = false;
       }

     }
   })(radioElements[i]);
  }
</script>
</body>
</html>
