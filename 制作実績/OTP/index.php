<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
		<title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
    	<div class="hero-unit" style="width:500px; height:100px; position:relative; margin:auto; top:100px; text-align:center; background: -webkit-gradient(linear, left top, left bottom, color-stop(1.00, #f6f7f7), color-stop(0.98, #b7b9c5), color-stop(0.00, #f6f7f7));
background: -webkit-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -moz-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -o-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -ms-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%); -webkit-border-radius:20px;">
			OTP生成<br/>
            <form action="index.php" method="post">
            ユーザID:<input type="text" name="id" ><br />
            <span style="position:relative; right:5px;">Password:<input type="password" name="pass"></span><br />
            <input type="submit" value="送信" class="btn btn-primary">
            </form>
        </div>
		<?php
			include_once ("Crypt/Blowfish.php");
			require_once("class/DatabaseClass.php");
			require_once("class/DecryptClass.php");
			
			if(isset($_POST["id"]))
			{
				$id = htmlspecialchars($_POST["id"],ENT_QUOTES);
				$pass = htmlspecialchars($_POST["pass"],ENT_QUOTES);
				
				$con = Database::Connect();
				$sql = "SELECT * FROM auth WHERE UserName = '$id'";
				$result = Database::Execute($con,$sql);
				$count = Database::CountRows($result);
				if($count=="1")
				{
					while($data = mysqli_fetch_array($result))
					{
						$key = md5($pass); 							//入力値をMD5化
						$authpass = Decrypt::BlowDecrypt($key,$data["UserPass"]);
						
						if($authpass == $pass)
						{
							session_start();
							$_SESSION["id"] = md5($id);
							$_SESSION["pass"] = $key;
							header("Location:output.php");
						}
						else
						{
							echo "Failed";
						}
						
					}

				}
				else
				{
					echo "Failed";
				}
			}
		?>
	</body>
</html>