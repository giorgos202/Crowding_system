<?php
include ("basic.php");
?>
  
<div class="container">
<h2> Connect as Admin </h2>
<form method=post>
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" name="pwd" required>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
	
	
</div>

</body>
</html>