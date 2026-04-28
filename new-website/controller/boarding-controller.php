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


    public function getMenuData() 
    {
        try {
            // Prepare SQL using placeholders
            $query = "SELECT * FROM tbl_menu ORDER BY `priority` ASC "; 
            $arrMenuData = $this->dbHelper->select($query);
            return $arrMenuData;

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




    public function getHeroSectionData() 
    {
        try {
            // Prepare SQL using placeholders
            $query = "SELECT * FROM tbl_home"; 
            $arrHomeData = $this->dbHelper->select($query);
            return $arrHomeData;

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




public function getFAQData($id) 
{
    try 
    {
        $whereClause = "";
        if($id>0)
            $whereClause = " WHERE faq_id = ".$id;

        // Prepare SQL using placeholders
        $query = "SELECT * FROM tbl_faq ".$whereClause." ORDER BY `priority` ASC";
        $arrFAQData = $this->dbHelper->select($query);
        return $arrFAQData; 
    
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



public function addUpdateFAQData($arrUpdateData)
{
    try {
        $intRowsUpdated = 0;
        foreach ($arrUpdateData as $faqData) {
            if ($faqData['FAQ_ID'] > 0) {
                // Update existing FAQ
                $query = "UPDATE tbl_faq SET 
                          question = '" . $faqData['QUESTION'] . "',
                          answer = '" . $faqData['ANSWER'] . "',
                          priority = '" . $faqData['PRIORITY'] . "',
                          `language` = '" . $faqData['LANGUAGE'] . "'
                          WHERE faq_id = '" . $faqData['FAQ_ID'] . "'";

                $intRows = $this->dbHelper->update($query);
                $intRowsUpdated++;
            } else {
                // Insert new FAQ
                $query = "INSERT INTO tbl_faq (question, answer, priority, `language`) VALUES (
                          '" . $faqData['QUESTION'] . "', 
                          '" . $faqData['ANSWER'] . "', 
                          '" . $faqData['PRIORITY'] . "', 
                          '" . $faqData['LANGUAGE'] . "')";

                $intRows = $this->dbHelper->insert($query);
                if ($intRows > 0) {
                    $intRowsUpdated++;
                }
            }
        }

        return $intRowsUpdated;

    } catch (Exception $e) {
        error_log("Add/Update FAQ error: " . $e->getMessage());
        return [
            "status" => false,
            "message" => "Something went wrong during FAQ update. Please try again."
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


public function getContactUsData() 
    {
        try {
            // Prepare SQL using placeholders
            $query = "SELECT * FROM tbl_contact"; 
            $arrContactData = $this->dbHelper->select($query);
            return $arrContactData;

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

public function updateContactUsData($arrUpdateData)
{
    try {

        $intRowsUpdated = 0;

        foreach ($arrUpdateData as $contactData) {

            // ---------- Sanitize all string values ----------
            foreach ($contactData as $key => $value) {
                if (is_string($value)) {
                    $contactData[$key] = str_replace(
                        ["'", '"'],
                        "`",
                        trim($value)
                    );
                }
            }

            $contactId = $contactData['CONTACT_ID'] ?? '';
            $language  = $contactData['LANGUAGE'];

            /* ================= INSERT ================= */
            if (empty($contactId)) {

                $query = "INSERT INTO tbl_contact (
                            enquiry_title,
                            enquiry_description,
                            enquiry_form_name,
                            enquiry_form_email,
                            enquiry_form_phone,
                            enquiry_preferred_city_title,
                            enquiry_property_room_name,
                            enquiry_requirements_placeholder,
                            enquiry_button_name,
                            phone_title,
                            phone_number,
                            whatsapp_title,
                            whatsapp_number,
                            email_title,
                            email_address,
                            office_address_title,
                            office_address,
                            social_media_title,
                            instagram_url,
                            facebook_url,
                            twitter_url,
                            linkedin_url,
                            language
                        ) VALUES (
                            '".$contactData['ENQUIRY_TITLE']."',
                            '".$contactData['ENQUIRY_DESCRIPTION']."',
                            '".$contactData['ENQUIRY_FORM_NAME']."',
                            '".$contactData['ENQUIRY_FORM_EMAIL']."',
                            '".$contactData['ENQUIRY_FORM_PHONE']."',
                            '".$contactData['ENQUIRY_PREFERRED_CITY_TITLE']."',
                            '".$contactData['ENQUIRY_PROPERTY_ROOM_NAME']."',
                            '".$contactData['ENQUIRY_REQUIREMENTS_PLACEHOLDER']."',
                            '".$contactData['ENQUIRY_BUTTON_NAME']."',
                            '".$contactData['PHONE_TITLE']."',
                            '".$contactData['PHONE_NUMBER']."',
                            '".$contactData['WHATSAPP_TITLE']."',
                            '".$contactData['WHATSAPP_NUMBER']."',
                            '".$contactData['EMAIL_TITLE']."',
                            '".$contactData['EMAIL_ADDRESS']."',
                            '".$contactData['OFFICE_ADDRESS_TITLE']."',
                            '".$contactData['OFFICE_ADDRESS']."',
                            '".$contactData['SOCIAL_MEDIA_TITLE']."',
                            '".$contactData['INSTAGRAM_URL']."',
                            '".$contactData['FACEBOOK_URL']."',
                            '".$contactData['TWITTER_URL']."',
                            '".$contactData['LINKEDIN_URL']."',
                            '".$language."'
                        )";

                $intRows = $this->dbHelper->insert($query);
                if ($intRows > 0) {
                    $intRowsUpdated++;
                }

            }
            /* ================= UPDATE ================= */
            else {

                $query = "UPDATE tbl_contact SET 
                            enquiry_title = '".$contactData['ENQUIRY_TITLE']."',
                            enquiry_description = '".$contactData['ENQUIRY_DESCRIPTION']."',
                            enquiry_form_name = '".$contactData['ENQUIRY_FORM_NAME']."',
                            enquiry_form_email = '".$contactData['ENQUIRY_FORM_EMAIL']."',
                            enquiry_form_phone = '".$contactData['ENQUIRY_FORM_PHONE']."',
                            enquiry_preferred_city_title = '".$contactData['ENQUIRY_PREFERRED_CITY_TITLE']."',
                            enquiry_property_room_name = '".$contactData['ENQUIRY_PROPERTY_ROOM_NAME']."',
                            enquiry_requirements_placeholder = '".$contactData['ENQUIRY_REQUIREMENTS_PLACEHOLDER']."',
                            enquiry_button_name = '".$contactData['ENQUIRY_BUTTON_NAME']."',
                            phone_title = '".$contactData['PHONE_TITLE']."',
                            phone_number = '".$contactData['PHONE_NUMBER']."',
                            whatsapp_title = '".$contactData['WHATSAPP_TITLE']."',
                            whatsapp_number = '".$contactData['WHATSAPP_NUMBER']."',
                            email_title = '".$contactData['EMAIL_TITLE']."',
                            email_address = '".$contactData['EMAIL_ADDRESS']."',
                            office_address_title = '".$contactData['OFFICE_ADDRESS_TITLE']."',
                            office_address = '".$contactData['OFFICE_ADDRESS']."',
                            social_media_title = '".$contactData['SOCIAL_MEDIA_TITLE']."',
                            instagram_url = '".$contactData['INSTAGRAM_URL']."',
                            facebook_url = '".$contactData['FACEBOOK_URL']."',
                            twitter_url = '".$contactData['TWITTER_URL']."',
                            linkedin_url = '".$contactData['LINKEDIN_URL']."'
                          WHERE contact_id = '".$contactId."'
                          AND language = '".$language."'";

                $this->dbHelper->update($query);
                $intRowsUpdated++;
            }
        }

        return $intRowsUpdated;

    } catch (Exception $e) {

        error_log("Update Contact Us error: " . $e->getMessage());

        return [
            "status"  => false,
            "message" => "Something went wrong during contact us update. Please try again."
        ];
    }
}



public function getFooterData($id) 
    {
        try {
            $whereClause = "";
            if($id>0)
                $whereClause = " WHERE quick_list_id = ".$id;

            // Prepare SQL using placeholders
            $query = "SELECT * FROM tbl_quick_list " . $whereClause . " ORDER BY link_priority ASC, `language` ASC";
            $arrFooterData = $this->dbHelper->select($query);
            return $arrFooterData;

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


public function addUpdateFooterData($arrUpdateData)
{
    try {
        $intRowsUpdated = 0;
        foreach ($arrUpdateData as $linkData) {
            if ($linkData['QUICK_LIST_ID'] > 0) {
                // Update existing Footer Link
                $query = "UPDATE tbl_quick_list SET 
                          link_name = '" . $linkData['LINK_NAME'] . "',
                          description = '" . $linkData['DESCRIPTION'] . "',
                          link_priority = '" . $linkData['LINK_PRIORITY'] . "',
                          category = '" . $linkData['CATEGORY'] . "',
                          `language` = '" . $linkData['LANGUAGE'] . "'
                          WHERE quick_list_id = '" . $linkData['QUICK_LIST_ID'] . "'";

                $intRows = $this->dbHelper->update($query);
                $intRowsUpdated++;
            } else {
                // Insert new Footer Link
                $query = "INSERT INTO tbl_quick_list (link_name, description, link_priority, category, `language`) VALUES (
                          '" . $linkData['LINK_NAME'] . "', 
                          '" . $linkData['DESCRIPTION'] . "', 
                          '" . $linkData['LINK_PRIORITY'] . "', 
                          '" . $linkData['CATEGORY'] . "',
                          '" . $linkData['LANGUAGE'] . "')";

                $intRows = $this->dbHelper->insert($query);
                if ($intRows > 0) {
                    $intRowsUpdated++;
                }
            }
        }

        return $intRowsUpdated;

    } catch (Exception $e) {
        error_log("Add/Update Footer Link error: " . $e->getMessage());
        return [
            "status" => false,
            "message" => "Something went wrong during Footer Link update. Please try again."
        ];
    }
}  


public function deleteFooterData($linkId)
    {
        try 
        {
            $query = "DELETE FROM tbl_quick_list WHERE quick_list_id = '" . intval($linkId) . "'";
            $intRows = $this->dbHelper->update($query);
            return $intRows;

        } 
        catch (Exception $e) 
        {
            error_log("Delete Footer Link error: " . $e->getMessage());
            return [
                "status" => false,
                "message" => "Something went wrong during Footer Link deletion. Please try again."
            ];
        }
    
    }




public function getApartmentsData($id) 
    {
        try {
            $whereClause = "";
            if($id>0)
                $whereClause = " WHERE apartment_uqk_id = ".$id;

            // Prepare SQL using placeholders
            
            $query = "SELECT * 
            FROM tbl_apartments " . $whereClause . " ORDER BY web_priority ASC, `language` ASC";
            
            $getGallaryQuery = "SELECT * FROM tbl_gallary
             " . $whereClause . " ORDER BY img_priority ASC"; 


            $arrApartmentData = $this->dbHelper->select($query);
            $arrGallaryData = $this->dbHelper->select($getGallaryQuery);

            return array($arrApartmentData, $arrGallaryData);

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

public function insertUpdateApartment($apartmentData, $galleryData)
{
    try 
    {
        $rowsAffected = 0;
        $uploadDir = "../../Document/ApartmentImg/";


        // echo "<pre>"; print_r($apartmentData); 
        // echo "<pre>"; print_r($galleryData); die;
        foreach ($apartmentData as $apt) 
        {

            if ($apt['APARTMENT_ID']>0) 
             {


                //ADDTIONAL_DESCRIPTION
                // UPDATE
             $query = "UPDATE tbl_apartments SET
                    city = '" . $apt['CITY'] . "',
                    type = '" . $apt['TYPE'] . "',
                    title = '" . $apt['TITLE'] . "',
                    price = '" . $apt['PRICE'] . "',
                    description = '" . $apt['DESCRIPTION'] . "',
                    tags = '" . $apt['TAGS'] . "',
                    map_embed = '" . $apt['MAP_EMBED'] . "',
                    rooms_count = '" . (int)$apt['ROOMS_COUNT'] . "',
                    rooms_count = '" . (int)$apt['ROOMS_COUNT'] . "',
                    additional_info = '" .$apt['ADDTIONAL_DESCRIPTION'] . "'
                    WHERE apartment_id = '" . (int)$apt['APARTMENT_ID'] . "'"; 

                $this->dbHelper->update($query);
                $rowsAffected++;

            } 
            else 
            {

                // INSERT
                $query = "INSERT INTO tbl_apartments
                    (apartment_uqk_id, city, `type`, title, price, `description`, tags, map_embed, rooms_count, web_priority, `language`,additional_info)
                VALUES (
                    '" . $apt['APARTMENT_UQK_ID'] . "',
                    '" . $apt['CITY'] . "',
                    '" . $apt['TYPE'] . "',
                    '" . $apt['TITLE'] . "',
                    '" . $apt['PRICE'] . "',
                    '" . $apt['DESCRIPTION'] . "',
                    '" . $apt['TAGS'] . "',
                    '" . $apt['MAP_EMBED'] . "',
                    '" . (int)$apt['ROOMS_COUNT'] . "',
                    '" . (int)$apt['WEB_PRIORITY'] . "',
                    '" . $apt['LANGUAGE'] . "',
                    '" .$apt['ADDTIONAL_DESCRIPTION'] . "'
                )";

                if ($this->dbHelper->insert($query)) 
                {
                    $rowsAffected++;
                }
            }
        }
       
        /* =====================================================
           2️⃣ INSERT / UPDATE GALLERY + IMAGE UPLOAD
        ====================================================== */
        foreach ($galleryData as $img) 
        {

            $galleryId = (int) ($img['GALLARY_ID'] ?? 0);
          

            // ---------- INSERT ----------
            if ($galleryId>0) 
            {
               // ---------- UPDATE ----------
               $query = "UPDATE tbl_gallary SET
                img_name = '" . $img['IMG_NAME'] . "',
                img_priority = '" . (int)$img['IMG_PRIORITY'] . "',
                is_banner_img = '" . (int)$img['IS_BANNER_IMG'] . "',
                img='".$img['IMG_EXT']."'
                WHERE gallary_id = '" . $galleryId . "'"; 

                $this->dbHelper->update($query);
                $rowsAffected++;         
                
            } 
            else 
            {

                   $query = "INSERT INTO tbl_gallary
                    (apartment_uqk_id, img_name, img_priority, is_banner_img,img)
                    VALUES (
                    '" . $img['APARTMENT_UQK_ID'] . "',
                    '" . $img['IMG_NAME'] . "',
                    '" . (int)$img['IMG_PRIORITY'] . "',
                    '" . (int)$img['IS_BANNER_IMG'] . "',
                    '".$img['IMG_EXT']."'
                    )"; 

                $galleryId = $this->dbHelper->insert($query);

                if ($galleryId > 0) {
                    $rowsAffected++;
                }
            }

            // echo  $rowsAffected; die;
        
            if ($img['TEMP_DIR']!='' || $img['TEMP_DIR']!=NULL) 
            {
                 $fileName = $img['APARTMENT_UQK_ID'] . '_' . $galleryId . '.' . $img['IMG_EXT']; 
               // $destPath = $uploadDir . $fileName;
                //echo $destPath = $_SERVER['DOCUMENT_ROOT'].'/boarding-house/Document/ApartmentImg/'. $fileName; die;
                 $destPath = '../../Document/ApartmentImg/'. $fileName; 

               if(move_uploaded_file($img['TEMP_DIR'], $destPath))
                   echo "files uploaded "; 
                
            }
        }

        return $rowsAffected;

    }  
    catch (Exception $e) 
    {
        error_log("Apartment insert/update error: " . $e->getMessage());
        return 0;
    }
}


public function deleteImageFromGallary($apartmentUqkId, $gallaryId)
{
    try {

        if (empty($apartmentUqkId) || empty($gallaryId)) {
            return 0;
        }

        // Ensure image belongs to the apartment (safety check)
        $query = "DELETE FROM tbl_gallary
                  WHERE gallary_id = '" . (int)$gallaryId . "'
                  AND apartment_uqk_id = '" . (int)$apartmentUqkId . "'";

        return $this->dbHelper->update($query);

    } catch (Exception $e) {
        error_log("Gallery image delete error: " . $e->getMessage());
        return 0;
    }
}

public function getEnquiryDataPaginated($flag,$start,$end,$limit,$offset)
{
    $where=[];

    if($flag=='today') $where[]="DATE(date_time)=CURDATE()";
    if($flag=='pending') $where[]="enq_sts='Pending For Reply'";
    if($flag=='responded') $where[]="enq_sts='Responded Enquiry'";
    if($start && $end) $where[]="DATE(date_time) BETWEEN '$start' AND '$end'";

    $sql="SELECT * FROM enquiry";
    if($where) $sql.=" WHERE ".implode(" AND ",$where);
    $sql.=" ORDER BY date_time DESC LIMIT $limit OFFSET $offset";

    return $this->dbHelper->select($sql);
}

public function getEnquiryCount($flag,$start,$end)
{
    $where=[];

    if($flag=='today') $where[]="DATE(date_time)=CURDATE()";
    if($flag=='pending') $where[]="enq_sts='Pending For Reply'";
    if($flag=='responded') $where[]="enq_sts='Responded Enquiry'";
    if($start && $end) $where[]="DATE(date_time) BETWEEN '$start' AND '$end'";

   $sql="SELECT COUNT(*) total FROM enquiry";

    if($where) $sql.=" WHERE ".implode(" AND ",$where);

    

    $res=$this->dbHelper->select($sql);

    //print_r($res);
    return $res[0]->TOTAL ?? 0;
}




public function deleteEnquiry($enquiryId)
{
    try {

        $enquiryId = (int)$enquiryId;
        if ($enquiryId <= 0) {
            return 0;
        }

        
        // 2️⃣ Delete enquiry
        $queryEnquiry = "
            DELETE FROM enquiry
            WHERE enquiry_id = '$enquiryId'
        ";

        return $this->dbHelper->update($queryEnquiry);

    } catch (Exception $e) {
        error_log("Delete Enquiry error: " . $e->getMessage());
        return 0;
    }
}


public function insertReply($arrReplyData)
{
    try {

        // ---- Validate required fields ----
        if (
            empty($arrReplyData['subject']) ||
            empty($arrReplyData['message']) ||
            empty($arrReplyData['email']) ||
            empty($arrReplyData['enquiryId'])
        ) 
        {
            return 0;
        }

        $subject    = trim($arrReplyData['subject']);
        $message    = trim($arrReplyData['message']);
        $email      = trim($arrReplyData['email']);
        $enquiryId  = (int)$arrReplyData['enquiryId'];
        $replyBy    = $arrReplyData['reply_by'] ?? null;

        // ---- Insert reply history ----
        $insertQuery = "
            INSERT INTO enquiry_reply_history
            (enquiry_id, subject, msg_reply, replied_by)
            VALUES
            ('$enquiryId', '$subject', '$message', '$replyBy')
        ";

        $replyId = $this->dbHelper->insert($insertQuery);

        if ($replyId <= 0) {
            return 0;
        }

        // ---- Update enquiry status ----
        $updateQuery = "
            UPDATE enquiry
            SET enq_sts = 'Responded Enquiry'
            WHERE enquiry_id = '$enquiryId'
        ";
        $this->dbHelper->update($updateQuery);

        // // ---- Send reply email ----
        // $headers  = "MIME-Version: 1.0\r\n";
        // $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        // $headers .= "From: noreply@yourdomain.com\r\n";

        // @mail($email, $subject, $message, $headers);

        // // ---- Return inserted reply ID ----


        return $replyId;

    } catch (Exception $e) {
        error_log("Insert Reply Error: " . $e->getMessage());
        return 0;
    }
}


public function getPriviousReplyHistory($enquiryId)
{
    try {

        $enquiryId = (int) $enquiryId;
        if ($enquiryId <= 0) {
            return [];
        }

        $query = "
            SELECT 
                reply_id,
                subject,
                msg_reply,
                replied_by,
                DATE_FORMAT(reply_date, '%d %b %Y %H:%i') AS reply_date
            FROM enquiry_reply_history
            WHERE enquiry_id = '$enquiryId'
            ORDER BY reply_date DESC
        ";

        return $this->dbHelper->select($query);

    } catch (Exception $e) {
        error_log('Get Previous Reply History Error: ' . $e->getMessage());
        return [];
    }
}


public function getFrontendDataByLanguage($language = 'English')
{
    try {

        // ---- Safety: allow only valid languages ----
        $allowedLanguages = ['English', 'German'];
        if (!in_array($language, $allowedLanguages)) {
            $language = 'English';
        }

        $data = [];

        /* ---------- MENU ---------- */
        $data['menu'] = $this->dbHelper->select(
            "SELECT * FROM tbl_menu WHERE language = '$language'"
        );

        /* ---------- HOME ---------- */
        $data['home'] = $this->dbHelper->select(
            "SELECT * FROM tbl_home WHERE language = '$language'"
        );

        /* ---------- FAQ ---------- */
        $data['faq'] = $this->dbHelper->select(
            "SELECT * FROM tbl_faq WHERE language = '$language' ORDER BY priority ASC"
        );

        /* ---------- CONTACT ---------- */
        $data['contact'] = $this->dbHelper->select(
            "SELECT * FROM tbl_contact WHERE language = '$language'"
        );

        /* ---------- QUICK LIST ---------- */
        $data['quick_list'] = $this->dbHelper->select(
            "SELECT * FROM tbl_quick_list WHERE language = '$language' ORDER BY link_priority ASC"
        );

        /* ---------- APARTMENTS + GALLERY ---------- */
        $rows = $this->dbHelper->select("
            SELECT 
                apa.*,
                ga.gallary_id,
                ga.img,
                ga.img_name,
                ga.is_banner_img,
                ga.img_priority
            FROM tbl_apartments apa
            LEFT JOIN tbl_gallary ga 
                ON ga.apartment_uqk_id = apa.apartment_uqk_id
            WHERE apa.language = '$language'
            ORDER BY apa.web_priority ASC, ga.img_priority ASC
        ");

        //echo '<pre>'; print_r($rows);

        /* ---------- GROUP APARTMENTS ---------- */
        $apartments = [];

        foreach ($rows as $row) {

            $uqkId = $row->APARTMENT_UQK_ID;

            if (!isset($apartments[$uqkId])) {
                $apartments[$uqkId] = [
                    'apartment_id'      => $row->APARTMENT_ID,
                    'apartment_uqk_id'  => $row->APARTMENT_UQK_ID,
                    'city'              => $row->CITY,
                    'type'              => $row->TYPE,
                    'title'             => $row->TITLE,
                    'price'             => $row->PRICE,
                    'description'       => $row->DESCRIPTION,
                    'add_info'       => $row->ADDITIONAL_INFO,
                    'tags'              => $row->TAGS,
                    'map_embed'         => $row->MAP_EMBED,
                    'rooms_count'       => $row->ROOMS_COUNT,
                    'web_priority'      => $row->WEB_PRIORITY,
                    'gallery'           => []
                ];
            }

            if (!empty($row->GALLARY_ID)) {
                $apartments[$uqkId]['gallery'][] = [
                    'gallary_id'    => $row->GALLARY_ID,
                    'img'           => $row->IMG,
                    'img_name'      => $row->IMG_NAME,
                    'is_banner_img' => $row->IS_BANNER_IMG,
                    'img_priority'  => $row->IMG_PRIORITY,
                    'apartment_uqk_id'  => $row->APARTMENT_UQK_ID
                ];
            }
        }


        $data['apartments'] = array_values($apartments);

        return $data;

    } catch (Exception $e) {
        error_log("Frontend Data Error ($language): " . $e->getMessage());
        return [];
    }
}

public function insertEquiryData($arrEnquiryData)
{
    try {

        // ---------- Sanitize all string values ----------
        foreach ($arrEnquiryData as $key => $value) {
            if (is_string($value)) {
                $arrEnquiryData[$key] = str_replace(
                    ["'", '"'],
                    "`",
                    trim($value)
                );
            }
        }

        $query = "INSERT INTO enquiry (
                    name,
                    email_id,
                    mobile_no,
                    city,
                    type_of_room,
                    description,
                    enq_sts
                ) VALUES (
                    '".$arrEnquiryData['enquiry_name']."',
                    '".$arrEnquiryData['enquiry_email']."',
                    '".$arrEnquiryData['enquiry_phone']."',
                    '".$arrEnquiryData['preferred_city']."',
                    '".$arrEnquiryData['property_room']."',
                    '".$arrEnquiryData['requirements']."',
                    'Pending For Reply'
                )";

        $intRows = $this->dbHelper->insert($query);

        return $intRows;

    } catch (Exception $e) {

        error_log("Insert Enquiry error: " . $e->getMessage());

        return [
            "status"  => false,
            "message" => "Something went wrong while submitting enquiry. Please try again."
        ];
    }
}





public function deleteApartment($apartmentUqkId)
{
    try {

        if (empty($apartmentUqkId)) 
            return 0;
        

        // Ensure image belongs to the apartment (safety check)
        $query = "DELETE FROM tbl_apartments
                  WHERE apartment_uqk_id = '" . (int)$apartmentUqkId . "'";
                  

        return $this->dbHelper->update($query);

    } catch (Exception $e) {
        error_log("Aparment delete error: " . $e->getMessage());
        return 0;
    }
}


public function updatePassword($arrUpdatePass)
{
    try {


        $checkQry="select * from admins where id='".$_SESSION['ID']."' and pwd='".$arrUpdatePass['current_password']."'";

        $strUpdateQry="update admins set pwd='".$arrUpdatePass['confirm_password']."' where id='".$_SESSION['ID']."' ";

//         echo "<pre>"; print_r($checkQry);
//  echo "<pre>"; print_r($strUpdateQry);die;
        $prePass= $this->dbHelper->select($checkQry);
        $intId=0;

       // echo "<pre>"; print_r($prePass);die;
        if($prePass[0]->PWD==$arrUpdatePass['current_password'])
        {   
            if($this->dbHelper->update($strUpdateQry))
            {    
                $intId++;
                 
            }   
        }   
        else
        {
              $intId=-1;
        }    

        return $intId;

    } catch (Exception $e) {
        error_log("Aparment delete error: " . $e->getMessage());
        return 0;
    }
}





    
}   
?>