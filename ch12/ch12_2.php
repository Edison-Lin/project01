<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>ch12_1.php</title>
</head>
<body>
<?php
class Student { // 宣告Student類別
   public $Id;      // 成員變數
   public $Name;
   function __construct($studentId, $studentName) //建構子，物件的初始函數
   {
    $this->Id=$studentId;
    $this->Name=$studentName;}
   // 成員方法
   function register() {   // 註冊
     echo $this->Name."註冊成功<br>";
   }
   function leaveform() { // 請假
     echo $this->Name."請假成功<br>";
   }   

}
class specialStudent extends Student {
    public function subsidy() 
       {
         // echo parent::leaveform();  
            echo $this->Name."補助成功";
          }
}

$objStudent = new specialStudent("s1234","詹xx");  // 建立物件


$objStudent->leaveform();
$objStudent->subsidy();

?>
</body>
</html>