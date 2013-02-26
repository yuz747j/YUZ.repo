<?php
	class Encrypt
	{
		
		static function BlowEncrypt($key,$EncryptData)
		{
			include_once ("Crypt/Blowfish.php");
			
			$blowfish = new Crypt_Blowfish($key);		//md5値を元に暗号、復号設定
			$data = $EncryptData;						//DBからパステキスト取得
			$data = $blowfish->encrypt($data);			//復号化
			$data = base64_encode($data);				//バイナリデータに変換
			
			return $data;

		}
		
		static function RandBlowEncrypt($key,$bktr,$EncryptData)
		{
			include_once ("Crypt/Blowfish.php");
			
			// CBCにて暗号化（キーと初期化ベクトルを引数に与える）
			$blowfish = Crypt_Blowfish::factory( 'cbc', $key , $bktr );
			$EncryptedData = $blowfish->encrypt($EncryptData);
			
			// このままだとバイナリデータなのでbase64で文字列化
			$EncryptedData = base64_encode( $EncryptedData );
			
			return $EncryptedData;
		}
	}
?>