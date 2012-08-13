<?php			//Пытается залогинить/зарегистрировать пользователя
				//если пользователь залогинен или его удалось залогинить, возвращает логин и количество кодов пользователя
	require_once 'db_conf.php';	
	require_once 'register.php';	
	
	if(isset($_GET['login']))
	{
		$reg_user = new reg($_GET['login']);
		$rg = $reg_user->register();
		if($rg == 1 || $rg == reg::ALREADY_EXIST)
		{			
			$rg = $reg_user->get_codes();
			$ret = array();
			$ret['login'] = $_GET['login'];
			$ret['codeCount'] = $reg_user->codeCount;
			for($i = 0; $i < $reg_user->codeCount; $i++)
				$ret['code'.$i] = $reg_user->currentCodes['code'.$i];
			$ret['banned'] = '0';
			$ret['blocked'] = '0';
			$ret['status'] = $reg_user->get_status();
			echo json_encode($ret);
		}
		elseif($rg == reg::USER_BANNED)
		{
			$ret = array('banned' => 'login', 'blocked' => '0');
			echo json_encode($ret);
		}
		elseif($rg == reg::IP_BANNED)
		{
			$ret = array('banned' => 'ip', 'blocked' => '0');
			echo json_encode($ret);
		}
	}
?>