<?php require_once 'ManagerStation.php'; ?>
<?php

class clsCourse extends ManagerStation
{
    /**
     * __construct
     *      建構子
     */
    function __construct(DatabaseManager $dbManager)
    {
        parent::__construct($dbManager);

        $this->tableName = "course";
        $this->keyColumn = "cid";

        $this->columns = array(
            "cid",
            "cname",
            "credit"
        );

        $this->columnsChinese = array(
            "cid" => "課程編號",
            "cname" => "課程名稱",
            "credit" => "學分數",
        );
    }

    function ListMember($whereColumn, $whereValue, $orderBy, $intRecordPage, $strLink, $Page, $Css1, $Css2)
    {
        $dbManager = $this->dbManager;

        // 取出資料庫總筆數
        if ((count($whereColumn) == count($whereValue)) && count($whereColumn) > 0 && count($whereValue) > 0) {
            $rowsCount = $dbManager->FuzzySelectCount($this->tableName, $whereColumn, $whereValue);
        } else {
            $rowsCount = $dbManager->SelectCount($this->tableName, $whereColumn, $whereValue);
        }
    
        if ($rowsCount == 0) {
            $this->rowsCount = -1;
            return "";
        }
      //  var_dump($rowsCount);
        // 計算應有頁數
        if (!$Page) $Page = 1;
        if ($intRecordPage == 0) $intRecordPage = $rowsCount;
        $totalpages = ceil($rowsCount / $intRecordPage);
        if ($Page > $totalpages) {
            $Page = $totalpages;
        }

        // 計算頁碼起始資料的index
        $moverow = ($Page - 1) * $intRecordPage;

        // 更改輸出欄位: MemberRole轉換為MR.Name
        $listColumn = $this->columns;

        // 設定JOIN、排序與資料筆數
        $join = "";
        $strOrderBy = "ORDER BY $orderBy";
        $strLimit = "LIMIT $moverow, $intRecordPage";
        // 搜尋
        if ((count($whereColumn) == count($whereValue)) && count($whereColumn) > 0 && count($whereValue) > 0) {
     
            $results = $dbManager->FuzzySelect($this->tableName, $listColumn, $join, $whereColumn, $whereValue, $strOrderBy, $strLimit);
      
        } else {
         
            $results = $dbManager->Select($this->tableName, $listColumn, $join, $whereColumn, $whereValue, $strOrderBy, $strLimit);
       
        }

        // JSON格式轉物件
        //print_r(json_decode($results, true));
        $this->arrValue = json_decode($results, true);

        if (count($this->arrValue)) {
            $this->rowsCount = count($this->arrValue);
        } else {
            $this->rowsCount = -1;
        }
       
        return $this->Paging($rowsCount, $intRecordPage, $strLink, $Page, $Css1, $Css2);
    }

    function ListMemberForControl($columns, $whereColumns, $whereValues)
    {
        $dbManager = $this->dbManager;

        $orderBy = "ORDER BY cid";

        $result = $dbManager->FuzzySelect($this->tableName, $columns, "", $whereColumns, $whereValues, $orderBy, "");

        return $result;
    }

   
    /**
     * 檢查ID是否存在。
     * 
     * @param   string      sID     待查的id
     * 
     * @return  bool        TRUE on 已存在 or FALSE on 不存在.
     */
    function CheckID($sID)
    {
        $this->SelectByID($sID);
        if (count($this->arrValue) > 0) {
            return true;    // id exist
        } else {
            return false;   // 
        }
    }

    /**
     * 檢查Phone是否存在。
     * 
     * @param   string      sPhone     待查的Phone
     * 
     * @return  bool        TRUE on 已存在 or FALSE on 不存在.
     */
    function CheckPhone($sPhone)
    {
        $this->keyColumn = "Phone";

        $this->SelectByID($sPhone);
        if (count($this->arrValue) > 0) {
            return true;    // id exist
        } else {
            return false;   // 
        }
    }

    
}
?>