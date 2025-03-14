<?php
    session_start();
    if (!isset($_SESSION['sAccount']))
	header('Location: login.php');
?>    
<?php require 'include/Global.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>後台 | <?php echo $Global_Title ?></title>
	<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />

	<script src="include/js/jquery-3.4.1.min.js"></script>
	<script src="include/CKEdit/ckeditor/ckeditor.js"></script>
	<script>
		function redirectDialog(filename, mode, msg) {
			alert(msg);

			location.replace(filename + "?mode=" + mode);
		}

		function deleteConfirm(filename, id) {
			if (confirm("警告：\n  確定刪除 ID為 " + id + " 的資料嗎?") == 1)
				location.replace(filename + "?mode=delete&id=" + id);
			else
				return false;
		}

		function refreshPage(page, mode) {
			location.replace(page + '?mode=' + mode);
		}

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#preview_image").attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		function readImageURL(index, input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					switch (index) {
						case 1:
							$("#preview_image").attr('src', e.target.result);
							break;
						case 2:
							$("#preview_image2").attr('src', e.target.result);
							break;
						case 3:
							$("#preview_image3").attr('src', e.target.result);
							break;
						default:
					}
				}

				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</head>

<body>
	<div id="header">
		<div class="content">
			<div class="logo"><a href="./"><img style="width:100px;margin:10px 0px 0px 0px;" src="logo/logo.png" /></a></div>
			<div class="logout"><a href="login.php?mode=logout">[ 登出 ]</a></div>
			<div class="lastlogintime"><?=$_SESSION["mname"]?>您好，登入時間 : <?php echo $_SESSION["LastLoginDateTime"] ?></div>
		</div>
	</div>

	<div id="container">

		<div class="content">

			<?php
			$a = explode("/", $_SERVER["SCRIPT_NAME"]);
			
			$tempFile = $a[count($a) - 1];

			?>

			<div class="sidebar">
				<ul>
					<li style="margin-top:15px;background-color:#632704;color:white;text-align:center;">管理功能</li>
					<li style="margin-top:5px;"><a class="<?php if ($tempFile == "member.php") echo 'selected'; ?>" href="member.php?mode=browse">會員管理</a></li>
					<li style="margin-top:5px;"><a class="<?php if ($tempFile == "course.php") echo 'selected'; ?>" href="course.php?mode=browse">課程管理</a></li>


					<ul>
			</div>

			<div class="edit_area">