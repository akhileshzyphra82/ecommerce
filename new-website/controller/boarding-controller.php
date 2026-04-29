<?php

require_once __DIR__ . '/../config/db_helper.php';

class BoardingController {
    private $dbHelper;

    public function __construct() {
        $this->dbHelper = new MySQLDB();
    }

    public function getEnquiry() {
        $query = "SELECT * FROM enquiry ";
        $arrEnquiry = $this->dbHelper->select($query);
        return $arrEnquiry;
    }

   

public function loginUser($postData) 
{
    try {
        // Prepare SQL using placeholders
        $query = "SELECT * FROM admins WHERE 
        email='".$postData['username']."' AND pwd='".$postData['password']."'"; 
        $arrUserData = $this->dbHelper->select($query);
        return $arrUserData;

    } catch (Exception $e) {
        // Log the error (never show raw SQL errors to the user)
        error_log("Login error: " . $e->getMessage());

        // Return a controlled error response
        return [
            "status" => false,
            "message" => "Something went wrong during login. Please try again."
        ];
    }
}


public function updateMenuData($arrUpdateData) 
{
    try {
        $intRowsUpdated = 0;

        foreach ($arrUpdateData as $menuData) {
                $query = "UPDATE tbl_menu SET 
                        menu_name = '".$menuData['MENU_NAME']."',
                        priority = '".$menuData['PRIORITY']."'
                        WHERE menu_id = '".$menuData['MENU_ID']."' 
                        AND language = '".$menuData['LANGUAGE']."'";

            $intRows = $this->dbHelper->update($query);
            $intRowsUpdated ++;
        }

        return $intRowsUpdated; 

    } catch (Exception $e) {
        // Log the error (never show raw SQL errors to the user)
        error_log("Update Menu error: " . $e->getMessage());

        // Return a controlled error response
        return [
            "status" => false,
            "message" => "Something went wrong during menu update. Please try again."
        ];
    }
}   




  

public function updateHeroSectionData($arrUpdateData)
{
    try {

        $intRowsUpdated = 0;

        foreach ($arrUpdateData as $homeData) {

            // ---------- Sanitize text values ----------
            foreach ($homeData as $key => $value) {
                if (is_string($value)) {
                    // Replace ' and " with `
                    $homeData[$key] = str_replace(
                        ["'", '"'],
                        "`",
                        trim($value)
                    );
                }
            }

            $homeId   = $homeData['HOME_ID'] ?? '';
            $language = $homeData['LANGUAGE'];

            /* ================= INSERT ================= */
            if (empty($homeId)) {

                $query = "INSERT INTO tbl_home (
                            hero_label,
                            hero_title,
                            hero_description,
                            rooms_title,
                            rooms_description,
                            price_title,
                            price_description,
                            faq_title,
                            faq_description,
                            contact_title,
                            contact_description,
                            enquiry_button_name,
                            footer_left_heading,
                            footer_middle_heading,
                            footer_right_heading,
                            language
                        ) VALUES (
                            '".$homeData['HERO_LABEL']."',
                            '".$homeData['HERO_TITLE']."',
                            '".$homeData['HERO_DESCRIPTION']."',
                            '".$homeData['ROOMS_TITLE']."',
                            '".$homeData['ROOMS_DESCRIPTION']."',
                            '".$homeData['PRICE_TITLE']."',
                            '".$homeData['PRICE_DESCRIPTION']."',
                            '".$homeData['FAQ_TITLE']."',
                            '".$homeData['FAQ_DESCRIPTION']."',
                            '".$homeData['CONTACT_TITLE']."',
                            '".$homeData['CONTACT_DESCRIPTION']."',
                            '".$homeData['ENQUIRY_BUTTON_NAME']."',
                            '".$homeData['FOOTER_LEFT_HEADING']."',
                            '".$homeData['FOOTER_MIDDLE_HEADING']."',
                            '".$homeData['FOOTER_RIGHT_HEADING']."',
                            '".$language."'
                        )";

                $intRows = $this->dbHelper->insert($query);
                if ($intRows > 0) {
                    $intRowsUpdated++;
                }

            } 
            /* ================= UPDATE ================= */
            else {

                $query = "UPDATE tbl_home SET 
                            hero_label = '".$homeData['HERO_LABEL']."',
                            hero_title = '".$homeData['HERO_TITLE']."',
                            hero_description = '".$homeData['HERO_DESCRIPTION']."',
                            rooms_title = '".$homeData['ROOMS_TITLE']."',
                            rooms_description = '".$homeData['ROOMS_DESCRIPTION']."',
                            price_title = '".$homeData['PRICE_TITLE']."',
                            price_description = '".$homeData['PRICE_DESCRIPTION']."',
                            faq_title = '".$homeData['FAQ_TITLE']."',
                            faq_description = '".$homeData['FAQ_DESCRIPTION']."',
                            contact_title = '".$homeData['CONTACT_TITLE']."',
                            contact_description = '".$homeData['CONTACT_DESCRIPTION']."',
                            enquiry_button_name = '".$homeData['ENQUIRY_BUTTON_NAME']."',
                            footer_left_heading = '".$homeData['FOOTER_LEFT_HEADING']."',
                            footer_middle_heading = '".$homeData['FOOTER_MIDDLE_HEADING']."',
                            footer_right_heading = '".$homeData['FOOTER_RIGHT_HEADING']."'
                          WHERE home_id = '".$homeId."'
                          AND language = '".$language."'";

                $this->dbHelper->update($query);
                $intRowsUpdated++;
            }
        }

        return $intRowsUpdated;

    } catch (Exception $e) {

        error_log("Update Home error: " . $e->getMessage());

        return [
            "status"  => false,
            "message" => "Something went wrong during home update. Please try again."
        ];
    }
}







public function deleteFAQData($faqId)
{
    try 
    {
        $query = "DELETE FROM tbl_faq WHERE faq_id = '" . intval($faqId) . "'";
        $intRows = $this->dbHelper->update($query);
        return $intRows;

    } 
    catch (Exception $e) 
    {
        error_log("Delete FAQ error: " . $e->getMessage());
        return [
            "status" => false,
            "message" => "Something went wrong during FAQ deletion. Please try again."
        ];
    }   


}



    
}   
?>