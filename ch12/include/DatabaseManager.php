<?php
$dbManager = new DatabaseManager;

class DatabaseManager
{
    private $connection;
    const STATUS_OK = 0;
    const STATUS_CONNECTION_ERROR = -1;

    private $DBServer = "localhost";
    private $DBType = "mysql";
    private $Username = "root";
    private $Password = "123456";
    private $Database = "test";
    private $Charset = "utf8";

    /**
     * __call
     *      預設function/定義函式多載
     */
    function __call($name, $arguments)
    {
        /*if ($name == "f")
        {
            $count = count($arguments);
            if (method_exists($this, $f = 'f' . $count))
            {
                call_user_func_array(array($this, $f), $arguments);
            }
        }*/
    }

    /**
     * __construct
     *      建構子
     */
    public function __construct()
    {
        $dsn = $this->DBType;
        $dsn = $dsn . ":host=" . $this->DBServer;
        $dsn = $dsn . ";dbname=" . $this->Database;
        $dsn = $dsn . ";charset=" . $this->Charset;

        // 建立PDO物件與連接資料庫
        $connection = new PDO($dsn, $this->Username, $this->Password);
        
        // 維持長時間連接, 注意：需Close連線
        $connection = new PDO($dsn, $this->Username, $this->Password, array(PDO::ATTR_PERSISTENT => true));

        date_default_timezone_set("Asia/Taipei");

        // 設定資料庫編碼資訊
        $connection->exec("SET NAMES 'UTF8'");
        $connection->exec("SET character SET UTF8");
        
        $this->connection = $connection;
    }

    /**
     * __destruct
     *      解構子。注意：若PDO為長時間連接, 須調整此函式。
     */
    function __destruct()
    {
        $this->Close();
    }

    /**
     * Close
     *      關閉PDO連線。注意：若PDO為長時間連接, 需呼叫此函式以關閉連線。
     */
    function Close()
    {
        $this->connection = null;
    }

    /**
     * 搜尋。
     * 
     * @param   string      $tableName          資料表名稱。
     * @param   Array       $columns            output的欄位。
     * @param   string      $join               JOIN。
     * @param   Array       $whereColumns       Where中搜尋的欄位，需與Where相關陣列位置對應。
     * @param   Array       $whereValues        Where中搜尋的值，需與Where相關陣列位置對應。注意：值前後皆須加%
     * @param   string      $limit              輸出筆數，需加入LIMIT，如：LIMIT 10。
     * @param   string      $orderBy            需加入ORDER BY並依需求加入ASC或DESC，如：ORDER BY a DESC。
     */
    function FuzzySelect($tableName, $columns, $join, $whereColumns, $whereValues, $orderBy, $limit)
    {
        // 取得連線
        $dbConnection = $this->connection;

        $sql = "SELECT ";

        // 加入欄位
        if(count($columns))
        {
            $columnArray = array();

            foreach($columns as $column)
            {
                array_push($columnArray, $column);
            }
            
            $sql .= implode(",", $columnArray) . " ";
        }
        else
        {
            $sql .= "* ";
        }
        

        // 設定資料表
        $sql .= "FROM " . $tableName . " ";

        $sql .= $join . " ";

        // process whereColumns
        if(count($whereColumns) && (count($whereColumns) == count($whereValues)))
        {
            $sql .= "WHERE ";
            $whereConditions = array();

            // 條件以參數化查詢形式
            foreach($whereColumns as $column)
            {
                $condition = $column . " LIKE ?";
                array_push($whereConditions, $condition);
            }
            $sql .= implode(" OR ", $whereConditions);
        }
        $sql .= " ";
        
        // 加入其他參數
        $sql .= $orderBy . " ";
        $sql .= $limit . " ";

        // Prepared Statement
        $statement = $dbConnection->prepare($sql);
        // 參數化搜尋，帶入whereValues
        $statement->execute($whereValues);

        // 取得結果
        $results = $statement->fetchAll(PDO::FETCH_BOTH);
        
        // 編碼為json格式, 輸出結果; JSON_UNESCAPED_UNICODE 避免Unicode顯示原碼
      //  return json_encode($results, JSON_UNESCAPED_UNICODE);
        return json_encode($results);
    }

    /**
     * 搜尋。
     * 
     * @param   string      $tableName          資料表名稱。
     * @param   Array       $columns            output的欄位。
     * @param   string      $join               JOIN。
     * @param   Array       $whereColumns       Where中搜尋的欄位，需與Where相關陣列位置對應。
     * @param   Array       $whereValues        Where中搜尋的值，需與Where相關陣列位置對應。
     * @param   string      $limit              輸出筆數，需加入LIMIT，如：LIMIT 10。
     * @param   string      $orderBy            需加入ORDER BY並依需求加入ASC或DESC，如：ORDER BY a DESC。
     */
    function Select($tableName, $columns, $join, $whereColumns, $whereValues, $orderBy, $limit)
    {
        // 取得連線
        $dbConnection = $this->connection;

        $sql = "SELECT ";

        // 加入欄位
        if(count($columns))
        {
            $columnArray = array();

            foreach($columns as $column)
            {
                array_push($columnArray, $column);
            }
            
            $sql .= implode(",", $columnArray) . " ";
        }
        else
        {
            $sql .= "* ";
        }
        
        // 設定資料表
        $sql .= "FROM " . $tableName . " ";

        $sql .= $join . " ";

        // process whereColumns
        if(count($whereColumns) && (count($whereColumns) == count($whereValues)))
        {
            $sql .= "WHERE ";
            $whereConditions = array();

            // 條件以參數化查詢形式
            foreach($whereColumns as $column)
            {
                $condition = $column . "=?";
                array_push($whereConditions, $condition);
            }
            $sql .= implode(" AND ", $whereConditions);
        }
        $sql .= " ";
        
        // 加入其他參數
        $sql .= $orderBy . " ";
        $sql .= $limit . " ";
        
        // Prepared Statement
        $statement = $dbConnection->prepare($sql);
        // 參數化搜尋，帶入whereValues
        $statement->execute($whereValues);
        
        // 取得結果
        $results = $statement->fetchAll(PDO::FETCH_BOTH);
       
        // 編碼為json格式, 輸出結果; JSON_UNESCAPED_UNICODE 避免Unicode顯示原碼
        return json_encode($results);
    }

    function SelectCount($tableName, $whereColumns, $whereValues)
    {
        $countColumn = array();
        array_push($countColumn, "COUNT(*)");
		
        $countJSON = $this->Select($tableName, $countColumn, "", $whereColumns, $whereValues, "", "");

      //  $rowsCount = json_decode($countJSON, true)[0]["COUNT(*)"];

        $rowsCount = json_decode($countJSON, true);
      //  echo '<pre>'; print_r($rowsCount->{'0'}); echo '</pre>';
        return $rowsCount[0]["COUNT(*)"];
    }
	
	function FuzzySelectCount($tableName, $whereColumns, $whereValues)
    {
        $countColumn = array();
        array_push($countColumn, "COUNT(*)");

        $countJSON = $this->FuzzySelect($tableName, $countColumn, "", $whereColumns, $whereValues, "", "");

       // $rowsCount = json_decode($countJSON, true)[0]["COUNT(*)"];
        $rowsCount = json_decode($countJSON, true);
        return $rowsCount[0]["COUNT(*)"];
    }

    /**
     * 新增。
     * 
     * @param   string  $tableName      資料表名稱。
     * @param   Array   $columns        欄位。
     * @param   Array   $values         值，需與@columns位置對應。
     * 
     * @return  bool    True on success or False on failure
     */
    function Insert($tableName, $columns, $values)
    {
        // 取得連線
        $dbConnection = $this->connection;

        if(count($columns) != count($values))
            return;

        // 設定資料表
        $sql = "INSERT INTO " . $tableName . " (";

        // 加入欄位
        $columnsArray = array();
        foreach($columns as $column)
        {
            array_push($columnsArray, $column);
        }
        $sql .= implode(", ", $columnsArray);
        $sql .= ") ";

        // 加入值
        $sql .= "VALUES (";
        $valuesArray = array();
        foreach($values as $value)
        {
            array_push($valuesArray, "?");
        }
        $sql .= implode(", ", $valuesArray);
        $sql .= ") ";
        
        // Prepared Statement
        $statement = $dbConnection->prepare($sql);
        // 參數化搜尋，帶入whereValues
        $success = $statement->execute($values);
        
        // 回傳是否寫入成功
        return $success;
    }

    /**
     * 更新。
     * 
     * @param   string  $tableName       資料表名稱。
     * @param   Array   $setColumns         更新的欄位。
     * @param   Array   $setValues       更新的值，需與@columns陣列位置對應。
     * @param   Array   $whereColumns    Where中搜尋的欄位，需與Where相關陣列位置對應。
     * @param   Array   $whereValues     Where中搜尋的值，需與Where相關陣列位置對應。
     * 
     * @return  bool    True on success or False on failure
     */
    function Update($tableName, $setColumns, $setValues, $whereColumns, $whereValues)
    {
        // 取得連線
        $dbConnection = $this->connection;
        
        // 檢查參數資訊
        if(count($setColumns) != count($setValues))
        {
            return;
        }
        else if(count($whereColumns)==0 || (count($whereColumns) != count($whereValues)))
        {
            return;
        }

        // 設定資料表
        $sql = "UPDATE " . $tableName . " SET ";
        
        // process與加入欲修改的欄位與數值
        $columnPairArray = array();
        foreach($setColumns as $column)
        {
            $columnPair = $column . "=?";
            array_push($columnPairArray, $columnPair);
        }
        $sql .= implode(", ", $columnPairArray);
        $sql .= " ";

        // process Where條件
        $sql .= "WHERE ";
        $whereConditions = array();

        // 條件以參數化查詢形式
        foreach($whereColumns as $column)
        {
            $condition = $column . "=?";
            array_push($whereConditions, $condition);
        }
        $sql .= implode(" AND ", $whereConditions);

        $parameters = array_merge($setValues, $whereValues);
        
        // Prepared Statement
        $statement = $dbConnection->prepare($sql);
        // 參數化搜尋，帶入
      //  print_r($parameters);
        $success = $statement->execute($parameters);
        
        // 回傳是否成功
        return $success;
    }

    /**
     * 刪除。
     * 
     * @param   string  $tableName       資料表名稱。
     * @param   Array   $whereColumns    Where中搜尋的欄位，需與Where相關陣列位置對應。
     * @param   Array   $whereValues     Where中搜尋的值，需與Where相關陣列位置對應。
     * 
     * @return  bool    True on success or False on failure
     */
    function Delete($tableName, $whereColumns, $whereValues)
    {
        // 取得連線
        $dbConnection = $this->connection;

        // 檢查參數資訊
        if(!count($whereColumns) && (count($whereColumns) != count($whereValues)))
        {
            return;
        }

        // 設定資料表
        $sql = "DELETE FROM " . $tableName . " ";

        // process WHERE
        $sql .= "WHERE ";
        $whereConditions = array();

        // 條件以參數化查詢形式
        foreach($whereColumns as $column)
        {
            $condition = $column . "=?";
            array_push($whereConditions, $condition);
        }
        $sql .= implode(" AND ", $whereConditions);
        
        // Prepared Statement
        $statement = $dbConnection->prepare($sql);
        // 參數化搜尋，帶入whereValues
        $success = $statement->execute($whereValues);

        // 回傳是否成功
        return $success;
    }
	
	/**
     * 直接下Command。
     * 
     * @param   string  $sql       SQL Command。
     * 
     * @return  bool    True on success or False on failure
     */
	function OpenSQL($sql)
	{
		// 取得連線
        $dbConnection = $this->connection;
		
		$statement = $dbConnection->prepare($sql);
		
        // 參數化搜尋，帶入whereValues
        $success = $statement->execute();
		
		// 回傳是否成功
        return $success;
	}
}
