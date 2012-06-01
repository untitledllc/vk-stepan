<?php			//При наличии $_GET['check'] проверяет залогинен ли пользователь, иначе - пытается залогинить/зарегистрировать пользователя
				//если пользователь залогинен или его удалось залогинить, возвращает логин и количество кодов пользователя
	require_once 'db_conf.php';	
	require_once 'register.php';
	if(isset($_GET['check']))
	{
		if(isset($_SESSION['vkId']))
		{
			$reg_user = new reg($_SESSION['vkId']);
			$rg = $reg_user->register();
			if($rg == reg::ALREADY_EXIST)
			{				
				echo json_encode(array("login" => $_SESSION['vkId'], "codeCount" => $reg_user->codeCount));
			}
		}
	}
	else
	{
		$reg_user = new reg($_GET['login']);
		$rg = $reg_user->register();
		if($rg == 1 || $rg == reg::ALREADY_EXIST)
		{
			$_SESSION['vkId'] = $_GET['login'];
			echo json_encode(array("login" => $_SESSION['vkId'], "codeCount" => $reg_user->codeCount));
		}
	}
?>