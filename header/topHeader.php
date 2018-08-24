<?
function printmenuList()
{
	$menuListArr = array();
	$menuListArr[0]['pagename'] = "Home";
	$menuListArr[0]['pageurl'] = "index.php";
	$menuListArr[1]['pagename'] = "帳戶管理";
	$menuListArr[1]['pageurl'] = "accountmanage.php";
	$menuListArr[2]['pagename'] = "統計圖";
	$menuListArr[2]['pageurl'] = "statistic.php";
	$menuListArr[3]['pagename'] = "排程設定";
	$menuListArr[3]['pageurl'] = "schedulesetting.php";
	
	echo '<!-- Header -->
			<header id="header">
				<a class="logo" href="index.php">Home</a>
				<nav>
					<a href="#menu">Menu</a>
				</nav>
			</header>

		<!-- Nav -->
			<nav id="menu">
				<ul class="links">
		';
	
	for ($i = 0; $i < count($menuListArr); $i++)
	{		
		echo '<li><a href="'.$menuListArr[$i]['pageurl'].'">'.$menuListArr[$i]['pagename'].'</a></li>';		
	}
	
	echo '</ul>
			</nav>';
}


?>