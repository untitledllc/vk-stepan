<?php		//проверяет и добавляет ajax'ом коды и возвращает плохие в json {'elem0':'bad_cod1', 'elem1':'bad_code2', ...}
	session_start();
	require_once 'db_conf.php';
	require_once 'check.php';
	
	$ch = new Checker($_GET['login'], $_GET['codes']);		//добавляем коды
	
	$ret = $ch->checkUser();
	
	if($ret == Checker::USER_BANNED)
	{
		$ret = array('banned' => 'login');
		echo json_encode($ret);
	}
	elseif($ret == Checker::IP_BANNED)
	{
		$ret = array('banned' => 'ip');
		echo json_encode($ret);
	}
	else
	{
		if(isset($_SESSION['blocked']))
		{
			if(time() - $_SESSION['blocked'] <= 60)
			{
				echo json_encode(array('blocked' => 'blocked'));
			}
			else
			{
				unset ($_SESSION['blocked']);
			}
		}
		if(!isset($_SESSION['blocked']))
		{
			$ch->parseCodes();
			$ch->checkCodes();
			if(count($ch->badCodes) > 0)
			{
				for($i = 0; $i < 100; $i++)		//for permanent ban
				{
					if(!isset($_SESSION['forBan'.$i]))
					{
						$_SESSION['forBan'.$i] = time();
						break;
					}
				}
				$banned = TRUE;
				for($i = 0; $i < 100; $i++)
				{
					if(isset($_SESSION['forBan'.$i]))
					{
						if(time() - $_SESSION['forBan'.$i] > 86400)
						{
							$banned = FALSE;
							unset($_SESSION['forBan'.$i]);
						}
					}
					else
					{
						$banned = FALSE;
					}
				}
				if($banned)
				{
					$query = sprintf("INSERT INTO pr_banned (vk_id) VALUES ('%s')", mysql_real_escape_string($_GET['login']));
					$res = mysql_query($query);
					$query = sprintf("INSERT INTO pr_ip_banned (ip, time) VALUES (INET_ATON('%s'), '%d')", $_SERVER['REMOTE_ADDR'], time());
					$res = mysql_query($query);
					$ret = array('banned' => 'login');
					echo json_encode($ret);
				}
				else
				{
					for($i = 0; $i < 5; $i++)		//for 1 min block
					{
						if(!isset($_SESSION['block'.$i]))
						{
							$_SESSION['block'.$i] = time();
							break;
						}
					}
					$blocked = TRUE;
					for($i = 0; $i < 5; $i++)
					{
						if(isset($_SESSION['block'.$i]))
						{
							if(time() - $_SESSION['block'.$i] > 60)
							{
								$blocked = FALSE;
								unset($_SESSION['block'.$i]);
							}
						}
						else
						{
							$blocked = FALSE;
						}
					}
					if($blocked)
					{
						$_SESSION['blocked'] = time();
						echo json_encode(array('blocked' => 'blocked'));
					}
					else
					{
						if(isset($_SESSION['blocked']))
							unset($_SESSION['blocked']);
						$result = array();
						foreach($ch->badCodes as $k => $bc) 
							$result['elem'.$k] = $bc;
						echo json_encode($result);
					}
				}
			}
			else
			{
				$ch->addCodes();
				if(count($ch->badCodes) > 0)
				{
						$result = array();
						foreach($ch->badCodes as $k => $bc) 
							$result['elem'.$k] = $bc;
						echo json_encode($result);
				}
				else
				{
					if(count($ch->parsedCodes) == 0)
						echo json_encode(array('elem0' => $_GET['codes']));
				}
			}
		}
	}
?>