function post(){
  var un = $("#username").val();
  var pw = $("#password").val();

  $.post('InsertUser.php',{username:un, password:pw}, function(data){
    $("#alerts").html(data);
  });
}
