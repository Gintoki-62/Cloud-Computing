<?php
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(100) NOT NULL,
    admin_gender VARCHAR(20),
    admin_position VARCHAR(30),
    admin_email VARCHAR(100) NOT NULL,
    admin_phone VARCHAR(20),
    admin_photo VARCHAR(200),
    password VARCHAR(255) NOT NULL);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Admin Profile Page">

        <!-- title -->
        <title>Admin Profile | GrandStore</title>

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
    <body style="background-image: url('./assets/img/grand2.png');background-repeat: no-repeat;background-size: cover;background-position: center;">
    <?php
     include 'config.php'; 
     session_start();  

     $admin_id = $_SESSION['admin_id'] ?? null;
    
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        
        $sql = "SELECT * FROM admins WHERE admin_id = '$admin_id'";
        
        $result = $conn->query($sql);
        
        if($row = $result->fetch_assoc())
        {
            $admin_name = $row['admin_name'];
            $admin_phone = $row['admin_phone'];
            $admin_email = $row['admin_email'];
            $admin_position = $row['admin_position'];
            $admin_photo = $row['admin_photo'];
            $admin_gender = $row['admin_gender']; 
        }
        else
        {
            echo'<div class = "error"> 
                   Database error. No admin record found.
                   <div>';
            exit();
        }
        $result->free();
        $conn->close();
    }
    else
    {
        // Handle POST request for admin profile update
        $admin_name = strtoupper(trim($_POST['admin_name']));
        $admin_gender = trim($_POST['admin_gender']);
        $admin_phone = trim($_POST['admin_phone']);
        $admin_email = trim($_POST['admin_email']);
        $admin_position = strtoupper(trim($_POST['admin_position']));
       
        $admin_photo = $_FILES["admin_photo"]["name"];
        $admin_photo_temp = $_FILES["admin_photo"]["tmp_name"];
        $folder = "./assets/img/" . $admin_photo;
       
        if (!move_uploaded_file($admin_photo_temp, $folder)) {
            $admin_photo = "default.jpg";
        }
        
        $error = array();
        // Add validation for admin fields here
        
        if(empty($error))
        {
            $sql = "UPDATE admins SET admin_name=?, admin_phone=?, admin_email=?, admin_position=?, admin_photo=?, admin_gender=? WHERE admin_id=?";
             
            $stmt = $conn->prepare($sql);
             
            $stmt->bind_param('ssssssi', $admin_name, $admin_phone, $admin_email, $admin_position, $admin_photo, $admin_gender, $admin_id);
             
            if($stmt->execute())
            {
                 printf('
                     <div style = " margin: 0px 50px 20px 50px;
                                    padding: 30px;
                                    border: 2px solid green;
                                    border-radius: 20px;
                                    box-shadow: 8px 8px 8px grey ;
                                    background-color: rgba(94,237,144,0.9);
                                    color: white;">
                     Admin <b>%s</b> has been updated.
                     </div>
                         ',$admin_name);
             }
             else{
                 echo'<div class="error">Error, Cannot Update Admin Record</div>';
             }
             $stmt->close();
             $conn->close();
        } else {
            printf('<ul class = "errorr">
                   <li>%s</li>
                   </ul>',implode('</li><li>',$error));
            echo'</ul>';
        }
    }
    ?>
        
    <br/>
    <form action="" method="POST" enctype="multipart/form-data" style="float:right; border: 2px solid gray; box-shadow: 3px 3px 3px grey ;
                                             margin: 0px 40px 40px 40px;
                                             padding: 40px 40px 80px 40px;
                                             border-radius: 50px">
        
        <table>
            <tr>
                <td colspan="3" style="text-decoration: underline"><h1 style="text-align: center;">Admin Profile</h1><br/></td>
            </tr>
        <tr>
            <td rowspan="7">
               <div class="photo" style="width: 300px; 
                                         height: 300px; 
                                         box-sizing: border-box; 
                                         position: relative; 
                                         margin: 0px 30px 30px 0px; 
                                         border-radius: 300px;">
                   <input type="file" name="admin_photo" accept="image/*" onchange="readURL(this);" title="Change Image" disabled style="position: absolute; 
                                                                                                                                  top: 0; 
                                                                                                                                  bottom: 0; 
                                                                                                                                  left: 0; 
                                                                                                                                  right: 0; 
                                                                                                                                  z-index: 100; 
                                                                                                                                  opacity: 0; 
                                                                                                                                  border-radius: 300px;" /> 
                   <img id="profile_pic" src="<?php echo isset($admin_photo) ? './assets/img/' . $admin_photo : './assets/img/default.jpg'; ?>"  style="max-height: 100%; 
                                                                           max-width: 100%; 
                                                                           vertical-align: bottom; 
                                                                           border-radius: 300px;" />
               </div>
            </td>
        </tr>
                
        <tr>
            <td>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="admin_name" value="<?php echo isset($admin_name)?$admin_name:"" ?>"  style="width: 250px; padding:5px; border-radius: 6px; border: none;background-color:transparent;" readonly/></td>   
        </tr>
        <tr>
            <td>Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td>&nbsp;&nbsp;&nbsp;
                <input type="radio" name="admin_gender" value="M" <?php if ($admin_gender == 'M') echo "checked"; ?>> Male &nbsp;&nbsp;
                <input type="radio" name="admin_gender" value="F" <?php if ($admin_gender == 'F') echo "checked"; ?>> Female<br>
            </td>
        </tr>
        <tr>
            <td>Position &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="admin_position" value="<?php echo isset($admin_position)?$admin_position:"" ?>" style="width: 250px;padding:5px; border-radius: 6px; border: none;background-color:transparent;" readonly/></td>
        </tr>
        <tr>
            <td>Phone Number :</td>
            <td><input class="search1" type="text" name="admin_phone" value="<?php echo isset($admin_phone)?$admin_phone:"" ?>" placeholder="000-9999999" style="width: 250px;padding:5px; border-radius: 6px; border: none;background-color:transparent;" readonly/></td>
        </tr>
        <tr>
            <td>Email Address &nbsp;&nbsp;&nbsp;:</td>
            <td><input class="search1" type="text" name="admin_email" value="<?php echo isset($admin_email)?$admin_email:"" ?>"  style="width: 250px;padding:5px; border-radius: 6px; border: none;background-color:transparent;" readonly/></td>
        </tr>
    </table>
        <br/>
        <div style="text-align: center">
                <input class="cart-btn" type="button" value="EDIT" onclick="window.location.href='editAdminProfile.php?admin_id=<?php echo $admin_id ?>'"/> &nbsp;&nbsp;&nbsp;
                <input class="cart-btn" type="button" value="BACK" onclick="window.location.href='adminDashboard.php'"/>
            </div>       
    </form>  
        
    </body>
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
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