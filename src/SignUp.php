<?php
include ("basic.php");
?>
  
<div class="container">
<form action="InsertUser.php" method="post">

    <div class="form-group">
    <label for="fname">First Name:</label>
    <input type="text" class="form-control" id="fname" name="fname" required>
  </div>
  
  <div class="form-group">
    <label for="lname">Last Name:</label>
    <input type="text" class="form-control" id="lname" name="lname" required>
  </div>
	
	<div class="form-group">
    <label for="usernameu">Username:</label>
    <input type="text" class="form-control" id="usernameu" name="usernameu" required>
  </div>
 
  
  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[#$*&@!%^])(?=.*[A-Z]).{8,}" title="Must contain at least one uppercase letter, one number and one special character (#$*&@), and at least 8 or more characters" id="pwd" name="pwd" required>
  </div>
  
 <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  
  
  <button type="submit" class="btn btn-default">Submit</button>
</form>
	
</div>

</body>
</html>