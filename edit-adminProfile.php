<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>GrandStore</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="assets/css/responsive.css">
    </head> 
<!----------------------------------------- get stud id ----------------------------------->    
    <body style="background-image: url('./assets/img/grand2.png');background-repeat: no-repeat;background-size: cover;background-position: center; ">
    <?php
     include '.vscode/config.php'; 
     include '.vscode/validationForm.php'; 
     include '.vscode/validation_admin.php'; 
     session_start();

     $admin_id = $_SESSION['admin_id'] ?? null;

    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        $sql = "SELECT * FROM admins WHERE admin_id = '$admin_id'";
        
        $result = $conn->query($sql);
        
        if($row = $result->fetch_assoc())
        {
            $username = $row['admin_name'];
            $admin_id = $row['admin_id'];
            $profile_photo = $row['admin_photo'];
            $phone = $row['admin_phone'];
            $email = $row['admin_email'];
            $gender = $row['admin_gender'];
            $admin_position = $row['admin_position'];
            $password = $row['admin_password']; 
        }
        else
        {
            //no recor select the database
            echo'<div class = "error"> 
                   Database error.No record found.
                   <div>';
            exit();
            
        }
        $result->free();
        $conn->close();
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Post method
        // When user clicks on the update button
        // Retrieve user input
        $username = strtoupper(trim($_POST['username']));
        $gender = trim($_POST['gender']);
       $phone = trim($_POST['phone']);
       $email = trim($_POST['email']);
       $admin_position = strtoupper(trim($_POST['admin_position']));

        // Check if a new profile photo has been uploaded
        if ($_FILES["profile_photo"]["size"] > 0) {
            $profile_photo = $_FILES["profile_photo"]["name"];
            $profile_photo_temp = $_FILES["profile_photo"]["tmp_name"];
            $folder = "./assets/img/" . $profile_photo;

            // Upload photo and check if it uploaded successfully
            if (!move_uploaded_file($profile_photo_temp, $folder)) {
                $profile_photo = "default.jpg";
            }
        } else {
            // No new photo uploaded, use the existing photo
            $profile_photo = $_POST['existing_photo'];
        }
       
       $error = validation_ad();
       
        if(empty($error))//no error
        {
            //step 2
             $sql = "UPDATE admins SET admin_name=?,admin_gender=?, admin_phone=?,admin_email=?,admin_position=?,admin_photo=? WHERE admin_id=?";
             
            //step 3
             $stmt = $conn->prepare($sql);

             $stmt->bind_param('ssssssd',$username,$gender, $phone, $email, $admin_position, $profile_photo, $admin_id);
             
             if($stmt->execute())
             {
                 //update success
                 printf('
                     <div style = " margin: 0px 50px 20px 50px;
                                    padding: 30px;
                                    border: 2px solid green;
                                    border-radius: 20px;
                                    box-shadow: 8px 8px 8px grey ;
                                    background-color: rgba(94,237,144,0.9);
                                    color: white;">
                     Member <b>%s</b> has been Update.
                     </div>
                         ',$username);
                
                         header("Location: adminProfile.php");
                         exit();
             }
             else{
                 echo'<div class="error">Error, Cannot Update Record</div>';
             }
             $stmt->close();
             $conn->close();
        }else{
            printf('<ul class = "errorr" 
                                    style="
                                    margin: 0px 90px 20px 90px;
                                    padding: 30px;
                                    border-radius: 20px;
                                    box-shadow: 3px 3px 3px grey ;
                                    background-color: rgba(251, 49, 49, 0.9);
                                    color: white;">
                   <li>%s</li>
                   </ul>',implode('</li><li>',$error));
        }
      
    }
     
    ?>
        
<!----------------------------------------- form ---------------------------------------->    
<br/>
    <form action="" method="POST" enctype="multipart/form-data" style="float:right; border: 2px solid gray; box-shadow: 3px 3px 3px grey ;
                                             margin: 0px 40px 40px 40px;
                                             padding: 40px 40px 80px 40px;
                                             border-radius: 50px">

        <table>
            <tr>
                <td colspan="3" style="text-decoration: underline"><h1 style="text-align: center;">Profile</h1><br/></td>
            </tr>
        <tr>
            <td rowspan="6">
               <div class="photo" style="width: 300px; 
                                         height: 300px; 
                                         box-sizing: border-box; 
                                         position: relative; 
                                         margin: 0px 30px 30px 0px; 
                                         border-radius: 300px;">
                   <input type="file" name="profile_photo" accept="image/*" onchange="readURL(this);" title="Change Image" style="position: absolute; 
                                                                                                                                  top: 0; 
                                                                                                                                  bottom: 0; 
                                                                                                                                  left: 0; 
                                                                                                                                  right: 0; 
                                                                                                                                  z-index: 100; 
                                                                                                                                  opacity: 0; 
                                                                                                                                  border-radius: 300px;" /> 
                   <input type="hidden" name="existing_photo" value="<?php echo $profile_photo; ?>" />

                   <img id="profile_pic" src="<?php echo isset($profile_photo) ? './assets/img/' . $profile_photo : './assets/img/default.jpg'; ?>"  style="max-height: 100%; 
                                                                           max-width: 100%; 
                                                                           vertical-align: bottom; 
                                                                           border-radius: 300px;" />
               </div>
            </td>
        </tr>
                
        <tr>
            <td>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="username" value="<?php echo isset($username)?$username:"" ?>"  style="width: 550px;padding:5px; border-radius: 6px; border: none;"/></td>
        </tr>
        <tr>
            <td>Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td>&nbsp;&nbsp;&nbsp;
                <input type="radio" name="gender" value="M" <?php echo $gender=='M'?"checked=checked":"";?>/> Male &nbsp;&nbsp;
                <input type="radio" name="gender" value="F"  <?php echo $gender=='F'?"checked=checked":"";?>/>&nbsp; Female
            </td>
        </tr>
        <tr>
            <td>Phone Number :</td>
            <td><input class="search1" type="text" name="phone" value="<?php echo isset($phone)?$phone:"" ?>" placeholder="000-9999999" style="width: 550px;padding:5px; border-radius: 6px; border: none;"/></td>
        </tr>
        <tr>
            <td>Email Address &nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="email" value="<?php echo isset($email)?$email:"" ?>"  style="width: 550px;padding:5px; border-radius: 6px; border: none;"/></td>
        </tr>
        <tr>
        <td>Position &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="admin_position" value="<?php echo isset($admin_position)?$admin_position:"" ?>" style="width: 550px;padding:5px; border-radius: 6px; border: none;"/></td>
        </tr>
    </table>
        <br/>
        <div style="text-align: center">
                <input class="cart-btn" type="submit" value="SAVE"/>
            </div>       
    </form>   
    </body>
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
    //focus on the ques 1
    document.getElementsByName("username")[0].focus();
    
    function readURL(input) {
        console.log(input.files);
        console.log(input.files[0]);

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile_pic').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    <script type="text/javascript">
        var profilePic = document.getElementById('profile_pic');

        // Reset width and height to auto
        profilePic.style.width = '300px';
        profilePic.style.height = '300px';

    </script>   
 
</html>