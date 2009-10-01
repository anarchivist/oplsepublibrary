<?php
if(!isset($_SESSION)){
	session_start();
}
?>
<html>
<body>
  <form enctype="multipart/form-data" action="upload.php" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
    Choose a file to upload: <input name="uploaded_file" type="file" />
    <input type="submit" value="Upload" />
  </form>
</body>
</html>