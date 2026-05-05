<?php
///////////////////////////////////////////////////
//	A Encryption/Decryption CLASS with Rijndael 256	
//	By Ismet Ozalp
//  16.03.2005
//  Please Do not remove this header
///////////////////////////////////////////////////
class CryptedPassword {
  
 	var $mykey = "qwerty!@#$%^YTREWQzxcvbn";
	
  
	function GetEncryptedPass($len){
		$pass = $this->makeRandomPassword($len);
		return $this->LinEncrypt($pass);
	}
	function LinEncrypt($pass) {
	
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); //get vector size on ECB mode 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); //Creating the vector
		$cryptedpass = mcrypt_encrypt (MCRYPT_RIJNDAEL_256, $this->mykey, $pass, MCRYPT_MODE_ECB, $iv); //Encrypting using MCRYPT_RIJNDAEL_256 algorithm 
	return base64_encode($cryptedpass);
	}

	function LinDecrypt($enpass) {
		$enpass = base64_decode($enpass);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decryptedpass = mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $this->mykey, $enpass, MCRYPT_MODE_ECB, $iv); //Decrypting...
	return rtrim($decryptedpass);
	}
	function makeRandomPassword($len) { 
 	 	$salt = "ABCDE0123FGHJKLMNPRSTUVW45XYZabch6efghjkmnpqrs789tuvwxyz"; 
  		srand((double)microtime()*1000000); 
    	for($i = 0;$i < $len;$i++) { 
    		$num = rand() % 56; 
    		$tmp = substr($salt, $num, 1); 
    		$pass = $pass . $tmp; 
    	} 
	return $pass; 
	}
} 
?>