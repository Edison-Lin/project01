<?php

class ManagerStation
{
    protected $arrValue;
    protected $columns;
    protected $columnsChinese;
    protected $dbManager;
    protected $keyColumn;
    protected $rowsCount;
    protected $tableName;
    
    function __construct(DatabaseManager $dbManager)
    {
        $this->dbManager = $dbManager;
        $this->tableName = "";
        $this->columns = array();
    }

    function __get($name) {
        return $this->$name;
    }

    function _GET($str){

        $val = isset($_GET[$str]) ? htmlspecialchars($_GET[$str]) : null;
    
        return $val;
    }

    function _FILE($str){

        $val = isset($_FILES[$str]) ? $_FILES[$str] : null;
    
        return $val;
    }

    function _POST($str){
        $val = isset($_POST[$str]) ? htmlspecialchars($_POST[$str]) : null;
    
        return $val;
    }
    
    /**
     * 搜尋表內所有資料。
     * 
     * @param   string  $orderby            排序欄位。
     * @param   int     $intRecordPage      每頁顯示筆數。
     * @param   int     $Page               目前頁碼。
     */
    function SelectAll($orderBy, $intRecordPage, $strLink, $Page, $Css1, $Css2)
	{
        $dbManager = $this->dbManager;

        // 取出資料庫總筆數
        $rowsCount = $dbManager->SelectCount($this->tableName, array(), array());
        if ($rowsCount == 0)
        {
            $this->rowsCount = -1;
            return "";
        }

        // 計算應有頁數
        if (!$Page) $Page = 1;
        if ($intRecordPage==0) $intRecordPage = $rowsCount;
		$totalpages = ceil($rowsCount / $intRecordPage);
		if ($Page > $totalpages) {
			$Page = $totalpages;
		}

        // 計算頁碼起始資料的index
        $moverow = ($Page - 1) * $intRecordPage;

        // 設定JOIN、排序與資料筆數
        $join = "";
        $strOrderBy = "ORDER BY $orderBy";
        $strLimit = "LIMIT $moverow, $intRecordPage";
        
        // 搜尋
        $results = $dbManager->Select($this->tableName, $this->columns, $join, array(), array(), $strOrderBy, $strLimit);
        // JSON格式轉物件
        $this->arrValue = json_decode($results, true);

        if(count($this->arrValue))
        {
            $this->rowsCount = count($this->arrValue);
        }
        else
        {
            $this->rowsCount = -1;
        }

        return $this->Paging($rowsCount, $intRecordPage, $strLink, $Page, $Css1, $Css2);
    }
    
    /**
     * 
     */
	function Paging($totalRows, $intRecordPage, $strLink, $Page, $Css1, $Css2)
	{
		$totalpages = ceil($totalRows / $intRecordPage);

		if ($Page > $totalpages)
			$selectPage = $Page - 1;
		else
			$selectPage = $Page;

		//echo $totalpages;
		$slider_number = 20;

		$selectLink = '';

		if ($totalpages <= $slider_number)
			for ($j = 1; $j <= $totalpages; $j++) {
				if ($selectPage == $j) {
					$selectLink .= '<div class="' . $Css2 . '">' . $j . '</div>';
				} else {
					$selectLink .= '<a href="' . $strLink . '&page=' . $j . '" class="' . $Css1 . '">' . $j . '</a>';
				}
			} else {
			$tempOffset = floor($slider_number / 2);
			//echo "selectPage=" . $selectPage;
			//12....
			if ($selectPage <= $tempOffset)
				for ($j = 1; $j <= $slider_number; $j++) {
					if ($selectPage == $j) {
						$selectLink .= '<div class="' . $Css2 . '">' . $j . '</div>';
					} else {
						$selectLink .= '<a href="' . $strLink . '&page=' . $j . '" class="' . $Css1 . '">' . $j . '</a>';
					}
				} else if ($selectPage >= ($totalpages - $tempOffset))
				for ($j = $totalpages - $slider_number + 1; $j <= $totalpages; $j++) {
					if ($selectPage == $j) {
						$selectLink .= '<div class="' . $Css2 . '">' . $j . '</div>';
					} else {
						$selectLink .= '<a href="' . $strLink . '&page=' . $j . '" class="' . $Css1 . '">' . $j . '</a>';
					}
				} else
				for ($j = ($selectPage - $tempOffset); $j <= ($selectPage + $tempOffset); $j++) {
					if ($selectPage == $j) {
						$selectLink .= '<div class="' . $Css2 . '">' . $j . '</div>';
					} else {
						$selectLink .= '<a href="' . $strLink . '&page=' . $j . '" class="' . $Css1 . '">' . $j . '</a>';
					}
				}
		}


		if ($Page != 1) {
			$tempPrevLink = '<a href="' . $strLink . '&Page=1" class="' . $Css1 . '">&lt;&lt;</a><a href="' . $strLink . '&page=' . ($Page - 1) . '" class="' . $Css1 . '">&lt;</a>';
		} else {
			$tempPrevLink = '';
		} //'<span class="'.$Css2.'">上一頁</span>'; }

		if ($totalpages > $Page) {
			$tempNextLink = '<a href="' . $strLink . '&page=' . ($Page + 1) . '" class="' . $Css1 . '">&gt;</a><a href="' . $strLink . '&page=' . ($totalpages) . '" class="' . $Css1 . '">&gt;&gt;</a>';
		} else {
			$tempNextLink = '';
		} //'<span class="'.$Css2.'">下一頁</span>'; }


		$PageMsg =  '<div style="float:right;">' . $tempPrevLink . $selectLink . $tempNextLink . '</div>';


		if ($totalRows == 0 || $totalRows <= $intRecordPage)  return  "";
		else  return  $PageMsg;
    }
    
    function SelectByID($id)
    {
        $dbManager = $this->dbManager;

        $whereColumns = array();
        array_push($whereColumns, $this->keyColumn);

        $whereValues = array();
        array_push($whereValues, $id);

        $results = $dbManager->Select($this->tableName, $this->columns, "", $whereColumns, $whereValues, "", "");

        $this->arrValue = json_decode($results, true);
        
        $this->SetIndex(0);
    }

    /**
     * 搜尋表內資料，輸出Json結果。
     * 
     * @param   Array   $columns            輸出的欄位，預設為全欄位。
     * @param   Array   $whereColumns       搜尋的欄位。
     * @param   Array   $whereValues        搜尋的欄位對應的值。
     * @param   string  $orderby            排序欄位。
     * @param   string  $limit              最大輸出筆數。
     */
    function ListBySeach($columns, $whereColumns, $whereValues, $orderBy, $limit)
    {
        $dbManager = $this->dbManager;

        if(count($whereColumns)!=count($whereValues))
        {
            return "";
        }

        if(count($columns)==0)
        {
            $columns = $this->columns;
        }

        if(strlen($orderBy)>0)
            $orderBy = "ORDER BY " . $orderBy;

        $results = $dbManager->Select($this->tableName, $columns, "", $whereColumns, $whereValues, $orderBy, $limit);

        return $results;
    }
    
    /**
     * 從搜尋後結果的資料集中取出一行資料，並將其依欄位放置資料。可使用$varname->columnName的形式取出資料，如：$ManagerStation->columnA。
     * 
     * @param   string      $intIndex       資料索引，<0或索引超過資料集則為空資料
     */
	function SetIndex($intIndex)
	{
		for ($ii = 0; $ii < count($this->columns); $ii++) {
            // 取得欄位名稱
            $tempColumns = $this->columns[$ii];

            // 帶入資料，若不存在則清空欄位的值
            if($intIndex < count($this->arrValue))
            {
                $this->$tempColumns = $this->arrValue[$intIndex][$tempColumns];
            }
            else
            {
                $this->$tempColumns = "";
            }
		}
    }
    
    /**
     * 基本id形式，以時間格式的YmdHisu。
     * 
     * @return 輸出datetime->format("YmdHisu")。
     */
    function GetNewID()
    {
        $dateTime = new DateTime();

        $strID = $dateTime->format("YmdHisu");

        return $strID;
    }

    /**
     * 基本id形式，以數字序列。
     * 
     * @return 輸出目前資料表中MAX(ID)+1。
     */
    function GetNewSimpleID()
    {
        $dbManager = $this->dbManager;

        $columns = array();
        array_push($columns, $this->keyColumn);

        $resultJSON = $dbManager->Select($this->tableName, $columns, "", array(), array(), "ORDER BY $this->keyColumn DESC", "LIMIT 1");
        $result = json_decode($resultJSON, true);

        if(count($result) == 0)
            return 1;
        else 
            return $result[0][$this->keyColumn]+1;
    }

    /**
     * 取得form以POST傳來的值，並存放在對應欄位的變數上，如：$id = $this->_POST("id")。
     * 
     * @return false表示POST的值，或POST的值無法對應任何欄位，true表示POST的值至少一個對應到欄位。 
     */
	function GetFormData()
	{
        $empty_count = 0;
        foreach($this->columns as $column)
        {
            $this->$column = $this->_POST($column);

            if(strlen($this->$column)==0)
            {
                $this->$column = "";
                $empty_count++;
            }
        }

        if($empty_count==count($this->columns))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * 
     */
	function Insert()
	{
        $dbManager = $this->dbManager;

        $columns = array();
        $values = array();

        foreach($this->columns as $column)
        {
            if(isset($this->$column)==false || strlen($this->$column)==0)
                continue;
            
            $fieldValue = $this->$column;

            array_push($columns, $column);
            array_push($values, $fieldValue);
        }
        
        $result = $dbManager->Insert($this->tableName, $columns, $values);

        return $result;
    }

    function InsertWithFile($path, $uploadColumns)
	{
        $dbManager = $this->dbManager;

        // 上傳檔案，更改檔案 為 檔案儲存路徑予資料庫寫入
        $keyColumn = $this->keyColumn;
        foreach($uploadColumns as $uploadColumn)
        {
            $file = $this->_FILE($uploadColumn);
            if(isset($file) && $file["error"] == 0)
            {
                $fileName = "up_" . $this->$keyColumn;
                
                $dest = $this->FileUpload($file, $path, $fileName, false);

                if( isset($dest) && strlen($dest)>0 )
                {
                    $this->$uploadColumn = $dest;
                }
                else
                {
                    return "";
                }
            }
        }

        // 處理寫入的欄位
        $columns = array();
        $values = array();

        foreach($this->columns as $column)
        {
            if(isset($this->$column)==false || strlen($this->$column)==0)
                continue;
            
            $fieldValue = $this->$column;

            array_push($columns, $column);
            array_push($values, $fieldValue);
        }
        
        $result = $dbManager->Insert($this->tableName, $columns, $values);

        return $result;
    }
    
    /**
     * 
     * @return bool 回傳是否成功執行Update
     */
	function Update($strID)
	{
        $dbManager = $this->dbManager;

        $columns = array();
        $setValues = array();
        
        // 取得待更新的欄位與資料
        foreach($this->columns as $column)
        {
            if(isset($this->$column)==false || strlen($this->$column)==0)
                continue;
            
            $fieldValue = $this->$column;

            array_push($columns, $column);
            array_push($setValues, $fieldValue);
        }
        
        // 建立條件
        $whereColumns = array($this->keyColumn);
        $whereValues = array($strID);

        $result = $dbManager->Update($this->tableName, $columns, $setValues, $whereColumns, $whereValues);

        return $result;
    }

    /**
     * @param   string      $strID              單號/ID。
     * @param   string      $storePath          檔案欲存放的路徑。
     * @param   array       $uploadColumns      FILES["key"]的key值與對應的資料庫欄位名稱。
     */
    function UpdateWithFile($strID, $storePath, $uploadColumns)
	{
        $dbManager = $this->dbManager;

        // 上傳檔案，更改檔案 為 檔案儲存路徑予資料庫寫入
        $keyColumn = $this->keyColumn;
        foreach($uploadColumns as $uploadColumn)
        {
            $file = $this->_FILE($uploadColumn);
            if(isset($file) && $file["error"] == 0)
            {
                $fileName = "up_" . $strID;
                
                $dest = $this->FileUpload($file, $storePath, $fileName, false);

                if( isset($dest) && strlen($dest)>0 )
                {
                    $this->$uploadColumn = $dest;
                }
                else
                {
                    return "";
                }
            }
        }

        // 處理寫入的欄位
        $columns = array();
        $setValues = array();
        
        // 取得待更新的欄位與資料
        foreach($this->columns as $column)
        {
            if(isset($this->$column)==false || strlen($this->$column)==0)
                continue;
            
            $fieldValue = $this->$column;

            array_push($columns, $column);
            array_push($setValues, $fieldValue);
        }
        
        // 建立條件
        $whereColumns = array($this->keyColumn);
        $whereValues = array($strID);

        $result = $dbManager->Update($this->tableName, $columns, $setValues, $whereColumns, $whereValues);

        return $result;
    }

    /**
     * 
     */
    function Delete($strID)
    {
        $dbManager = $this->dbManager;

        // 建立條件
        $whereColumns = array();
        array_push($whereColumns, $this->keyColumn);

        $whereValues = array();
        array_push($whereValues, $strID);

        $result = $dbManager->Delete($this->tableName, $whereColumns, $whereValues);

        return $result;
    }

    /**
     * @param   FILE        $file           上傳的檔案。
     * @param   string      $path           指定上傳的路徑。
     * @param   string      $fileName       上傳時保存的檔案名稱(無副檔名)。
     * @param   bool        $perserve       上傳目錄下已有相同檔名的檔案時，是否保留原始檔案。
     * 
     * @return  string      檔案位置與名稱。
     */
    function FileUpload($file, $path, $fileName, $preserve)
    {
        $dest = "";

        // 檢查檔案資訊
        if ($file["error"] > 0 || $file["size"] < 512)
        {
            return $dest;
        }
        else
        {
            /*echo "檔名: " . $file["name"] . "<br />";
            echo "檔案型別: " . $file["type"] . "<br />";
            echo "檔案大小: " . ($file["size"] / 1024) . " Kb<br />";
            echo "快取檔案: " . $file["tmp_name"] . "<br />";*/
        }

        $oFileName = $file["name"];
        $ext = pathinfo($oFileName, PATHINFO_EXTENSION);
		if(isset($ext)==false || strlen($ext)==0)
		{
			$ext = "jpg";
		}
        
        // 保留原始檔案下，處理重複命名
        while ( file_exists($path . $fileName . "." . $ext) && $preserve )
        {
            $dateTime = new DateTime();

            $fileName = $fileName . "_" . $dateTime->format("YmdHisu");
        }
        
        $fileName = $fileName . "." . $ext;
        $dest = $path . $fileName;
        
        move_uploaded_file($file["tmp_name"], $dest);

        return $dest;
    }

    function FileDelete($file)
    {
        if(unlink($file))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
