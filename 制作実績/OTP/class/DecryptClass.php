<?php

	class Decrypt
	{
		static function BlowDecrypt($key,$DecryptData)
		{
			include_once ("Crypt/Blowfish.php");
			
			$blowfish = new Crypt_Blowfish($key);		//md5値を元に暗号、復号設定
			$data = $DecryptData;						//DBからパステキスト取得
			$data = base64_decode($data);				//バイナリデータに変換
			$data = $blowfish->decrypt($data);			//復号化
			$data = rtrim($data);						//ゴミの排除
			
			return $data;
		}
		
		
		static function RandBlowDecrypt($key,$bktr,$DecryptData)
		{
			include_once ("Crypt/Blowfish.php");
			
			$blowfish = Crypt_Blowfish::factory( 'cbc', $key , $bktr );
			$authdata = base64_decode($DecryptData);
			$authdata = $blowfish->decrypt($authdata);
			$authdata = rtrim($authdata);
			
			return $authdata;					
		}
	}
?>