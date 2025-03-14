<?php require_once 'ManagerStation.php'; ?>
<?php

class clsMember extends ManagerStation
{
    /**
     * __construct
     *      建構子
     */
    function __construct(DatabaseManager $dbManager)
    {
        parent::__construct($dbManager);

        $this->tableName = "member";
        $this->columns = array(
            "mid",
            "passwd",
            "mname",
            "lastlogindatetime"
        );
    }

    function Login($sID, $sPW)
    {
        $dbManager = $this->dbManager;

        if ($sID == '' || $sPW == '') return 0; //login falure

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $sID)) return 0; //avoid sql injection
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $sPW)) return 0; //avoid sql injection

        $whereColumn = array();
        array_push($whereColumn, "mid");

        $whereValue = array();
        array_push($whereValue, $sID);

        $resultsJson = $dbManager->Select($this->tableName, $this->columns, "", $whereColumn, $whereValue, "", "");
        $results = json_decode($resultsJson, true);

        foreach ($results as $row) {
            if ($row['passwd'] == $sPW) {
                foreach ($this->columns as $column) {
                    $this->$column = $row[$column];
                }

                $setColumns = array();
                array_push($setColumns, "lastlogindatetime");

                $setValues = array();
                array_push($setValues, date("Y-m-d H:i:s"));

                $response = $dbManager->Update($this->tableName, $setColumns, $setValues, $whereColumn, $whereValue);

                // 更新時間錯誤
                if (!$response)
                    return 2;

                return 0; //login sucess
            } else {
                return 1; //login falure
            }
        }

        return 3; //login falure
    }
}
?>