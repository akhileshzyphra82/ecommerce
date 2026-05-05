<?php
ini_set('display_errors', 1);

require_once __DIR__ . '/../common/functions.php';
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
require_once __DIR__ . '/../controller/website_controller.php';
$controller = new WebsiteController();

switch($action)
{	
	case "Insert":
		$name = trim($_POST['authFullName'] ?? '');
        $email = strtolower(trim($_POST['authEmail'] ?? ''));
        $phone_code = trim($_POST['phone_code'] ?? '');
        $phone = trim($_POST['authPhone'] ?? '');
        $password = (string)($_POST['authPassCreate'] ?? '');
        $confirmPassword = (string)($_POST['authPassConfirm'] ?? '');

        if ($name === '' || $email === '' || $phone_code === '' || $phone === '' || $password === '' || $confirmPassword === '')
        {
            header("location:index?urlstring=".EncryptURL("action=&type=warn&msg=Please fill all required fields."));
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            header("location:index?urlstring=".EncryptURL("action=&type=warn&msg=Please enter a valid email address."));
            exit();
        }

        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password))
        {
            header("location:index?urlstring=".EncryptURL("action=&type=warn&msg=Password must be at least 8 characters and include letters numbers and special characters."));
            exit();
        }

        if ($password !== $confirmPassword)
        {
            header("location:index?urlstring=".EncryptURL("action=&type=warn&msg=Passwords do not match. Please try again."));
            exit();
        }

        if ($controller->isEmailRegistered($email))
        {
            header("location:index?urlstring=".EncryptURL("action=&type=warn&msg=This email is already registered. Please sign in."));
            exit();
        }

        $arrUserData = array(
            "user_type_id" => 2,
            "name" => $name,
            "communication_email_id" => $email,
            "communication_mobile_num_isd" => (int)$phone_code,
            "communication_mobile_num" => preg_replace('/[^0-9]/', '', $phone),
            "erp_password" => $password
        );

        $result = $controller->InsertUserFromWebsite($arrUserData);
        if ((int)$result > 0)
        {
            header("location:index?urlstring=".EncryptURL("action=&type=ok&msg=Registration successful. Please sign in.&userId=".$result));
            exit();
        }

        header("location:index?urlstring=".EncryptURL("action=&type=err&msg=Registration failed. Please try again."));
        exit();
	break;
	
}


?>
