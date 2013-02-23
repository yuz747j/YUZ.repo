<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
		<title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body style="text-align:center;">
    	<div class="hero-unit" style="width:500px; height:100px; position:relative; margin:auto; top:100px; text-align:center; background: -webkit-gradient(linear, left top, left bottom, color-stop(1.00, #f6f7f7), color-stop(0.98, #b7b9c5), color-stop(0.00, #f6f7f7));
background: -webkit-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -moz-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -o-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -ms-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%); -webkit-border-radius:20px;">
            <form action="auth.php" method="post">
            Password:<input type="password" name="pass"><br />
            <input type="submit" value="送信" class="btn btn-primary">
            </form>
        </div>
		<?php
			include_once ("Crypt/Blowfish.php");
			require_once("class/DatabaseClass.php");
			require_once("class/EncryptClass.php");
			require_once("class/DecryptClass.php");
			if(isset($_POST["pass"]))
			{
				if($_POST["pass"]!="")
				{
					$pass = htmlspecialchars($_POST["pass"]);
					
					$data = explode ("_",$pass);
					
					$key=$data[0];
					$res=$data[1];
					
					$con = Database::Connect();
					$sql = "SELECT * FROM auth WHERE MD5(UserName) = '$key'";
					$result = Database::Execute($con,$sql);
					$count = Database::CountRows($result);
					if($count=="1")
					{
						while($seed = mysqli_fetch_array($result))
						{
							if($seed["Used"]!=1)
							{
								
								$authdata = Decrypt::RandBlowDecrypt($key,$res,$seed["P_Seed"]);//復号化
											
								if($authdata == md5($res))
								{
									$con = Database::Connect();
									
									
									
									$sql = "SELECT MAX(LogID) as LogID ,PublishDate ,AuthUser FROM log WHERE LogID=(SELECT MAX(LogID) from log) AND MD5(AuthUser) = '$key'";
									$result = Database::Execute($con,$sql);
									while($data = mysqli_fetch_array($result))
									{
										
										$logID = $data["LogID"];
										$logDate = date($data["PublishDate"]);
										$time = date('Y-m-d H:i:s',strtotime("-1 day"));
										$pretime = new DateTime($time);
										$logtime = new DateTime($logDate);
										
										if($pretime <= $logtime)
										{
											$sql = "UPDATE auth set Used = 1 WHERE MD5(UserName) = '$key'";
											Database::Execute($con,$sql);
											$sql = "UPDATE log set UsedDate = '$time' WHERE LogID = '$logID'";
											Database::Execute($con,$sql);
											header("Location:cmpauth.php");
										}
									
									}
									
									echo "<span style=\" position:relative; top:100px;\">パスワードの有効期限が切れています。再発行してください</span>";
									
								}
								else
								{
									echo "<span style=\" position:relative; top:100px;\">このパスワードは使用出来ません。</span>";
								}
							}
							else
							{
								echo "<span style=\" position:relative; top:100px;\">このパスワードはすでに使われています。再発行してください。</span>";
							}
							
						}
	
					}
					else
					{
						echo "<span style=\" position:relative; top:100px;\">このパスワードは使用出来ません。</span>";
					}
				}
				else
				{
					echo "<span style=\" position:relative; top:100px;\">読み込みに失敗している可能性があります。<br>もう一度読み込ませてください。</span>";
				}
			}
		?>
	</body>
</html>