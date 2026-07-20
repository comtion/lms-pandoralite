<?php
	/**** Class Database ****/
	Class MyDatabase
	{
		/**** function connect to database ****/
		function MyDatabase($strHost,$strDB,$strUser,$strPassword)
		{
				$this->objConnect = mysql_connect($strHost,$strUser,$strPassword);
				$this->DB = mysql_select_db($strDB);
		}
		
		function setCollaction(){
				mysql_query("SET character_set_results=utf8") or die('Error query 1: ' . mysql_error());
		
				mysql_query("SET character_set_client = utf8") or die('Error query 2: ' . mysql_error());
		
				mysql_query("SET character_set_connection = utf8") or die('Error query 3: ' . mysql_error());
		}
		/**** function insert record ****/
		function fncInsertRecord() 
		{
				$strSQL = "INSERT INTO $this->strTable ($this->strField) VALUES ($this->strValue)";
				
				

				return @mysql_query($strSQL);
		}
		
		function fncInsertMemberRecord()
		{
				$strSQL = "INSERT INTO $this->strTable ($this->strField) VALUES ($this->strValue) ON DUPLICATE KEY UPDATE $this->strOnUpdate";
				
				//echo $strSQL;

				return @mysql_query($strSQL);
		}

		function fncGetLastId()
		{
				$strSQL = "SELECT CASE WHEN (MAX(id)+1 ) IS NULL THEN 0 ELSE (MAX(id)+1 ) END 'ID' FROM $this->strTable ";
				$objQuery = @mysql_query($strSQL);
				return @mysql_fetch_array($objQuery);
		}

		function fncCheckHas()
		{
				$strSQL = "SELECT * FROM $this->strTable WHERE $this->strCondition ";
				$objQuery = @mysql_query($strSQL);
				return @mysql_num_rows($objQuery);
		}

		/**** function select record ****/
		function fncSelectRecord() //return true,false
		{
				$strSQL = "SELECT * FROM $this->strTable WHERE $this->strCondition ";
				$objQuery = @mysql_query($strSQL);
				//return @mysql_fetch_array($objQuery);
				return @$objQuery;
		}
		
		/**** function select record ****/
		function fncSelectAllRecord()
		{
				$strSQL = "SELECT cus.fullname,cus.phone,cus.email,pac.name,pac.description,cus.c_date FROM $this->strTable cus inner join $this->strTableJoin pac on cus.package=pac.id ";
				$objQuery = @mysql_query($strSQL);
				return $objQuery ;//@mysql_fetch_array($objQuery);
		}

		/**** function update record (argument) ****/
		function fncUpdateRecord()
		{
				$strSQL = "UPDATE $this->strTable SET  $this->strCommand WHERE $this->strCondition ";
				return @mysql_query($strSQL);
		}

		/**** function delete record ****/
		function fncDeleteRecord()
		{
				$strSQL = "DELETE FROM $this->strTable WHERE $this->strCondition ";
				return @mysql_query($strSQL);
		}

		/*** end class auto disconnect ***/
		function __destruct() {
				return @mysql_close($this->objConnect);
	    }
	}			

?>