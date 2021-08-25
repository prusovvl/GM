<?php
date_default_timezone_set('Europe/Kiev');
include ($main_path.'func/function.php');
include ("../func/globals.php");


class SQL {



public $GetDB_Connection;
public $mysql_logs_err = "mysql_err";
public $mysqli;



public function Connect($host, $user_base_login, $user_base_pass, $user_base)
{


	
	$this->mysqli = new mysqli($host, $user_base_login, $user_base_pass);
if(!$this->mysqli) die('Could not connect: ' . mysql_error());
mysqli_select_db($this->mysqli, $user_base);
if(!$this->mysqli) die('Could not connect to DB: ' . mysql_error()); 
	$this->mysqli->query("SET CHARACTER SET utf8");
	
}

public function ClearQuery ()
	{
		 while($this->mysqli->next_result()) $this->mysqli->store_result();
	}
	
		public function rollback()
	{
		$this->mysqli->rollback();
	}
	
		public function commit()
	{
		 $this->mysqli->commit();
	}
	
	public function autocommit($Do)
	{
		$this->mysqli->autocommit($Do);
	}
		

public function SQLProcedure ($Query, $err_cntrl = true)
	{
		
		$GLOBALS["sql_err"] = false;
		if ($err_cntrl==true)
	$this->mysqli->autocommit(FALSE);

if ($this->mysqli->multi_query($Query)) {



$_SOBR_ALL_RES = array ();
$z=0;

    do {
        if ($result = $this->mysqli->store_result()) {
		
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			
			 
			// for ($i=0; $i<=count ($row)-1; $i++)
			 //{
$_SOBR_ALL_RES [$z] = $row;
	$z++;
				// }
				 
			 }
		
		mysqli_free_result($result);
		
		} 
		
	
		
	} while (@$this->mysqli->next_result());
	
if ($err_cntrl==true) {
$this->mysqli->commit();
$this->mysqli->autocommit(TRUE);
	}
	return $_SOBR_ALL_RES;	
	$this->GetDB_Connection->connectClose();
}
else {
$this->SendAboutErr ();
	$GLOBALS["sql_err"] = true;
err_log ($this->mysql_logs_err, "SQL error. ".$this->mysqli->error.". Query: '".$Query."', user_id: ".$_SESSION ['user_id'], __FILE__ .":". __LINE__);
$GetRes [0] ['RES'] = "SQL error! See logs";
if ($err_cntrl==true) {
$this->mysqli->rollback();
	$this->mysqli->autocommit(TRUE);
}
return $GetRes;

$this->GetDB_Connection->connectClose();

}
	
	
	}
	
	
	public function SQLQuery ($Query, $err_cntrl = true)
	{
	 $GetQueryRes = $this->mysqli->query($Query);
	 
	
	 
	
	 
	 if (!$GetQueryRes)
	 {
		 
		 
		 $get_err = $this->mysqli->error;
if ($err_cntrl==true)
$this->mysqli->autocommit(FALSE);
		 	
$this->SendAboutErr ();

if ($err_cntrl==true) {
	$this->mysqli->rollback();
	$this->mysqli->autocommit(TRUE);
}



err_log ($this->mysql_logs_err, $get_err.". Query: '".$Query."', user_id: ".$_SESSION ['user_id'], __FILE__ .":". __LINE__);
return "error";



	 }
	 
	 //echo "---".count($GetQueryRes)."****";
	 
		$row = @$GetQueryRes->num_rows;
	 
	 //echo "\n\n\n\n";
	 
	 
	 if ($row==0)
	 	 return $row;
	 

	 
$get_err = $this->mysqli->error;
if ($err_cntrl==true)
$this->mysqli->autocommit(FALSE);

/*$GLOBALS["sql_err"] = false;
if (trim($get_err)!="" and explode (":",$get_err)[0]!="go")
{
	
	
	$GLOBALS["sql_err"] = true;
$this->SendAboutErr ();

if ($err_cntrl==true) {
	$this->mysqli->rollback();
	$this->mysqli->autocommit(TRUE);
}


err_log ($this->mysql_logs_err, $get_err.". Query: '".$Query."', user_id: ".$_SESSION ['user_id'], __FILE__ .":". __LINE__);
return false;



exit;
}*/

//echo explode (":",$get_err)[1];

if (explode (":",$get_err)[0]=="go") $GetQueryRes = explode (":",$get_err)[1];

if ($err_cntrl==true) {
$this->mysqli->commit();
$this->mysqli->autocommit(TRUE);
}
	return $GetQueryRes;
	
	//$this->GetDB_Connection->connectClose();
	
	}

	public function Close()
	{
	mysqli_close($this->mysqli);
	}

public function SendAboutErr()
{

/*$to  = "InfoErr@isecli.com";  

$subject = "Обнаруженая SQL-ошибка"; 

$message = ' 
<html> 
    <head> 
        <title>Обнаруженая SQL-ошибка</title> 
    </head> 
    <body> 
        <p>Обнаруженая SQL-ошибка!<br>
id пользователя иницирующего запрос: '.$_SESSION ['user_id'].'

</p> 
    </body> 
</html>'; 

$headers  = "Content-type: text/html; charset=windows-1251 \r\n"; 
$headers .= "From: iSeCli <noreplay@iSeCli.com>\r\n"; 


mail($to, $subject, $message, $headers); 
*/


}
	
}


