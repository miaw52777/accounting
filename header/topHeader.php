<?
function menuList($selectPageName)
{
	$menuListArr = array();
	$menuListArr[0]['pagename'] = "Home";
	$menuListArr[0]['pageurl'] = "index.php";
	$menuListArr[1]['pagename'] = "帳戶管理";
	$menuListArr[1]['pageurl'] = "statistic.php";
	$menuListArr[2]['pagename'] = "統計圖";
	$menuListArr[2]['pageurl'] = "";
	$menuListArr[3]['pagename'] = "排程設定";
	$menuListArr[3]['pageurl'] = "";
	
	
	
	echo '<div class="scrollmenu"> <br>';
	for ($i = 0; $i < count($menuListArr); $i++)
	{
		if($selectPageName == $menuListArr[$i]['pagename'])
		{
			$isActive = "active";
		}
		else
		{
			$isActive = "";
		}
		echo '<a href="'.$menuListArr[$i]['pageurl'].'" class="'.$isActive.'" >'.$menuListArr[$i]['pagename'].'</a>';
	}
	
	echo '</div>  <br>';
}

?>