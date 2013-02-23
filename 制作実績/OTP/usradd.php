<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
		<title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
        <script src="js/common.js"></script>
	</head>
	<body style="text-align:center;">
        <div class="hero-unit" style="width:500px; height:200px; position:relative; margin:auto; top:100px; text-align:center; background: -webkit-gradient(linear, left top, left bottom, color-stop(1.00, #f6f7f7), color-stop(0.98, #b7b9c5), color-stop(0.00, #f6f7f7));
background: -webkit-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -moz-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -o-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: -ms-linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%);
background: linear-gradient(top, #f6f7f7 0%, #b7b9c5 98%, #f6f7f7 100%); -webkit-border-radius:20px;">
			<div  style="position:relative; top:-30px;">
            <h2>ユーザ登録</h2>
            <form method="post" action="usradd.php" id="form1">
            新規ID:<input type="text" name="id" id="id"><br/>
            <span style=" position:relative; right:14px;">パスワード:<input type="password" name="pass1" id="pass1"></span><br/>
            <span style=" position:relative; right:37px;">パスワード(確認):<input type="password" name="pass2" id="pass2"></span><br/>
            <span style=" position:relative; left:2px;">E-mail:<input type="email" name="mail1" id="mail1"></span><br/>
            <span style=" position:relative; right:21px;">E-mail(確認):<input type="email" name="mail2" id="mail2"></span><br/>
            <input type="submit" name="submit" value="登録" class="btn btn-primary" onClick="return checkValue();">
            </form>
            </div>
            </div>
        
        
        <?php
			require_once("class/DatabaseClass.php");
			require_once("class/EncryptClass.php");
			require_once("class/DecryptClass.php");
			
			if(isset($_POST["id"]))
			{
				$id = htmlspecialchars($_POST["id"]);
				
				$con = Database::Connect();
				
				$sql = "select * from auth where UserName = '$id'";
				
				$result = Database::Execute($con,$sql);
				$num = Database::CountRows($result);
				if($num >= 1)
				{
					echo "<span style=\"color:red\">このユーザ名は既に使われています。</span>";
				}
				else
				{
					$pass = htmlspecialchars($_POST["pass1"]);
					$encpass = Encrypt::BlowEncrypt(md5($pass),$pass);
					
					$email = htmlspecialchars($_POST["mail1"]);
					$encemail = Encrypt::BlowEncrypt(md5($pass),$email);
					
					
					$sql = "insert into auth(UserName,UserPass,UserEmail) values('$id','$encpass','$encemail')";
					Database::Execute($con,$sql);
					
					header("Location:addcmp.php");
					
					
				}
			}
		?>
	</body>
</html>