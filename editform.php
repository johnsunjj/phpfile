<?php

	error_reporting( ~E_NOTICE );
	
	require_once 'dbContent.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT mobpic,name,amount,description FROM techpro WHERE name =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{
		$model = $_POST['modName'];
		$amount = $_POST['amount'];
		$mobDesc = $_POST['mobDescrip'];
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
					
		if($imgFile)
		{
			$upload_dir = 'image/'; // upload directory	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
			$userpic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['mobpic']);
					move_uploaded_file($tmp_dir,$upload_dir.$mobpic);
				}
				else
				{
					$errMSG = "Sorry, your file is too large it should be less then 5MB";
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}	
		}
		else
		{
			// if no image selected the old image remain as it is.
			$userpic = $edit_row['mobpic']; // old image from database
		}	
						
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('UPDATE techpro
									     SET mobpic=:upic, 
										     name=:uname, 
											 amount=:uamount, 
										     description=:udescription 
								       WHERE name=:uid');
			$stmt->bindParam(':upic',$mobpic);
			$stmt->bindParam(':uname',$model);
			$stmt->bindParam(':uamount',$amount);
			$stmt->bindParam(':udescription',$mobDesc);
			
	
				
			if($stmt->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry Data Could Not Updated !";
			}
		
		}
		
						
	}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="style.css">
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>
<?php
	include 'header.php';
?>
<div class="container">



	<div class="page-header">
    	<h1 class="h2">Update Mobile Model </h1>
    </div>



<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>
   
    
	<table class="table table-bordered table-responsive">
		
	  <tr>
    	<td><label class="control-label">Mobile Pic</label></td>
        <td>
        	<p><img src="image/<?php echo $mobpic; ?>" height="400" width="300" /></p>
        	<input class="input-group" type="file" name="mobpic" accept="image/*" />
        </td>
    </tr>
	
		<tr>
    	<td><label class="control-label">Mobile Model.</label></td>
        <td><input class="form-control" type="text" name="modName" value="<?php echo $model; ?>" required /></td>
    </tr>
		
    <tr>
    	<td><label class="control-label">Amount.</label></td>
        <td><input class="form-control" type="text" name="amount" value="<?php echo $amount; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Description.</label></td>
        <td><input class="form-control" type="text" name="mobDescrip" value="<?php echo $mobDesc; ?>" required /></td>
    </tr>
    
  
    
    <tr>
        <td colspan="2"><button type="submit" name="btn_save_updates" class="btn btn-success">
        <span class="glyphicon glyphicon-save"></span> Update
        </button>
        
        <a class="btn btn-default btn-danger" href="index.php"> <span class="glyphicon glyphicon-backward"></span> Cancel </a>
        
        </td>
    </tr>
    
    </table>
    
</form>



</div>
</body>
</html>