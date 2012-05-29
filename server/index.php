<?php	//////////файлик дл€ отладки и тестировани€
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
		if(!isset($_POST['vkId']))		//при первом входе предлагаем зарегистрироватьс€ в базе
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
				include 'code_add_form.php';
			}
			elseif($rg == reg::ALREADY_EXIST)
			{
				unset($_POST['vkId']);
				echo "Ёто им€ зан€то";
				include 'reg_form.php';				
			}
			elseif($rg == reg::ADDING_FAILED)
			{
				unset($_POST['vkId']);
				echo "¬о врем€ регистрации произошла ошибка";
				include 'reg_form.php';		
			}
		}
	}
	else
	{
		if(!isset($_POST['codes']))		//если пользователь зареган, предлагаем вводить коды
		{
			include 'code_add_form.php';
		}
		else
		{
			$ch = new Checker($_SESSION['vkId'], $_POST['codes']);		//добавл€ем коды
			$ch->parseCodes();
			$ch->checkCodes();
			$ch->addCodes();
			if(count($ch->badCodes) > 0)
			{
				echo 'Ќе удалось зарегать следующие номера: <br>';
				foreach($ch->badCodes as $c)
				{					
					echo $c.'<br>';
				}
			}
			include 'code_add_form.php';
		}
	}
		
?>
	
</body>
</html>