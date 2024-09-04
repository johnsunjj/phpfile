<?php

	require_once 'dbContent.php';
	if(isset($_GET['delete_id']))
	{
		// select image from db to delete
		$stmt_select = $DB_con->prepare('SELECT name FROM techpro WHERE name =:uid');
		$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("user_images/".$imgRow['userPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $DB_con->prepare('DELETE FROM techpro WHERE name =:uid');
		$stmt_delete->bindParam(':uid',$_GET['delete_id']);
		$stmt_delete->execute();
		
		header("Location: index.php");
	}

	?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<title>Techpro</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="css/product.css">
</head>

<body>
	<?php
	include 'header.php';
?>
<div class="container">

	
	<?php
	
	$stmt = $DB_con->prepare('SELECT mobpic,name,amount,description FROM techpro');
	$stmt->execute();
	
	if($stmt->rowCount() > 0)
	{
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			?>
	
			<div class="col-xs-4">
				<div class="mono">
				<img src="image/<?php echo $row['mobpic']; ?>" class="img-responsive" width="300px" height="1000px" />
				<span><p class="page-header" align="center"><?php echo $name; ?></p>
				<p align="center">&#8377;<?php echo $amount; ?></p></span>
				<div class="description"><p align="left"><?php echo $description; ?></p></div>
				<p class="page-header">
				<div class="edit">
				<a class="btn btn-primary" href="editform.php?edit_id=<?php echo $row['name']; ?>" title="click for edit" 
                onclick="return confirm('Do you Want to Edit <?php echo $row['name']; ?>?')">
                <span class="glyphicon glyphicon-edit"></span> Edit</a> 
                
                
				<a class="btn btn-danger" href="?delete_id=<?php echo $row['name']; ?>" title="click for delete" 
                onclick="return confirm('Do you want to delete <?php echo $row['name']; ?>?')">
                <span class="glyphicon glyphicon-remove-circle"></span> Delete</a>
				</div>
				</p>
			</div>    
		</div>
			<?php
		}
	}
	else
	{
		?>
        <div class="col-xs-6">
        	<div class="alert alert-warning">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ...
            </div>
        </div>
        <?php
	}
	
?>
</div>	
	

</body>
</html>