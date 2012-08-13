<?php
	if(isset($_POST['vkLink']))
	{
		require_once '../db_conf.php';
		require_once '../register.php';
		//Получаем uid по ссылке, либо помощью парсинга страницы пользователя по регулярке
			
		//Парсим
		$matches = array();
		if (preg_match('/http:\/\/vk.com\/id[0-9]+/', $_POST['vkLink'], $matches) == 1)
		{
			$matches = array();
			preg_match('/[0-9]+/', $_POST['vkLink'], $matches);
		}
		else
		{
			//Получаем страницу пользователя
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $_POST['vkLink']);
	        curl_setopt($ch, CURLOPT_HEADER, false);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $result = curl_exec($ch);
	        curl_close($ch);
			preg_match('/(<a)\s(id="profile_photo_link")\s(href="\/photo)[0-9]+(_)/', $result, $matches);
		}
        $result = array();
        preg_match('/[0-9]+/', $matches[0], $result);
        $result = substr($result[0], 0);	//uid
       
        // Регистрируем пользователя и ставим заданный статус
        $reg_user = new reg($result);
		$rg = $reg_user->register();
		if($rg == 1 || $rg == reg::ALREADY_EXIST)
		{
			$rg = $reg_user->get_codes();
			if ($reg_user->codeCount < 5)
			{
				$countMissed = 5 - $reg_user->codeCount;

				for ($countMissed; $countMissed > 0; $countMissed--)
				{
					$currField = 'code'.strval((5-$countMissed));
					$query = sprintf("UPDATE pr_users SET %s = '11111111' WHERE vk_id = '%s'", $currField, $result);
					$res = mysql_query($query);
				}
				$query = sprintf("UPDATE pr_users SET codes_num = '5' WHERE vk_id = '%s'", $result);
				$res = mysql_query($query);
			}
			$reg_user->set_status($_POST['status']);
			
		}
	}
	header('Location: http://legenda1795.ru/vkapps/addUsr/');






?>