<?php
	class Checker	//�����, ������� ��������� �������� ������������� ���� � ��������� �� ������������
	{
		function __construct($vkId_, $codeArr_)
		{
			$this->vkId = $vkId_;
			$this->codeArr = $codeArr_;
			$this->parsedCodes = array();
			$this->badCodes = array();
		}
		function parseCodes()	//��������� ������ � ������ �� ������, ����� ������ ������ ����
		{
			$sep = array(',', '.', ' ', '\n', ';');			//��������� ����������� ����� (����� ���������)
			$this->parsedCodes = array();			
			$temp = "";			
			for($i = 0; $i < strlen($this->codeArr); $i++)
			{
				if(!in_array($this->codeArr[$i], $sep))
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
				}					
			}
			if(strlen($temp) > 0)
			{
				if(strlen($temp) != 8)					//���������� ���� ������������ �����
					$this->badCodes[] = $temp;				
				else
					$this->parsedCodes[] = $temp;
				unset($temp);
			}	
		}
		function checkCodes()			//������� ���� �� ����
		{
			if(count($this->parsedCodes) != 0)
			{
				foreach ($this->parsedCodes as $k => $c)
				{
					$query = sprintf("SELECT * FROM razin_promo.pr_codes WHERE code = '%s'", mysql_real_escape_string($c));
					$res = mysql_query($query);
					if(mysql_num_rows($res) <= 0)	//���� ���� ��� - ���������� ��� � ������ ������ �����
					{
						$this->badCodes[] = $c;
						unset($this->parsedCodes[$k]);
					}
				}
			}
			$this->checked = true;
		}
		function addCodes()			//��������� ���� �����
		{
			if($this->checked)
			{
				$temp = "";
				$t = 0;
				for($i = 0; $i < count($this->parsedCodes); $i++)
				{
					$query = sprintf("DELETE FROM razin_promo.pr_codes WHERE code = '%s'", mysql_real_escape_string($this->parsedCodes[$i]));
					$res = mysql_query($query);			//������� ��� �� ���� �����, �.�. ������ �� ��������������
					if($res)
					{
						$temp .= $this->parsedCodes[$i] . ' ';		//���������� ��� ���� ����� � ������, ������� ����� ������� � ����� ����� � ��
						$t++;
					}
					else
						$this->badCodes[] = $this->parsedCodes[$i];
				}
				$query = sprintf("UPDATE razin_promo.pr_users SET codes = CONCAT(codes, '%s'), codes_num = codes_num + '%d' WHERE vk_id = '%s'", mysql_real_escape_string($temp), $t, mysql_real_escape_string($this->vkId));
				$res = mysql_query($query);		//����������� ���������� ����� �������� ������������, � ���������� ��� �������� �� ����
			}
		}		
		protected $checked = false;
		public $codeArr;			//������ � ������, (����� ������ ��� ������� ��� \n � �.�.)
		public $badCodes;			//���� �� ��������� ��������
		protected $parsedCodes;		//������ �������, ������ �����
		protected $vkId;			//�� ����� � ��
	


	}
?>