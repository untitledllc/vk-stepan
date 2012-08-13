<?php
	class reg	//класс, добавляющий пользователя в БД
	{
		const ALREADY_EXIST = -1;
		const ADDING_FAILED = -2;
		const USER_BANNED = -3;
		const IP_BANNED = -4;
		function __construct($vkId_)
		{
			$this->vkId = $vkId_;
			$this->codeCount = 0;
			$this->currentCodes = array();
		}
		function register()
		{
			$query = sprintf("SELECT * FROM pr_ip_banned WHERE ip = INET_ATON('%s')", $_SERVER['REMOTE_ADDR']);
			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0)
			{
				$res = mysql_fetch_assoc($res);
				if(time() - $res['time'] > 86400)
				{
					$query = sprintf("DELETE FROM pr_ip_banned WHERE ip = INET_ATON('%s')", $_SERVER['REMOTE_ADDR']);
					$res = mysql_query($query);
				}
				else
					return self::IP_BANNED;
			}
			$query = sprintf("SELECT * FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);		//проверяем нет ли этого пользователя
			if(mysql_num_rows($res) > 0)	
			{
				$query = sprintf("SELECT * FROM pr_banned WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);
				if(mysql_num_rows($res) > 0)
				{
					return self::USER_BANNED;
				}
				$query = sprintf("SELECT codes_num FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);
				if(mysql_num_rows($res) > 0)
				{
					$rec = mysql_fetch_assoc($res);
					$this->codeCount = (int)($rec['codes_num']);
				}
				return self::ALREADY_EXIST;
			}
			
			$query = sprintf("INSERT INTO pr_users (vk_id, codes_num) VALUES ('%s', 0)", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);		//добавляем.
			if(!$res)
				return self::ADDING_FAILED;
			else
				return 1;
			
		}
		function get_codes()
		{
			$query = sprintf("SELECT * FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0)
			{
				$res = mysql_fetch_assoc($res);
				for($ind = 0; $ind < 5; $ind++)
				{
					$code_ind = "code".$ind;
					if($res[$code_ind] == '0')
						break;
					$this->currentCodes[$code_ind] = $res[$code_ind];
				}
				return 0;
			}
			else
				return -1;
		}
		function set_status($st)
		{
			//Проверим, есть ли пользователь в базе  "statuses"
			$query = sprintf("SELECT * FROM pr_statuses WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);
			$st = intval($st);
			if($st < 1 || $st > 3)
				return -1;
			if(mysql_num_rows($res) > 0)
			{		
				$query = sprintf("UPDATE pr_statuses SET  status = %d WHERE vk_id = '%s'", $st, mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);
			}
			else
			{
				$query = sprintf("INSERT INTO pr_statuses (vk_id, status) VALUES ('%s', %d)", mysql_real_escape_string($this->vkId), $st);
				$res = mysql_query($query);
			}
		}
		function get_status()
		{
			$query = sprintf("SELECT status FROM pr_statuses WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0)
			{
				$res = mysql_fetch_assoc($res);
				return intval($res['status']);
			}
			else
				return 1;	//По умолчанию все юнги
		}
		protected $vkId;
		public $codeCount;
		public $currentCodes;
	}
?>