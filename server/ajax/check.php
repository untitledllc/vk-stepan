<?php
	class Checker	//класс, который проверяет введённые пользователем коды и добавляет их пользователю
	{
		const USER_BANNED = -3;
		const IP_BANNED = -4;
		function __construct($vkId_, $codeArr_)
		{
			$this->vkId = $vkId_;
			$this->codeArr = $codeArr_;
			$this->parsedCodes = array();
			$this->badCodes = array();
		}
		function checkUser()
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
			$query = sprintf("SELECT * FROM pr_banned WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0)
			{
				return self::USER_BANNED;
			}
			return 1;
		}
		function parseCodes()	//разбирает строку с кодами на массив, сразу удаляя кривые коды 
		{
			$this->parsedCodes = array();
			$temp = "";
			for($i = 0; $i < strlen($this->codeArr); $i++)
			{
				if(($this->codeArr[$i] >= '0' && $this->codeArr[$i] <= '9' ) || (strtolower($this->codeArr[$i]) >= 'a' && strtolower($this->codeArr[$i]) <= 'z'))
				{
					$temp .= $this->codeArr[$i];
				}
				else
				{
		 			if(strlen($temp) > 0)
					{
						if(strlen($temp) != 8)
							$this->badCodes[] = $temp;
						else
							$this->parsedCodes[] = $temp;
						$temp = "";
					}
					else
					{
						$temp = "";
					}
				}					
			}
			if(strlen($temp) > 0)
			{
				if(strlen($temp) != 8)					//откидываем коды неправильной длины
					$this->badCodes[] = $temp;				
				else
					$this->parsedCodes[] = $temp;
				unset($temp);
			}
		}
		function checkCodes()			//сверяет коды по базе
		{
			if(count($this->parsedCodes) != 0)
			{
				foreach ($this->parsedCodes as $k => $c)
				{
					
					$query = sprintf("SELECT * FROM pr_codes WHERE code = '%s' AND status = '0'", mysql_real_escape_string($c));
					$res = mysql_query($query);
					if(mysql_num_rows($res) <= 0)	//если кода нет - записываем его в массив плохих кодов
					{
						$this->badCodes[] = $c;
						unset($this->parsedCodes[$k]);
					}
				}
			}
			$this->checked = true;
		}
		function addCodes()			//добавляет коды юзеру
		{
			if($this->checked)
			{
				
				$query = sprintf("SELECT codes_num FROM pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);
				$from_field;		//в какое поле вставлять очередной код
				if(mysql_num_rows($res) > 0)
				{
					$res = mysql_fetch_assoc($res);
					if($res['codes_num'] > 5)
						return -2; 
					$from_field = $res['codes_num'];
				}
				foreach($this->parsedCodes as $c)
				{
					$current_field = "code".$from_field;
						$from_field++;					
					$query = sprintf("UPDATE pr_users SET %s = '%s', codes_num = codes_num + 1 WHERE vk_id = '%s'", $current_field, mysql_real_escape_string($c), mysql_real_escape_string($this->vkId));
					$res = mysql_query($query);
					if($res)
					{
						$query = sprintf("UPDATE pr_codes SET status = '1' WHERE code = '%s'", mysql_real_escape_string($c));		//если код добавился пользователю, то меняем статус этого кода
						mysql_query($query);
					}
					else
						$this->badCodes[] = $c;
				}
			}
		}		
		protected $checked = false;
		public $codeArr;			//строка с кодами, (через пробел или запятую или \n и т.д.)
		public $badCodes;			//коды не прошедшие проверку
		public $parsedCodes;		//массив хороших, годных кодов
		protected $vkId;			//ид юзера в вк
	


	}
?>