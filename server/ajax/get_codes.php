<?php			
	require_once 'db_conf.php';	
	require_once 'register.php';
	if(isset($_GET['login']))
	{		
		$reg_user = new reg($_GET['login']);
		$rg = $reg_user->get_codes();
		if($rg != -1)
		{				
			echo json_encode($reg_user->currentCodes);
		}
	
	}
?>