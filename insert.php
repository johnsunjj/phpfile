<?php

	error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once 'dbContent.php';
	
	if(isset($_POST['btnsave']))
	{
		$model = $_POST['modName'];
		$amount = $_POST['amount'];
		$mobDesc = $_POST['mobDescrip'];
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		
		
		if(empty($model)){
			$errMSG = "Please Enter Mobile Model.";
		}
		else if(empty($amount)){
			$errMSG = "Please Enter Amount.";
		}
		else if(empty($mobDesc)){
			$errMSG = "Please Enter Description.";
		}
		else if(empty($imgFile)){
			$errMSG = "Please Select Image File.";
		}
		else
		{
			$upload_dir = 'image/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$mobpic = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$mobpic);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO techpro(mobpic,name,amount,description) VALUES(:upic,:uname,:uamount, :udescription)');
			$stmt->bindParam(':upic',$mobpic);
			$stmt->bindParam(':uname',$model);
			$stmt->bindParam(':uamount',$amount);
			$stmt->bindParam(':udescription',$mobDesc);
			
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
    	<td><label class="control-label">Mobile Pic</label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>
	
    <tr>
    	<td><label class="control-label">Model Name</label></td>
        <td><input class="form-control" type="text" name="modName" placeholder="Enter Model Name" value="<?php echo $model; ?>" /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Amount</label></td>
        <td><input class="form-control" type="text" name="amount" placeholder="Enter Amount" value="<?php echo $amount; ?>" /></td>
    </tr>
		    <tr>
    	<td><label class="control-label">Description</label></td>
        <td><input class="form-control" type="text" name="mobDescrip" placeholder="Enter Description" value="<?php echo $mobDesc; ?>" /></td>
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