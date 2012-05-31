<?php	//////////файлик для отладки и тестирования
	session_start();
	require_once 'db_conf.php';	
	require_once 'register.php';
	require_once 'check.php';	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Test Razin</title>
</head>
<body>

<?php 
	if(!isset($_SESSION['vkId']))
	{
		if(!isset($_POST['vkId']))		//при первом входе предлагаем зарегистрироваться в базе
		{
			include 'reg_form.php';
		}
		else
		{
			$reg_user = new reg($_POST['vkId']);
			$rg = $reg_user->register();			
			if($rg == 1)
			{
				$_SESSION['vkId'] = $_POST['vkId'];
				unset($_POST['vkId']);
				echo "Вы активировали ".$reg_user->codeCount." кодов";
				include 'code_add_form.php';
			}
			elseif($rg == reg::ALREADY_EXIST)
			{				
				$_SESSION['vkId'] = $_POST['vkId'];
				unset($_POST['vkId']);
				echo "Вы активировали ".$reg_user->codeCount." кодов";
				include 'code_add_form.php';
			}
			elseif($rg == reg::ADDING_FAILED)
			{
				unset($_POST['vkId']);
				echo "Во время регистрации произошла ошибка";
				include 'reg_form.php';		
			}
		}
	}
	else
	{
		$reg_user = new reg($_SESSION['vkId']);
		$rg = $reg_user->register();
		echo $_SESSION['vkId']."<br>";
		if(!isset($_POST['codes']))		//если пользователь зареган, предлагаем вводить коды
		{
			echo "Вы активировали ".$reg_user->codeCount." кодов";
			include 'code_add_form.php';
		}
		else
		{
			$ch = new Checker($_SESSION['vkId'], $_POST['codes']);		//добавляем коды
			$ch->parseCodes();
			$ch->checkCodes();
			$ch->addCodes();
			if(count($ch->badCodes) > 0)
			{
				echo 'Не удалось зарегать следующие номера: <br>';
				foreach($ch->badCodes as $c)
				{					
					echo $c.'<br>';
				}
			}
			echo "Вы активировали ".$reg_user->codeCount." кодов";
			include 'code_add_form.php';
		}
	}
		
?>

</body>
</html>