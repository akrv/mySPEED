<?php 

class config
{
	function getconfig()
	{
		$xmlcnt["code"] = '1';
		
		$data = array('GC_LOGIN_REQUIRED','GC_REGISTRATION','GC_REMEMBER_PASSWORD','GC_RESET_PASSWORD');
		$login = GC_LOGIN_REQUIRED;$reg = GC_REGISTRATION;$res = GC_RESET_PASSWORD;$rem = GC_REMEMBER_PASSWORD;
		 
		$inc=0;
		foreach($data as $row)
		{
			$i=0;
			if($row == 'GC_LOGIN_REQUIRED')
			{
				$xmlcnt['config']['data'][$inc]['label'][$i]['name'] = $row;
				$xmlcnt['config']['data'][$inc]['label'][$i]['value'] = $login;
				$i++;
			}
			$j=0;
			if($row == 'GC_REGISTRATION')
			{
				$xmlcnt['config']['data'][$inc]['label'][$j]['name'] = $row;
				$xmlcnt['config']['data'][$inc]['label'][$j]['value'] = $reg;
				$j++;
			}
			$l=0;
			if($row == 'GC_REMEMBER_PASSWORD')
			{
				$xmlcnt['config']['data'][$inc]['label'][$l]['name'] = $row;
				$xmlcnt['config']['data'][$inc]['label'][$l]['value'] = $rem;
				$l++;
			}
			$k=0;
			if($row == 'GC_RESET_PASSWORD')
			{
				$xmlcnt['config']['data'][$inc]['label'][$k]['name'] = $row;
				$xmlcnt['config']['data'][$inc]['label'][$k]['value'] = $res;
				$k++;
			}
			$inc++;
		}
		return $xmlcnt;
	}
}
?>