<?php
			include_once ("Crypt/Blowfish.php");
			include_once("Image/QRCode.php"); 
			include_once("phpmailer/class.phpmailer.php");
			require_once("class/DatabaseClass.php");
			require_once("class/EncryptClass.php");
			require_once("class/DecryptClass.php");
			
			session_start();
			if(isset($_SESSION["id"]))
			{
				
				
				$id = $_SESSION["id"];
				$key = $_SESSION["id"];
				$res = "";
				
				$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
				for($i=0; $i < 8; $i++)
				{
					//変数に入った文字列からランダムに1文字を抜き出して「$res」に追記
					$res .= $chars{mt_rand(0, strlen($chars)-1)};
				}
				
				
				$encrypt_char = Encrypt::RandBlowEncrypt($key,$res,md5($res)); //キーと初期化ベクトルを与えて暗号化する				
				
				$con = Database::Connect();
				$sql = "UPDATE auth set P_Seed = '$encrypt_char',Used = 0 WHERE MD5(UserName) = '$id'";
				Database::Execute($con,$sql);
				
				$qrdata = $id."_".$res;
				
				header("Content-type:image/png;");
				$qr = new Image_QRCode();
				$image = $qr->makeCode($qrdata,array("output_type" => "return"));
				imagepng($image,"./downloads/qr.png");
					
				// 宛て先アドレス復号
				$mailTo="";
				$sql = "SELECT * FROM auth WHERE MD5(UserName) = '$id'";
				$result = Database::Execute($con,$sql);
				$count = Database::CountRows($result);
				if($count=="1")
				{
					while($data = mysqli_fetch_array($result))
					{
						$key = $_SESSION["pass"];
						$mailTo = Decrypt::BlowDecrypt($key,$data["UserEmail"]);
				
					}
				}
				 
				 
				// メールのタイトル
				$mailSubject = 'OTP発行メール';
				 
				// メール本文
				$mailMessage = 'こちらのQRコードをお使いください';
				 
				// 添付するファイル
				$fileName    = "./downloads/qr.png";
				 
				// 差出人のメールアドレス
				$mailFrom    = 'yuz707j@gmail.com';
				 
				// Return-Pathに指定するメールアドレス
				$returnMail  = 'yuz707j@gmail.com';
				 
				// メールで日本語使用するための設定
				mb_language("Ja") ;
				mb_internal_encoding("UTF-8");
				 
				$header  = "From: $mailFrom\r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-Type: multipart/mixed; boundary=\"__PHPRECIPE__\"\r\n";
				$header .= "\r\n";
				 
				$body  = "--__PHPRECIPE__\r\n";
				$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n";
				$body .= "\r\n";
				$body .= $mailMessage . "\r\n";
				$body .= "--__PHPRECIPE__\r\n";
				 
				// 添付ファイルへの処理
				$handle = fopen($fileName, 'r');
				$attachFile = fread($handle, filesize($fileName));
				fclose($handle);
				$attachEncode = base64_encode($attachFile);
				 
				$body .= "Content-Type: image/jpeg; name=\"$file\"\r\n";
				$body .= "Content-Transfer-Encoding: base64\r\n";
				$body .= "Content-Disposition: attachment; filename=\"$file\"\r\n";
				$body .= "\r\n";
				$body .= chunk_split($attachEncode) . "\r\n";
				$body .= "--__PHPRECIPE__--\r\n";
				 
				// メールの送信と結果の判定
				if (ini_get('safe_mode'))
				{
				 $result = mb_send_mail($mailTo, $mailSubject, $body, $header);
				} 
				else 
				{
				 $result = mb_send_mail($mailTo, $mailSubject, $body, $header,'-f' . $returnMail);
				}
				 
				if($result)
				{
						//発行ログ登録
						$sql = "SELECT * FROM auth WHERE MD5(UserName) = '$id'";
						$result = Database::Execute($con,$sql);
						$count = Database::CountRows($result);
						if($count=="1")
						{
							while($data = mysqli_fetch_array($result))
							{
								$user = $data["UserName"];
								$time = date('Y-m-d H:i:s');
								$insert = "INSERT INTO log (LogID,AuthUser,PublishDate) VALUES(0,'$user','$time')";
								$result = Database::Execute($con,$insert);
							}
						}
					  header("Location:cmpoutput.php");
				}
				else
				{
					   echo '<p>送信失敗</p>';
				}
				
				

			}
			
		?>
