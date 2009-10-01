<?php
	require_once('ebookRead.php');
	require_once('ebookData.php');


	if(!isset($_SESSION)){
		session_start();
	}
	//Check that we have a file
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  //Check if the file is a zip file and it's size is less than 350Kb
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if($ext == "epub"){
	    //Determine the path to which we want to save this file
	      $newname = dirname(__FILE__).'/upload/'.$filename;
	      //Check if the file with the same name is already exists on the server
	      if (!file_exists($newname)) {
	        //Attempt to move the uploaded file to it's new place
	        if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
	           //echo "The uploaded file has been saved at: $newname \n <br />";
	        } else {
	           echo "Error: A problem occurred during file upload!";
	        }
	      } else {
	         echo "Error: File ".$_FILES["uploaded_file"]["name"]." already exists \n <br />";
	      }
	  } else {
	     echo "Error: Only .epub files are accepted for upload\n <br />";
	  }
	} else {
	 echo "Error: No file uploaded\n <br />";
	}
	
	//read our epub file
	$ebook = new ebookRead($newname);
	$_SESSION['esd'] = serialize($ebook->getEBookDataObject());
	$_SESSION['efile'] = $newname;
	
	function metadata($ebook){
		display("<b>Title:</b>", $ebook->getDcTitle());
		display("<b>Creator:</b>", $ebook->getDcCreator());
		display("<b>Description:</b>", $ebook->getDcDescription());
		display("<b>ISBN or ID:</b>", $ebook->getDcIdentifier());
		display("<b>Contributor(s):</b>", $ebook->getDcContributor());
		display("<b>Contributor(s) Role:</b>", $ebook->getDcContributor("Role"));
		display("<b>Language:</b>", $ebook->getDcLanguage());
		display("<b>Rights:</b>", $ebook->getDcRights());
		display("<b>Publisher</b>:", $ebook->getDcPublisher());
		display("<b>Subject:</b>", $ebook->getDcSubject());
		display("<b>Date:</b>", $ebook->getDcDate());
		display("<b>Type:</b>", $ebook->getDcType());
		display("<b>Format:</b>", $ebook->getDcFormat());
		display("<b>Sources:</b>", $ebook->getDcSource());
		display("<b>Relation:</b>", $ebook->getDcRelation());
		display("<b>Coverage:</b>", $ebook->getDcCoverage());
	}

	function display($title, $data){
	$info = "";
	if(is_array($data)){
		foreach($data as $element){
			if($info == "")
				$info = $element;
			else
				$info = $info.", ".$element;
		}
		$data = $info;
	}
	if($data != "")
		echo $title." ".$data."\n <br />";

	}
?>
<HTML>
<HEAD>
	<TITLE>OPL's Open eBook Reader - <?php echo $ebook->getDcTitle();?></TITLE>
	<meta name="author" content="Jacob Weigand">
</HEAD>
<BODY>
	<P ALIGN=CENTER STYLE="margin-bottom: 0in">Welcome</P>
	<br />
	<P ALIGN=CENTER STYLE="margin-bottom: 0in">Some stuff about the opl and the open ebook reader here.</P>
	<br />
	<P ALIGN=LEFT STYLE="margin-bottom: 0in"><?php metadata($ebook);?></P>
	<br />
	<P ALIGN=CENTER STYLE="margin-bottom: 0in"><A HREF="./<?php echo "read.php"; ?>" TARGET="_self">Start Reading <?php echo $ebook->getDcTitle();?></A></P>
</BODY>
</HTML>