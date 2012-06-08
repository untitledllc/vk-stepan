<?php
	require_once '../db_conf.php';
	if(isset($_POST['vkId']))
	{
		$query = sprintf("SELECT * FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($_POST['vkId']));
		$res = mysql_query($query) or die(mysql_error());
		$res = mysql_fetch_assoc($res);
		$c = 0;
		while($c < 5)
		{
			$ind = 'code'.$c;
			$query = sprintf("UPDATE pr_codes SET status = 0 WHERE code = '%s'", mysql_real_escape_string($res[$ind]));
			mysql_query($query) or die(mysql_error());
			$query = sprintf("UPDATE pr_users SET %s = '0' WHERE vk_id = '%s'", mysql_real_escape_string($ind), mysql_real_escape_string($_POST['vkId']));
			mysql_query($query) or die(mysql_error());
			$c++;
		}
		//$query = sprintf("UPDATE pr_users SET codes_num = 0 WHERE vk_id = '%s'", mysql_real_escape_string($_POST['vkId']));
		$query = sprintf("DELETE FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($_POST['vkId']));
		mysql_query($query) or die(mysql_error()); 
	}
?>