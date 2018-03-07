/**
 * Password Strength:Confirm the level of password(0 null,1 low,2 middle,3 high).
 *		密码强度：确定密码的水平（0 空，1  低，2 中，3 高）。
 * @param num 
 * @return number modes
 */
function bitTotal(num)
{
	modes=0;
	for(i=0;i<5;i++)
	{
	   	if (num & 1) modes++;
	   	num>>>=1;
	}
	return modes;
}

/**
 * verify the password and sure the password strength.
 * 验证密码的强度。
 * @param pwd
 * @return L(low) or M(middle) or H(high) or N(null)
 */
function checkPwdStrength(pwd)
{
	var msg = '';
	pwd_level = checkStrong(pwd);
	switch(pwd_level)
	{
		case 1:
			msg = 'L';
			break;
		case 2:
			msg = 'M';
			break;
		case 3:
		case 4:
			msg = 'H';
			break;
		default:
			msg = 'N';
	}
	return msg;
}
/**
 * Password Strength:Split the password, confirm the character range.
 *		密码强度：拆分密码串，确定每个字符的范围
 * @param sPW
 * @return number : password level
 */
function checkStrong(sPW)
{
	if (sPW.length<=5)
   	return 0; //密码太短
	Modes=0;
	for (i=0;i<sPW.length;i++)
	{
		Modes|=charMode(sPW.charCodeAt(i));
	}
	return bitTotal(Modes);
}


/**
 * Password Strength:Confirm the scope of input character(Number,Lowercase,Uppercase,Special).
 * 	密码强度：确定输入字符的范围（数字，小写字符，大写字符，特殊字符）。
 * @param iN
 * @return number
 */
function charMode(iN)
{
	if(iN>=48 && iN <=57) //数字
		return 1;
	if(iN>=65 && iN <=90) //大写字母
		return 2;
	if(iN>=97 && iN <=122) //小写
		return 4;
	else
		return 8; //特殊字符
}