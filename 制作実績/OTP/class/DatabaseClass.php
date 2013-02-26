<?php
class Database {

	/*
	 * データベースへの接続
	 */
	static function Connect()
	{
		$con = mysqli_connect('localhost', 'root', '', 'otp');
		if(!$con)
		{
			return false;
		}
		$sql = "SET NAMES utf8";
		mysqli_query($con, $sql);
		return $con;
	}


	/*
	 * データベースのクローズ
	 */
	static function Close($con)
	{
		mysqli_close($con);
	}


	/*
	 * SQLの実行
	 */
	static function Execute($con, $sqlString)
	{
		$result = mysqli_query($con, $sqlString);
		if(!$result)
		{
			return false;
		}
		return $result;
	}


	/*
	 * 取得したレコード数を返す
	 */
	static function CountRows($result)
	{
		if($result != null)
		{
			$count = mysqli_num_rows($result);

			if(!$count)
			{
				$count = false;
			}
		}
		else
		{
			$count = false;
		}
		
		return $count;
	}



}

?>
