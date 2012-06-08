<?php
	//$_GET['login'] - Ид вк; $_GET['ava'] - фото; $_GET['logo'] - номер варианта логотипа; $_GET['del'] - удалить временный файл;
	//{'login':'vk_id', 'pic':'link_to_new_avatar'}
	if(isset($_POST['del']) && isset($_POST['login']))
	{
		unlink("temp_user_avatar_".$_POST['login'].".jpg");
	}
	elseif(isset($_POST['login']) && isset($_POST['ava']) && isset($_POST['logo']))
	{
		$src = @ImageCreateFromJPEG($_POST['ava']);
		switch($_POST['logo'])
		{
			case 1:
				$logo = @ImageCreateFromPNG("logo1.png");	
			break;
			case 2:
				$logo = @ImageCreateFromPNG("logo2.png");	
			break;
			case 3:
				$logo = @ImageCreateFromPNG("logo3.png");	
			break;
		}
		ImageAlphaBlending($logo, true);
		if($src && $logo)
		{
			$dx = ImageSX($src) - ImageSX($logo);
			$dy = ImageSY($src) - ImageSY($logo);
			ImageCopy($src, $logo, $dx, $dy, 0, 0, ImageSX($logo), ImageSY($logo));
			ImageJPEG($src, "temp_user_avatar_".$_POST['login'].".jpg");
			ImageDestroy($src);
			ImageDestroy($logo);
			echo json_encode(array('login' => $_POST['login'], 'pic' => "temp_user_avatar_".$_POST['login'].".jpg"));
		}
	}
	
	
?>