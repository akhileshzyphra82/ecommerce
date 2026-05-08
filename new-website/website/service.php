<?php
ini_set('display_errors', 1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../common/functions.php';
function redirectWithFlash(string $target, string $type, string $message, string $extra = ''): void
{
    sinelec_set_flash($type, $message);
    $location = $target;
    if ($extra !== '') {
        $location .= '?' . ltrim($extra, '?&');
    }
    header("location:{$location}");
    exit();
}

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
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
            redirectWithFlash('index', 'warn', 'Please fill all required fields.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            redirectWithFlash('index', 'warn', 'Please enter a valid email address.');
        }

        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password))
        {
            redirectWithFlash('index', 'warn', 'Password must be at least 8 characters and include letters numbers and special characters.');
        }

        if ($password !== $confirmPassword)
        {
            redirectWithFlash('index', 'warn', 'Passwords do not match. Please try again.');
        }

        if ($controller->isEmailRegistered($email))
        {
            redirectWithFlash('index', 'warn', 'This email is already registered. Please sign in.');
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
            redirectWithFlash('index', 'ok', 'Registration successful. Please sign in.', 'userId=' . $result);
        }

        redirectWithFlash('index', 'err', 'Registration failed. Please try again.');
	break;

    case "Login":
        $username = strtolower(trim($_POST['authUserId'] ?? ''));
        $password = (string)($_POST['authPassword'] ?? '');

        if ($username === '' || $password === '')
        {
            redirectWithFlash('index', 'warn', 'Please enter your email and password.');
        }

        $user = $controller->loginUser([
            'username' => $username,
            'password' => $password,
        ]);

        if (!empty($user) && isset($user['user_id']))
        {
            session_regenerate_id(true);
            $_SESSION['sinelec_user'] = [
                'USER_ID' => (int)$user['user_id'],
                'NAME' => (string)($user['name'] ?? ''),
                'EMAIL' => (string)($user['email'] ?? ''),
                'USER_TYPE_ID' => (int)($user['user_type_id'] ?? 0),
                'COMMUNICATION_MOBILE_NUM_ISD' => (int)($user['communication_mobile_num_isd'] ?? 0),
                'COMMUNICATION_MOBILE_NUM' => (int)($user['communication_mobile_num'] ?? 0)  ,
                'COMPANY_NAME' => (string)($user['company_name'] ?? ''),
                'DESIGNATION' => (string)($user['designation'] ?? ''),
                'IS_PWD_UPDATED' => (bool)($user['is_pwd_updated'] ?? false)
            ];

            redirectWithFlash('index', 'ok', 'Signed in successfully.');
        }

        redirectWithFlash('index', 'err', 'Invalid email or password.');
    break;

    case "ChangePassword":
        $userId = (int)($_SESSION['sinelec_user']['USER_ID'] ?? 0);
        $currentPassword = (string)($_POST['current_password'] ?? '');
        $newPassword = (string)($_POST['new_password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');
        $passwordRule = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/';

        if ($userId <= 0) {
            redirectWithFlash('index', 'warn', 'Please sign in to continue.');
        }

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            redirectWithFlash('change-password', 'warn', 'Please fill all password fields.');
        }

        if (!preg_match($passwordRule, $newPassword)) {
            redirectWithFlash('change-password', 'warn', 'New password must be at least 8 characters and include letters numbers and special characters.');
        }

        if ($newPassword !== $confirmPassword) {
            redirectWithFlash('change-password', 'warn', 'New password and confirm password do not match.');
        }

        if ($currentPassword === $newPassword) {
            redirectWithFlash('change-password', 'warn', 'New password must be different from current password.');
        }

        $changeErrorCode = '';
        if ($controller->changeUserPassword($userId, $currentPassword, $newPassword, $changeErrorCode)) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
            session_start();
            redirectWithFlash('index', 'ok', 'Password updated successfully. Please login again.');
        }

        switch ($changeErrorCode) {
            case 'current_password_invalid':
                redirectWithFlash('change-password', 'err', 'Current password is incorrect.');
                break;
            case 'same_as_current':
                redirectWithFlash('change-password', 'warn', 'New password must be different from current password.');
                break;
            default:
                redirectWithFlash('change-password', 'err', 'Unable to update password right now. Please try again.');
                break;
        }
    break;

    case "Logout":
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_start();
        redirectWithFlash('index', 'ok', 'Signed out successfully.');
    break;

    case "UpdateProfile":
        $userId = (int)($_SESSION['sinelec_user']['USER_ID'] ?? 0);
        if ($userId <= 0) {
            redirectWithFlash('index', 'warn', 'Please sign in to continue.');
        }

        $name = trim((string)($_POST['profile_name'] ?? ''));
        $phoneCode = preg_replace('/[^0-9]/', '', (string)($_POST['profile_phone_code'] ?? ''));
        $number = preg_replace('/[^0-9]/', '', (string)($_POST['profile_number'] ?? ''));
        $company = trim((string)($_POST['profile_company'] ?? ''));
        $designation = trim((string)($_POST['profile_designation'] ?? ''));

        if ($name === '' || $phoneCode === '' || $number === '') {
            redirectWithFlash('profile', 'warn', 'Name, phone code, and number are required.');
        }

        if (strlen($number) < 6) {
            redirectWithFlash('profile', 'warn', 'Please enter a valid mobile number.');
        }

        $updated = $controller->updateUserProfile([
            'user_id' => $userId,
            'name' => $name,
            'communication_mobile_num_isd' => (int)$phoneCode,
            'communication_mobile_num' => $number,
            'company_name' => $company,
            'designation' => $designation,
        ]);

        if ($updated) {
            $_SESSION['sinelec_user']['NAME'] = $name;
            $_SESSION['sinelec_user']['COMMUNICATION_MOBILE_NUM_ISD'] = (int)$phoneCode;
            $_SESSION['sinelec_user']['COMMUNICATION_MOBILE_NUM'] = $number;
            $_SESSION['sinelec_user']['COMPANY_NAME'] = $company;
            $_SESSION['sinelec_user']['DESIGNATION'] = $designation;
            redirectWithFlash('profile', 'ok', 'Profile updated successfully.');
        }

        redirectWithFlash('profile', 'err', 'Unable to update profile right now. Please try again.');
    break;
	
}


?>
