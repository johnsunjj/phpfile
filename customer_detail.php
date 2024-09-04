<?php

	error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once 'dbContent.php';
	
	if(isset($_POST['btnsave']))
	{
		$username = $_POST['user_name'];
		$usermob = $_POST['user_mob'];
		$$useraddress = $_POST['user_add'];
		
		$purchdetail = $_POST['purch_detail'];
		
		
		
		if(empty($username)){
			$errMSG = "Please Enter Name.";
		}
		else if(empty($usermob)){
			$errMSG = "Please Enter mobile number.";
		}
		else if(empty($useraddress)){
			$errMSG = "Please Enter address.";
		}
		else if (empty($purchdetail)){
			$errMSG = "Please Select Image File.";
		}

		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO techpro1(name,mobile,address,purch_detail) VALUES(:uname,:umobile, :uaddress, :upurch_detail)');
			$stmt->bindParam(':uname',$username);
			$stmt->bindParam(':umobile',$usermob);
			$stmt->bindParam(':uaddress',$useraddress);
			$stmt->bindParam(':upurch_detail',$purchdetail);
			
			if($stmt->execute())
			{
				$successMSG = "new record succesfully inserted ...";
				header("refresh:5;index.php"); // redirects image view page after 5 seconds.
			}
			else
			{
				$errMSG = "error while inserting....";
			}
		}
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/inserttab.css">
</head>

<body>
<?php
	include 'header.php';
?>
<div class="container">
	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   
<form method="post" enctype="multipart/form-data" class="form-horizontal">
	 <div class="tab">   
	<table class="table table-bordered table-responsive">
		
	
    <tr>
    	<td><label class="control-label">Name</label></td>
        <td><input class="form-control" type="text" name="user_name" placeholder="Enter Name" value="<?php echo $username; ?>" /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Mobile Number</label></td>
        <td><input class="form-control" type="text" name="user_mob" placeholder="Enter Mobile number" value="<?php echo $usermob; ?>" /></td>
    </tr>
		    <tr>
    	<td><label class="control-label">Address</label></td>
        <td><input class="form-control" type="text" name="user_add" placeholder="Enter Address" value="<?php echo $useraddress; ?>" /></td>
    </tr>
		
   	    <tr>
    	<td><label class="control-label">Purchuse Method</label></td>
        <td><input class="form-control" type="text" name="purch_detail" placeholder="Purchuse Method" value="<?php echo $purchdetail; ?>" /></td>
    </tr>
	
   
    
    <tr>
        <td colspan="2"><button type="submit" name="btnsave" class="btn btn-success btn-block">Submit
        </button>
        </td>
    </tr>
    
    </table>
    </div>
</form>

</div>

</body>
</html>