<?php


function form_login(){
	echo <<<PL
	<h2>Podaj swoje dane:</h2>
	<form class="col-md-6" method='post'> 
  <div class="form-group">
    <label for="exampleInputEmail1">userId</label>
    <input type="text" class="form-control" name="userID" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="userId">
    <small id="emailHelp" class="form-text text-muted"  >Wprowadź swóje id użytkownika</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">loginToken</label>
    <input type="text" class="form-control" name="loginToken" id="exampleInputPassword1" placeholder="loginToken">
  </div>
 
  <button type="submit" class="btn btn-primary">Zapisz</button>
  </form>
PL;
}
function bootstrap(){
	echo <<<PL
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
PL;
}

?>