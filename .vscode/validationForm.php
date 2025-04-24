<?php
    function getGender()
    {
        return array(
            'M'=>'Male',
            'F'=>'Female'
        );
    }
    
    $GENDERS = getGender();
    
   function validation(){
    global $username,$gender,$phone,$email,$address, $profile_photo;
    
    $error = array();
    
    //////////////////////////check first name///////////////////////
    if($username == NULL)
    {
        $error['firstname'] = 'Please Enter Your <b>Name</b>.';
    }
    else if(strlen($username)>30)
    {
        $error['firstname'] = '<b>Name Length</b> Cannot More Than <b>30 Characters</b>.';
    }
    else if(!preg_match('/^[A-Za-z @,\'\.\-\/]+$/',$username))
    {
        $error['firstname'] = 'Invalid <b>Name</b> Format. Can Contain Only Uppercase and Lowercase Alphabet, Space, Alias [ @ ],
        Comma [ , ], Single-quote [ ‘ ], Dot [ . ], Dash [ - ] and Slash [ / ].';
    } 
    //////////////////////////check gender //////////////////////////
    if($gender == NULL)
    {
      $error['gender']='Please Select the <b>Gender</b>.';  
    }
    else if(!array_key_exists($gender, getGender()))
    {
       $error['gender'] ='Invalid <b>Gender</b> Code Detected';
    }
    //////////////////////////check phone ///////////////////////////
    if($phone == NULL)
    {
      $error['phone']='Please Enter the <b>Phone Number.</b>';  
    }
     else if(!preg_match('/^01\d-\d{7}$/',$phone))
    {
        $error['phone']='Invalid <b>Phone Number Format</b>. Format:019-9999999 and Start with 01.';
    }
    
    //////////////////////////check email ///////////////////////////
    if($email == NULL)
    {
      $error['email']='Please Enter the <b>Email Address.</b>';  
    }
    else if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]+$/',$email))
    {
       $error['email']='Invalid <b>Email Address Format</b>. Format: abc123@gmail.com.';  
    }
    
    //////////////////////////check address /////////////////////////
    if($address == NULL)
    {
      $error['address']='Please Enter the <b>Address.</b>';  
    }
    else if(!preg_match('/^[a-zA-Z0-9 @,\.\'\-\s\/]+$/',$address))
    {
        $error['address']  = 'Invalid <b>Address Format.</b> <br/>Can Contain Only Uppercase and Lowercase Alphabet, Space, Alias [ @ ],
        Comma [ , ], Single-quote [ ‘ ], Dot [ . ], Dash [ - ] and Slash [ / ].';
    }
    
    /////////////////////upload photo/////////////////////////////////
    // Maximum file size in bytes (e.g., 2MB)
    $max_file_size = 2 * 1024 * 1024; // 2MB

    // Allowed file types
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    
    // Get file extension
    $file_ext = strtolower(pathinfo($profile_photo, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["profile_photo"]["size"] > $max_file_size) {
        $error['profile_Photo'] = '<b>Photo size is too large.</b> Maximum size allowed is 2MB.';
    }

    // Check file type
    if (!in_array($file_ext, $allowed_types)) {
        $error['profile_photo'] = '<b>Unsupported Photo type.</b> Only JPG, JPEG, PNG, and GIF files are allowed.';
    }
    
    return $error;
    
    }//end validation function 
    
function validationwithoutid(){
    global $username,$gender,$phone,$email,$address, $profile_photo;
    
    $error = array();
    
    //////////////////////////check first name///////////////////////
    if($username == NULL)
    {
        $error['firstname'] = 'Please Enter Your <b>Name</b>.';
    }
    else if(strlen($username)>30)
    {
        $error['firstname'] = '<b>Name Length</b> Cannot More Than <b>30 Characters</b>.';
    }
    else if(!preg_match('/^[A-Za-z @,\'\.\-\/]+$/',$username))
    {
        $error['firstname'] = 'Invalid <b>Name</b> Format. Can Contain Only Uppercase and Lowercase Alphabet, Space, Alias [ @ ],
        Comma [ , ], Single-quote [ ‘ ], Dot [ . ], Dash [ - ] and Slash [ / ].';
    }
  
    //////////////////////////check gender //////////////////////////
    if($gender == NULL)
    {
      $error['gender']='Please Select the <b>Gender</b>.';  
    }
    else if(!array_key_exists($gender, getGender()))
    {
       $error['gender'] ='Invalid <b>Gender</b> Code Detected';
    }
    //////////////////////////check phone ///////////////////////////
    if($phone == NULL)
    {
      $error['phone']='Please Enter the <b>Phone Number.</b>';  
    }
     else if(!preg_match('/^01\d-\d{7}$/',$phone))
    {
        $error['phone']='Invalid <b>Phone Number Format</b>. Format:019-9999999 and Start with 01.';
    }
    
    //////////////////////////check email ///////////////////////////
    if($email == NULL)
    {
      $error['email']='Please Enter the <b>Email Address.</b>';  
    }
    else if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]+$/',$email))
    {
       $error['email']='Invalid <b>Email Address Format</b>. Format: abc123@gmail.com.';  
    }
    
    //////////////////////////check address /////////////////////////
    if($address == NULL)
    {
      $error['address']='Please Enter the <b>Address.</b>';  
    }
    else if(!preg_match('/^[a-zA-Z0-9 @,\.\'\-\s\/]+$/',$address))
    {
        $error['address']  = 'Invalid <b>Address Format.</b> <br/>Can Contain Only Uppercase and Lowercase Alphabet, Space, Alias [ @ ],
        Comma [ , ], Single-quote [ ‘ ], Dot [ . ], Dash [ - ] and Slash [ / ].';
    }
    
    /////////////////////upload photo/////////////////////////////////
    // Maximum file size in bytes (e.g., 2MB)
    $max_file_size = 2 * 1024 * 1024; // 2MB

    // Allowed file types
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    
    // Get file extension
    $file_ext = strtolower(pathinfo($profile_photo, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["profile_photo"]["size"] > $max_file_size) {
        $error['profile_Photo'] = '<b>Photo size is too large.</b> Maximum size allowed is 2MB.';
    }

    // Check file type
    if (!in_array($file_ext, $allowed_types)) {
        $error['profile_photo'] = '<b>Unsupported Photo type.</b> Only JPG, JPEG, PNG, and GIF files are allowed.';
    }
    
    return $error;
    
    }//end validation function 
?>