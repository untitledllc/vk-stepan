 <?php
    //$_POST['login'] - Ид вк; $_POST['ava'] - фото; $_POST['logo'] - номер варианта логотипа; $_POST['upload_url'] - ссылка для загрузки изображений;
	//{'login':'vk_id', 'pic':'link_to_new_avatar'}
    if (isset($_POST["upload_url"])) {
        $upload_url = $_POST["upload_url"];
		$src = @ImageCreateFromJPEG($_POST['ava']);
		
		for($i = 0; $i < 200; $i++)
		{
			if($i == $_POST['logo'])
			{
				$i++;
				$logoFName = 'logo'.$i.'.png';
				$logo = ImageCreateFromPNG($logoFName);
				break;
			}
		}
		
		ImageAlphaBlending($logo, true);
		$tmp_name;
		if($src && $logo)
		{
			if(ImageSX($logo) > (ImageSX($src) / 2))
			{
				$newLogoSX = ImageSX($src) / 2;
				$scale = $newLogoSX / ImageSX($logo);
				$newLogoSY = ImageSY($logo) * $scale;
				$newLogo = ImageCreateTrueColor($newLogoSX, $newLogoSY);
				ImageAlphaBlending($newLogo, false);
				ImageSaveAlpha($newLogo, true);
				$transparent = imagecolorallocatealpha($newLogo, 255, 255, 255, 127);
				ImageFilledRectangle($newLogo, 0, 0, $newLogoSX, $newLogoSY, $transparent);
				ImageCopyResampled($newLogo, $logo, 0, 0, 0, 0, $newLogoSX, $newLogoSY, ImageSX($logo), ImageSY($logo));
				$logo = $newLogo;
			}
			$dx = ImageSX($src) - ImageSX($logo);
			$dy = ImageSY($src) - ImageSY($logo);
			ImageCopy($src, $logo, $dx, $dy, 0, 0, ImageSX($logo), ImageSY($logo));
			$tmp_name = "temp_user_avatar_".$_POST['login'].".jpg";
			ImageJPEG($src, $tmp_name, 100);
			ImageDestroy($src);
			ImageDestroy($logo);
		}
		
		
        $post_params['photo'] = '@'.$tmp_name;     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        $result = curl_exec($ch);
        curl_close($ch);
        
		unlink($tmp_name);
		
        echo $result;
       
     
    }
?>