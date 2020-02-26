<?php
/*
Plugin Name:  Form Plugin
Plugin URI: http://www.form.com
Description: Custom Form Validation Plugin
Version: 1.0
Author: Krishnan
Author URI: http://www.krishnan.com
*/

function formCode() { ?>

     <div class="form-wrap">
         <form  action="" method="POST" >
          <input type="hidden" name="action" value="submit">
          
         <div class="form-group">
           <label for="first_name">First Name<red>*</red></label>
           <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required="yes">
          </div>
          
         <div class="form-group">
           <label for="last_name">Last Name<red>*</red></label>
           <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required="yes">
          </div>
          
         <div class="form-group">
           <label for="email">Email Address<red>*</red></label>
           <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="yes">
         </div>
          
         <div class="form-group">
           <label for="phone">Phone Number<red>*</red></label>
           <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone" required="yes">
          </div>
          
         <div class="form-group">
           <label for="file">File<red>*</red></label>
           <input type="file" class="form-control" name="file" id="file" placeholder="File" required="yes">
          </div>
          
         <div class="form-group">
               <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          </div>
          
          </form>
     </div>

     <?php

     
     


     







// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_DISPLAY', true );
// define( 'WP_DEBUG_LOG', true );
//Initializing variable
$action = "";
// This is somewhat of a validity checker.
// I don't have the form sending to anywhere in the 'form' DOM.
// I validate the existence of the form in a hidden field.

//isset()
$action = isset($_POST['action']) ? $_POST['action'] : '';
//empty()
$action = !empty($_POST['action']) ? $_POST['action'] : '';

if ($action=="")    /* display the contact form */
    {

    }
else                /* send the submitted data */
    {
    $first_name		=	$_POST['first_name'];
    $last_name		=	$_POST['last_name'];
    $email			=	$_POST['email'];
    $phone			=	$_POST['phone'];
    $file			=	$_POST['file'];

	// $from		=	"From: $first_name $last_name<$email>\r\nReturn-path: $email";
     // $subject	=	"Web contact form: $last_name";
     // $courtesy 	=	"Thank you for your interest in contacting My Company. (Here is a copy of the form you recently sent to us.) ";
     $file = array( WP_CONTENT_DIR . '/uploads/2020/01/Untitled document.docx' );
	$message	=	" 
	Sent:  
	--------------------------------------------------- <br/>
	Name:    $first_name $last_name\r\n <br/>
	Email:   $email\r\n <br/>
	Phone:   $phone\r\n <br/>
	
     ";
     // $courtesy_message = "$courtesy \r\r $message";
	// wp_mail("krishnan.dhanapal@ziffity.com", $subject, $message, $from);  // This one goes to ME.
     // wp_mail($from, $subject, $courtesy_message, $from_noreply);    // This one goes to the guest.
     

     $to = 'krishnan.dhanapal@ziffity.com';
     $subject = 'The Form Details';
     // $body = 'The email body content';
     $headers = array('Content-Type: text/html; charset=UTF-8');


     // global $wpdb;
     // $table = $wpdb->prefix.'wp_details';
     // $data = array('column1' => 'data one', 'column2' => 123);
     // $format = array('%s','%d');
     // $wpdb->insert($table,$data,$format);
     // $my_id = $wpdb->insert_id;

     $target = 'uploads/' . basename($_FILES['file']['name']);
     // $target = '/uploads/2020/01/Untitled document.docx';

//     if(move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
//         $fp = fopen($target, "r");
//     }


     global $wpdb;
     $sql=$wpdb->insert('wp_details', array('firstname'=> $first_name, 'lastname'=> $last_name,'email'=>$email,'phone'=>$phone,'file'=>$target));
     if($sql==true){
          echo "<script>alert('data inserted')</script>";
     }
     else{
          echo "<script>alert('data not inserted')</script>";
     }
 
     // wp_mail( $to, $subject, $body, $headers );

        if (wp_mail($to,$subject,$message,$headers,$file)) {
          echo '<div class="alert alert-success pt-5 mt-5" role="alert">
                    <h2>Thank you!</h2>
                    </div>';
        }else {
          echo '<div class="alert alert-danger pt-5 mt-5" role="alert">
                    <h2>Failed!</h2>
                    </div>';
                    print_r(error_get_last());
         }
		
	    }
    // }
?>

<?php }

function shortcode() {
	ob_start();
	formCode();
	return ob_get_clean();
}
add_shortcode( 'contact_form', 'shortcode' );

// Add into Admin Menu Bar
add_action('admin_menu', 'form_plugin'); 

function form_plugin(){
    add_menu_page( 'From Plugin Page TabTitle ', 'Form Plugins', 'manage_options', 'test-plugin', 'content' );
}

function content() { ?>

<div class="container admin">
<h1>Form Details</h1>
     <table>
          <tr>
               <th>Firstname</th>
               <th>Lastname</th>
               <th>Email</th>
               <th>Phone</th>
               <th>Personal Info</th>
          </tr>
          <?php
               global $wpdb;
               $result = $wpdb->get_results ( "SELECT * FROM wp_details" );
               foreach ( $result as $print ) {
          ?>
          <tr>
               <td><?php echo $print->firstname;?></td>
               <td><?php echo $print->lastname;?></td>
               <td><?php echo $print->email;?></td>
               <td><?php echo $print->phone;?></td>
               <td><a href="<?php echo $print->file;?>">view File</a></td>
          </tr>
          <?php
          }
          ?>
     </table>
</div>

<?php }

?>

<style>
     .admin table{
          width: 95%;
          border: 1px solid #000;
          margin: auto;
          border-collapse: collapse;
     }
     .admin th,.admin td{
          border: 1px solid #000;
          text-align:center;
     }


</style>