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
			echo json_encode(array("login" => $_GET['login'], "codeCount" => $reg_user->codeCount));
		}
	}
?>