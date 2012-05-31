<?php
	class Checker	//класс, который проверяет введённые пользователем коды и добавляет их пользователю
	{
		function __construct($vkId_, $codeArr_)
		{
			$this->vkId = $vkId_;
			$this->codeArr = $codeArr_;
			$this->parsedCodes = array();
			$this->badCodes = array();
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
					$query = sprintf("SELECT * FROM razin_promo.pr_codes WHERE code = '%s'", mysql_real_escape_string($c));
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
				$temp = "";
				$t = 0;
				foreach($this->parsedCodes as $c)
				{
					$query = sprintf("DELETE FROM razin_promo.pr_codes WHERE code = '%s'", mysql_real_escape_string($c));
					$res = mysql_query($query);			//удаляем код из базы кодов, т.к. теперь он активированный
					if($res)
					{
						$temp .= $c . ' ';		//записываем эти коды снова в строку, которую потом допишем к кодам юзера в БД
						$t++;
					}
					else
						$this->badCodes[] = $c;
				}
				$query = sprintf("UPDATE razin_promo.pr_users SET codes = CONCAT(codes, '%s'), codes_num = codes_num + '%d' WHERE vk_id = '%s'", mysql_real_escape_string($temp), $t, mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);		//увеличиваем количество кодов текущего пользователя, и дописываем ему введённые им коды
			}
		}		
		protected $checked = false;
		public $codeArr;			//строка с кодами, (через пробел или запятую или \n и т.д.)
		public $badCodes;			//коды не прошедшие проверку
		protected $parsedCodes;		//массив хороших, годных кодов
		protected $vkId;			//ид юзера в вк
	


	}
?>