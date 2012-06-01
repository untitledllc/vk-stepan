<?php		//проверяет и добавляет ajax'ом коды и возвращает плохие в json {'elem0':'bad_cod1', 'elem1':'bad_code2', ...}
	require_once 'db_conf.php';
	require_once 'check.php';
	
	$ch = new Checker($_GET['login'], $_GET['codes']);		//добавляем коды
	$ch->parseCodes();
	$ch->checkCodes();
	$ch->addCodes();
	if(count($ch->badCodes) > 0)
	{
		$result = array();
		foreach($ch->badCodes as $k => $bc)
			$result['elem'.$k] = $bc;
		echo json_encode($result);
	}
?>