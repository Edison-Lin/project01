<?php require_once 'ManagerStation.php'; ?>
<?php

class clsMembers extends ManagerStation
{
    /**
     * __construct
     *      建構子
     */
    function __construct(DatabaseManager $dbManager)
    {
        parent::__construct($dbManager);

        $this->tableName = "member";
        $this->keyColumn = "mid";

        $this->columns = array(
            "mid",
            "mname",
            "passwd",
            "lastlogindatetime"
        );

        $this->columnsChinese = array(
            "mid" => "編號",
            "mname" => "姓名",
            "passwd" => "密碼",
            "lastlogindatetime" => "最後登入時間"
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

        $orderBy = "ORDER BY mid";

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

    /**
     * 
     */
    function GetLastLoginDateTime($sID)
    {
    }

    /**
     * 
     */
    function Login($sID, $sPW, $loginMedia)
    {
        $dbManager = $this->dbManager;

        if ($sID == '' || $sPW == '') return 0; //login falure

        $whereColumn = array();
        array_push($whereColumn, "Email");

        $whereValue = array();
        array_push($whereValue, $sID);

        $resultsJson = $dbManager->Select($this->tableName, $this->columns, "", $whereColumn, $whereValue, "", "");
        $results = json_decode($resultsJson, true);

        foreach ($results as $row) {
            if ((strcmp($loginMedia, "A") != 0 || strcmp($loginMedia, "I") != 0) && $row['Password'] == $sPW) {
                foreach ($this->columns as $column) {
                    $this->$column = $row[$column];
                }

                $whereValue = array();
                array_push($whereValue, $this->Email);

                $setColumns = array();
                array_push($setColumns, "LastLoginDateTime");
                array_push($setColumns, "LastLoginUsed");

                $setValues = array();
                array_push($setValues, date("Y-m-d H:i:s"));
                array_push($setValues, $loginMedia);

                $response = $dbManager->Update($this->tableName, $setColumns, $setValues, $whereColumn, $whereValue);

                // 檢查是否已驗證
                if (strlen($row['CheckNo']) > 1 || strcmp($row['CheckNo'], "0") != 0) {
                    return 4;   // email not check
                }

                // 更新時間錯誤
                if (!$response)
                    return 2;   // login falure: time update error

                return 0; // login sucess
            } else {
                return 1; // login falure: password error
            }
        }

        return 3; // login falure: no exist
    }

    function Register()
    {
        $keyColumn = $this->keyColumn;
        $sID = $this->$keyColumn;

        $result = $this->Insert();
        if ($result) {
            return 0;   // register sucess
        } else {
            return 2;   // register falure: insert error
        }

        return 3;   // register falure: logical error
    }

    function CheckUser($sID, $sPW)
    {
        $dbManager = $this->dbManager;

        if ($sID == '' || $sPW == '') return 1; //login falure

        $whereColumn = array();
        array_push($whereColumn, "Email");

        $whereValue = array();
        array_push($whereValue, $sID);

        $resultsJson = $dbManager->Select($this->tableName, $this->columns, "", $whereColumn, $whereValue, "", "");
        $results = json_decode($resultsJson, true);

        if (hash("sha256", $sID) == $sPW) {
            return 0;
        }

        foreach ($results as $row) {
            if ($row['Password'] == $sPW) {
                foreach ($this->columns as $column) {
                    $this->$column = $row[$column];
                }

                return 0; // login sucess
            } else {
                return 1; // login falure: password error
            }
        }

        return 3; // login falure: no exist
    }

    function GetUserInfo($id)
    {
        $dbManager = $this->dbManager;

        $keyColumn = $this->keyColumn;

        $whereColumns = array();
        array_push($whereColumns, $keyColumn);

        $whereValues = array();
        array_push($whereValues, $id);

        $results = $dbManager->Select($this->tableName, $this->columns, "", $whereColumns, $whereValues, "", "LIMIT 1");

        return $results;
    }

    function UpdateAddress($id, $address)
    {
        $dbManager = $this->dbManager;

        $keyColumn = $this->keyColumn;

        $whereColumn = array();
        array_push($whereColumn, $keyColumn);
        $whereValues = array();
        array_push($whereValues, $id);

        $setColumns = array();
        array_push($setColumns, "Address");
        $setValues = array();
        array_push($setValues, $address);

        $results = $dbManager->Update($this->tableName, $setColumns, $setValues, $whereColumn, $whereValues);

        return $results;
    }

    // =========================驗證碼相關=========================
    function CreateCheckNo($email)
    {
        $result = "1";

        $this->SelectByID($email);
        if (count($this->arrValue) > 0) {
            if (strlen($this->CheckNo) == 0 || strlen($this->CheckNo) > 1) {
                // 已存在驗證碼(位數大於1位)，或不存在驗證碼(預設的CheckNo值大於兩位)則產生驗證碼
                $dateString = date("Y-m-d H:i:s");
                $checkNo = hash('sha256', $dateString);

                $this->CheckNo = $checkNo;
                $success = $this->Update($email);

                if ($success == true) {
                    // 建立認證碼成功，回傳認證碼
                    $result = $checkNo;
                } else {
                    // 建立認證碼失敗
                    $result = "-3";
                }
            } else if ($this->CheckNo == 0) {
                // 該會員已認證
                $result = "-2";
            }
        } else {
            // 查無該會員
            $result = "-1";
        }

        return $result;
    }

    function CheckCheckNo($email, $checkNo)
    {
        $result = "1";

        $this->SelectByID($email);
        if (count($this->arrValue) > 0) {
            if (strlen($this->CheckNo) > 1) {
                if (strcmp($checkNo, $this->CheckNo) == 0) {
                    // 驗證通過
                    // 更新資料庫
                    $this->CheckNo = "0";
                    $success = $this->Update($email);

                    if ($success == true) {
                        // 更新成功
                        $result = "0";
                    } else {
                        // 更新失敗
                        $result = "-4";
                    }
                } else {
                    // 驗證失敗
                    $result = "-3";
                }
            } else if ($this->CheckNo == 0) {
                // 會員已驗證
                $result = "-2";
            } else {
                // 資料庫預設值錯誤
                $result = "-5";;
            }
        } else {
            // 會員資料不存在
            $result = "-1";
        }

        return $result;
    }
}
?>