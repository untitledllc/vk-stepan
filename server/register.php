<?php
	class reg	//класс, добавляющий пользователя в БД
	{
		const ALREADY_EXIST = -1;
		const ADDING_FAILED = -2;
		function __construct($vkId_)
		{
			$this->vkId = $vkId_;
			$this->codeCount = 0;
		}
		function register()
		{
			$query = sprintf("SELECT * FROM razin_promo.pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);		//проверяем нет ли этого пользователя
			if(mysql_num_rows($res) > 0)	
			{
				$query = sprintf("SELECT codes_num FROM razin_promo.pr_users WHERE vk_id = '%s'", mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);
				if(mysql_num_rows($res) > 0)
				{
					$rec = mysql_fetch_assoc($res);
					$this->codeCount = (int)($rec['codes_num']);
				}
				return self::ALREADY_EXIST;
			}
			
			$query = sprintf("INSERT INTO razin_promo.pr_users (vk_id, codes, codes_num) VALUES ('%s', '', 0)", mysql_real_escape_string($this->vkId));
			$res = mysql_query($query);		//добавляем.
			if(!$res)
				return self::ADDING_FAILED;
			else
				return 1;
			
		}
		protected $vkId;
		public $codeCount;
	}
?>