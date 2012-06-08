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
			echo json_encode($ret);
		}
	}
?>