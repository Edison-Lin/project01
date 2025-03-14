<?php require 'include/clsCourse.php'; ?>
<?php require 'include/DatabaseManager.php'; ?>
<?php require 'include/Global.php'; ?>
<?php require 'header.php'; ?>
<?php
$Course = new clsCourse($dbManager);
$keyColumn = $Course->keyColumn;

$ThisFileName = basename($_SERVER['PHP_SELF']);
$ThisPageName = "課程管理";

$mode = $Course->_GET("mode");

// 編輯畫面變數
$msg = "";
$err = "";
$varEdit = "";
$password_required = "required";
$columnsChinese = array();
$timeChinese = "";

// Data Region
switch ($mode) {
	case "":
	case "browse":
		$PageListNum = 20;
		$PageListLink = "?mode=browse";

		$Page = $Course->_GET("page");
		if (!$Page) {
			$Page = 1;
		}

		$columns = $Course->columns;
		$columnsChinese = $Course->columnsChinese;

		/* ====================排序==================== */
		// 取得最後瀏覽紀錄
		if (isset($_SESSION["viewPage"]) == true) {
			$viewPage = $_SESSION["viewPage"];
		} else {
			$viewPage = "";
		}

		// 檢查是否為相同頁面
		if (strlen($viewPage) == 0 || strcmp($ThisFileName, $viewPage) != 0) {
			$_SESSION["orderMode"] = "";
			$_SESSION["orderBy"] = "";
		}

		// 抓取排序標的
		$ori_orderBy = $Course->_GET("orderBy");
		$orderBy = $Course->_GET("orderBy");
		if (strlen($orderBy) == 0) {
			// 預設排序為CreateTime
			$orderBy = "cid";
		}

		// 設定排序模式
		$orderMode = (isset($_SESSION["orderMode"])) ? $_SESSION["orderMode"] : "";
		if (strlen($orderMode) == 0) {
			// 預設排序模式
			$orderMode = "ASC";

			// 更改排序標的的中文敘述
			$indexofOrder = array_search($orderBy, $columns);
			$columnsChinese = array_merge($columnsChinese, array($orderBy => $columnsChinese[$orderBy] . "↓"));
		} else if (strlen($ori_orderBy) != 0) {
			// 依據過去排序模式，更改標的的中文敘述
			$pre_orderBy = $_SESSION["orderBy"];
			if (strcmp($pre_orderBy, $orderBy) == 0) {
				// 與前次排序相同，模式反向
				if (strcmp($orderMode, "DESC") == 0) {
					$orderMode = "ASC";

					// 更改排序標的的中文敘述
					$indexofOrder = array_search($orderBy, $columns);
					$columnsChinese = array_merge($columnsChinese, array($orderBy => $columnsChinese[$orderBy] . "↑"));
				} else {
					$orderMode = "DESC";

					// 更改排序標的的中文敘述
					$indexofOrder = array_search($orderBy, $columns);
					$columnsChinese = array_merge($columnsChinese, array($orderBy => $columnsChinese[$orderBy] . "↓"));
				}
			} else {
				// 與前次不同則採用預設排序模式
				$orderMode = "DESC";

				// 更改排序標的的中文敘述
				$indexofOrder = array_search($orderBy, $columns);
				$columnsChinese = array_merge($columnsChinese, array($orderBy => $columnsChinese[$orderBy] . "↓"));
			}
		}

		// 時間資訊中文敘述調整
		$timeChinese = "時間資<br>訊";
		if (strlen($ori_orderBy) == 0) {
			$orderMode = "DESC";
			$timeChinese .= "↓";
		} else {
			if (strcmp($orderBy, "CreateTime") == 0) {
				if (strcmp($orderMode, "DESC") == 0) {
					// 更改中文敘述
					$timeChinese .= "↓";
				} else {
					// 更改中文敘述
					$timeChinese .= "↑";
				}
			}
		}

		$order = $orderBy . " " . $orderMode;

		// 重設Session
		$_SESSION["viewPage"] = $ThisFileName;
		$_SESSION["orderBy"] = $orderBy;
		$_SESSION["orderMode"] = $orderMode;

		// 搜尋模式
		$whereColumn = array();
		$whereValue = array();

		$key = $Course->_GET("WhereColumn");
		$value = $Course->_GET("WhereValue");
		if (strlen($key) > 0 && strlen($value) > 0) {
			array_push($whereColumn, $key);
			array_push($whereValue, "%" . $value . "%");
			$ThisPageName .= "(搜尋: " . $key . "=" . $value . ")";
		}

		$strPageList = $Course->ListMember($whereColumn, $whereValue, $order, $PageListNum, $PageListLink, $Page, "paging", "paging_current");
	
		break;
	case "addsave":
		$Course->GetFormData();

		//$Course->Password = hash("sha256", $Course->Password);

		$success = $Course->Insert();

		$mode = "browse";

		if ($success) {
			$msg = "課程: " . $Course->$keyColumn . " 新增成功!";
			echo "<script>redirectDialog('$ThisFileName','$mode', '$msg');</script>";
		} else {
			$mode="add";
			$err = "新增失敗!";
		}

		break;
	case "add":
		$Course->GetFormData();

		break;
	case "updatesave":
		$Course->GetFormData();

	//	if (strlen($Course->Password) > 0)
	//		$Course->Password = hash("sha256", $Course->Password);

		$id = $Course->_GET("id");

		$saveState = $Course->Update($id);

		if ($saveState) {
			$msg = "ID: " . $id . " 更新成功!";
		} else {
			$err = "更新失敗!";
		}

		$mode = "update";
	case "update":
		$id = $Course->_GET("id");

		$Course->SelectByID($id);

		// 清除密碼與其要求
	//	$Course->Password = "";
//		$password_required = "";

		// 設定Main Page Header標題
		$ThisPageName .= " ｜ ID: " . $id;

		// 設定表單formEdit action指向的id
		$varEdit = "&id=" . $id;

		break;
	case "delete":
		$id = $Course->_GET("id");
		$mode = "browse";

		$success = $Course->Delete($id);

		if ($success) {
			echo "<script>redirectDialog('$ThisFileName','$mode', 'ID: $id 的資料已刪除!');</script>";
		} else {
			echo "<script>redirectDialog('$ThisFileName', '$mode', '刪除失敗');</script>";
		}

		break;
}

// Main Page Header
//######################################################################################################################
?>
<script>
	$(document).ready(function() {
		$("#search_submit").click(function() {
			var column = $("#search_column").find(":selected").val();
			var keyword = $("#search_keyword").val();

			var url = window.location.href;
			if (url.indexOf("&") > 0)
				url = url.substr(0, url.indexOf("&"));

			if (keyword.length == 0) {
				window.location.href = url;
			} else {
				window.location.href = url + "&WhereColumn=" + column + "&WhereValue=" + keyword;
			}
		});
	});
</script>

<div class="table">
	<div class="header">
		<div class="title" id="main_page_head"><a href="<?php echo $ThisFileName ?>"><?php echo $ThisPageName ?></a></div>
		<div class="add"><a href="<?php echo $ThisFileName ?>?mode=add" title="新增"><img src="logo/add.png" border="0" /></a></div>
		<?php
		if (strcmp($mode, "browse") == 0) {
		?>
			<div class="add"><button id="search_submit">搜尋</button></div>
			<div class="add"><input type="text" id="search_keyword" placeholder="請輸入關鍵字" /></div>
			<div class="add">
				<select id="search_column">
					<option value="cid">課程編號</option>
					<option value="cname">課程名稱</option>
				</select>
			</div>
		<?php
		}
		?>
	</div>

	<?php
	//######################################################################################################################
	switch ($mode) {
		case "":
		case "browse":
			// Start of if List
			if ($Course->rowsCount == -1 && strlen($key) == 0 && strlen($value) == 0) {
	?>
				<div align="center" style="padding-top:100px;padding-bottom:100px;">目前尚無資料!</div>
			<?php
			} else if ($Course->rowsCount == -1 && strlen($key) > 0 && strlen($value) > 0) {
			?>
				<div align="center" style="padding-top:100px;padding-bottom:100px;">查無欄位 <?php echo $columnsChinese["$key"] ?> 為 <?php echo $value ?> 的資料!</div>
			<?php
			} else {
			?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<th align="center"><a href="<?php echo $ThisFileName ?>?mode=browse&orderBy=cid"><?php echo $columnsChinese["cid"] ?></a></th>
						<th align="center"><a href="<?php echo $ThisFileName ?>?mode=browse&orderBy=mname"><?php echo $columnsChinese["mname"] ?></a></th>
						<th align="center"><a href="<?php echo $ThisFileName ?>?mode=browse&orderBy=passwd"><?php echo $columnsChinese["passwd"] ?></a></th>
						<th align="center">&nbsp;</th>
						<th align="center">&nbsp;</th>
					</tr>
					<?php
					// Start of for 建立資料行
					for ($i = 0; $i < $Course->rowsCount; $i++) {
						$Course->SetIndex($i);
					?>
						<tr>
							<?php
							// 取得欄位名稱資料並轉成表格內容
							foreach ($columns as $column) {
							?>
								<td align="center"><?php echo $Course->$column ?></td>
							<?php
							}
							?>
							<td align="right"><button class="button1" onclick="location.replace('<?php echo $ThisFileName ?>?mode=update&id=<?php echo $Course->$keyColumn ?>')">編 輯</button></td>
							<td align="right"><button class="button1" onclick="javascript:deleteConfirm('<?php echo $ThisFileName ?>', '<?php echo $Course->$keyColumn ?>')">刪 除</button></td>

						</tr>
					<?php
					} // End for 建立資料行
					?>
				</table>
			<?php
				// 輸出分頁資訊
				if (isset($strPageList) && $strPageList != "") echo $strPageList;;
			} // End of if List
			break;
		case "add":
			
		case "addsave":
			
		case "update":
			
		case "updatesave":
			
			?>
			<div>
				<form name="formEdit" enctype="multipart/form-data" method="post" action="<?php echo $ThisFileName ?>?mode=<?php echo $mode ?>save<?php echo $varEdit ?>">
					<div style="padding-top:16px;padding-bottom:16px;float:right;">
						<button type="button" class="button2" onclick="location.replace('<?php echo $ThisFileName ?>?mode=browse')">回上一頁</button>
					</div>
					<?php
					if ($msg != "") {
					?>
						<div style="height:30px;">
							<div class="msg" style="padding-bottom:20px"><?php echo $msg ?></div>
						</div>
						<script>
							$(".msg").delay(3000).fadeOut(1000);
						</script>
					<?php
					}

					if ($err != "") {
					?>
						<div class="err" style="padding-bottom:20px"><?php echo $err ?></div>
					<?php
					}
					?>
					<div class="clear">&nbsp;</div>

					<div class="row">
						<div class="subtitle">課程編號</div>
						<div><input type="text" id="cid" name="cid" value="<?php echo $Course->cid ?>" required /></div>
						<div class="clear">&nbsp;</div>
					</div>

					<div class="row">
						<div class="subtitle">課程名稱</div>
						<div><input type="text" name="cname" value="<?php echo $Course->cname ?>" required /></div>
						<div class="clear">&nbsp;</div>
					</div>

					<div class="row">
						<div class="subtitle">學分數</div>
						<div><input type="text" name="credit" value="<?php echo $Course->credit ?>" require /></div>
						<div class="clear">&nbsp;</div>
					</div>



					<div class="row">
						<div class="subtitle">&nbsp;</div>
						<div>
							<?php
						//	if (strcmp($mode, "add") == 0) {
			//					echo '<button type="submit" id="member_submit" class="button2" disabled>請輸入編號</button>&nbsp;';
			//				} else {
								echo '<button type="submit" class="button2">儲 存</button>&nbsp;';
			//				}
							?>
							<button type="button" class="button2" onclick="location.replace('<?php echo $ThisFileName ?>?mode=browse')">回上一頁</button>
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</form>
			</div>
	<?php
			break;
	}
	?>
	<?php
	//######################################################################################################################
	?>
	<div class="clear">&nbsp;</div>
</div> <!-- end table -->
<?php
//######################################################################################################################
?>
<?php require 'footer.php' ?>