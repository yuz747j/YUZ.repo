// JavaScript Document
function checkValue()
{
	var id = form1.id.value;
	var pass1 = form1.pass1.value;
	var pass2 = form1.pass2.value;
	var mail1 = form1.mail1.value;
	var mail2 = form1.mail2.value;
	
	
	if(id != "" && pass1 != "" &&pass2 != "" && mail1 != "" && mail2 != "")
	{
		if(pass1 != pass2 && mail1 != mail2)
		{
			alert("入力されたパスワードと\nメールアドレスが一致していません!");
			return false;
		}
		else if (mail1 != mail2)
		{
			alert("入力されたメールアドレスが一致していません!");
			return false;
		}
		else if(pass1 != pass2)
		{
			alert("入力されたパスワードが一致していません!");
			return false;
		}
		return true;
	}
	else
	{
		alert("入力項目に不備があります。");
		return false;
	}
	
}