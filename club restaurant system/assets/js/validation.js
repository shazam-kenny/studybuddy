function validateLogin(){
  var u = document.getElementById('username').value.trim();
  var p = document.getElementById('password').value;
  if(!u||!p){ alert('Enter username and password'); return false; }
  return true;
}
function validateEmployeeForm(){ return true; } // extend as needed