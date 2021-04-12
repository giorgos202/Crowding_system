<?php
include ("basic.php");
?>


<div class="container">

<form method=post>
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" name="pwd" required>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
	<br>
	<a href='AdminLogin.php'>Connect as Admin </a>
</div>

</body>
</html>