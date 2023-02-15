<?php
session_start();

	print_r($_FILES);
	$pic=$_FILES['pic'];
	if($pic['type']!="image/jpeg" && $pic['type']!="image/png")
	
		die("<script> alert('Csak jpg vagy png típusú fájlokat tölthetsz fel!')</script>");
	
	if($pic['type']=="image/jpeg") $tipus=".jpg";
	if($pic['type']=="image/png") $tipus=".png";
	
	$ujnev=date("ymdHis_").$_SESSION['userid']."_".velszo(8).$tipus;
	move_uploaded_file($pic['tmp_name'],"./upload/".$ujnev);

		
		
	
	
	function velszo( $h )
	{
		$szo="";
		$k="abcdefghijklmnopqrstuvwxyz";
		for($i=1;$i<=$h;$i++) $szo.=substr($k,rand(0,strlen($k)-1),1);
		return $szo;
	}
	
	
	/*Képméretezés*/
	$forrasimg= imagecreatefromjpeg("./upload/".$ujnev);
	$szeles=imagesx($forrasimg);
	$magas=imagesy($forrasimg);
	if($szeles>$magas)
	{
		$kisszel=240;
		$kismag=($magas/$szeles*240).round;
	}else{
		$kisszel=($szeles/$magas*240).round;
		$kismag=240;
	}
	$dstimg=imagecreatetruecolor($kisszel,$kismag);
	imagecopyresampled(
	$dstimg,	$forrasimg,	0,	0,	0,	0,	$kisszel,	$kismag,	$szeles,	$magas	);
	
	imagejpeg($dstimg,"./s/".$ujnev);
	imagedestroy($forrasimg);
	imagedestroy($dstimg);
	/*Képméretezés vége*/
	
	include("kapcso.php");
		mysqli_query($dab,"
		INSERT INTO `kep` 	(`kid`,			`knev`, 			`keredetinev`, 			`udatum`, 		`kuserid`, 							`kprofkep`, 				`kmeret`, 			`kstat`)
		VALUES 				(NULL,			'$ujnev', 			'$pic[name]', 			'NOW()', 		'$_SESSION[userid]', 						'',					 			
		'$pic[size]', 			'F')
		");
		
	print"<script>alert('Sikeres kép feltöltés!')
	location.href='./?p=adatlap'</script>";
	mysqli_close($dab);
		
?>