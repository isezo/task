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
