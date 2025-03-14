<?php require 'include/Global.php';?>
<?php require 'include/DatabaseManager.php'; ?>
<?php
require "include/clsMember.php";
$Member = new clsMember($dbManager);

$mode = $Member->_GET("mode");
switch ($mode) {
	case "login":
		$check = $Member->Login($Member->_POST("Username"), $Member->_POST("Password"));
		if ($check == 0) {
			$_SESSION["sAccount"]    =  $Member->mid;
			$_SESSION["mname"] = $Member->mname;
			$_SESSION["LastLoginDateTime"] = date("Y-m-d H:i:s") ;
			header('Location: index.php');
			exit;
		}
		elseif($check == 4){
			$err = '* SQL 注入攻擊';
		}	
		else
		$err = '* 錯誤的帳號及密碼';
		break;
	case "logout":
		unset($_SESSION["sAccount"]);
		header("location: login.php");
		break;
	default:		
	}
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $Global_Title ?></title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="login">
		<div style="margin-bottom:20px;margin-left:50px;"><img style="width:120px;" src="logo/logo.png" /></div>
		<div class="body">

			<form method="post" action="login.php?mode=login">

				<div class="rows">

					<div class="row">
						<div class="name">帳號</div>
						<div class="value"><input style="width:140px;" name="Username" type="text" maxlength="50" /></div>
					</div>


					<div class="row">
						<div class="name">密碼</div>
						<div class="value"><input style="width:140px;" name="Password" type="password" maxlength="50" /></div>
					</div>


					<?php
					if ($err) {
					?>
						<div class="row">
							<div class="name">&nbsp;</div>
							<div class="value">
								<div class="err"><?php echo $err ?></div>
							</div>
						</div>
					<?php
					}
					?>


					<div class="row2">
						<div class="name"></div>
						<div class="value"><input type="submit" name="submit" value="登 入" class="button1" /></div>
					</div>


				</div>
			</form>
		</div>
	</div>


</body>

</html>