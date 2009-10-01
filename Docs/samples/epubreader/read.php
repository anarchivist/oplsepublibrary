<?php
	require_once('ebookRead.php');
	require_once('ebookData.php');

	if(!isset($_SESSION)){
		session_start();
	}
	$ebookData = unserialize($_SESSION['esd']);
	$ebook = new ebookRead($ebookData);
	$spineInfo = $ebook->getSpine();

	function buildToc($ebook){
		$found = false;
		$spineInfo = $ebook->getSpine();
		for($x = 0;$x < count($spineInfo);$x+=1){		
			$content = $ebook->getContentById($spineInfo[$x]);		
			if($found)
				break;			
			for($y = 0;$y < count($spineInfo);$y+=1){
				$manItem = $ebook->getManifestById($spineInfo[$y]);				
				if(preg_match("/".$manItem->href."/", $content)){
					$found = true;
					break;				
				}					
			}		
		}
		if(!$found){
			echo "<h2>Table of Contents</h2><br />";
			$toc = $ebook->getTOC();
			for($x = 0;$x < count($spineInfo);$x+=1){
				if(isset($toc)){
					$cToc = $toc[$x];
					$tag = substr($cToc->fileName, 0, strrpos($cToc->fileName, '.'));	
					echo "<a href=\"#".$tag."\" >".$cToc->name."</a>\n<br />\n";
				}else{
					$manItem = $ebook->getManifestById($spineInfo[$x]);
					$tag = substr($manItem->href, 0,strrpos($manItem->href, '.'));		
					echo "<a href=\"#".$tag."\" >".$manItem->id."</a>\n<br />\n";
				}
			}
			echo "<br />";
		}
	}	
	
	function editToc($content, $ebook){
		$spineInfo = $ebook->getSpine();	
		for($x = 0;$x < count($spineInfo);$x+=1){
			$manItem = $ebook->getManifestById($spineInfo[$x]);
			$tag = substr($manItem->href, 0,strrpos($manItem->href, '.'));		
			$content = str_replace($manItem->href, "#".$tag, $content);
		}
		return $content;
	}
?>
<HTML>
<HEAD>
	<TITLE>OPL's Open eBook Reader - <?php echo $ebook->getDcTitle();?></TITLE>
	<meta name="author" content="Jacob Weigand">
</HEAD>
<BODY>
	<?php
		echo "<P ALIGN=LEFT STYLE=\"margin-bottom: 0in\">\n";
			echo buildToc($ebook);
			for($x = 0;$x < count($spineInfo);$x+=1){		
				$manItem = $ebook->getManifestById($spineInfo[$x]);				
				echo "<div id=\"section\">";
				echo "<a name=".substr($manItem->href, 0,strrpos($manItem->href, '.'))." />\n";
				echo editToc($content = $ebook->getContentById($spineInfo[$x]), $ebook);
				echo "</div>\n";
			}
		echo "</P><br />\n";
		if(is_file($_SESSION['efile']))		
			unlink($_SESSION['efile']);
	?>
	</BODY>
</HTML>