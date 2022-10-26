<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}

if (isset($_POST['btnEdit'])) {

	$name = $db->escapeString(($_POST['name']));
	$role = $db->escapeString($_POST['role']);
	$email = $db->escapeString($_POST['email']);
	$mobile = $db->escapeString($_POST['mobile']);
	$password = $db->escapeString($_POST['password']);
	$github = $db->escapeString($_POST['github']);
	$upi = $db->escapeString($_POST['upi']);
	$error = array();


        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($role)) {
            $error['role'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($email)) {
            $error['email'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($mobile)) {
            $error['mobile'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($password)) {
            $error['password'] = " <span class='label label-danger'>Required!</span>";
        }
       

		

		if (!empty($name) && !empty($role) && !empty($email) && !empty($mobile)&& !empty($password)&& !empty($github)&& !empty($upi))
		 {
			if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
				//image isn't empty and update the image
				$old_image = $db->escapeString($_POST['old_image']);
				$extension = pathinfo($_FILES["image"]["name"])['extension'];
		
				$result = $fn->validate_image($_FILES["image"]);
				$target_path = 'upload/staffs/';
				
				$filename = microtime(true) . '.' . strtolower($extension);
				$full_path = $target_path . "" . $filename;
				if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
					echo '<p class="alert alert-danger">Can not upload image.</p>';
					return false;
					exit();
				}
				if (!empty($old_image)) {
					unlink($old_image);
				}
				$upload_image = 'upload/staffs/' . $filename;
				$sql = "UPDATE staffs SET `image`='" . $upload_image . "' WHERE `id`=" . $ID;
				$db->sql($sql);
			}
			
             $sql_query = "UPDATE staffs SET name='$name',role='$role',email='$email',mobile='$mobile',password='$password',github='$github',upi='$upi' WHERE id =  $ID";
			 $db->sql($sql_query);
			 $res = $db->getResult();
             $update_result = $db->getResult();
			if (!empty($update_result)) {
				$update_result = 0;
			} else {
				$update_result = 1;
			}

			// check update result
			if ($update_result == 1) {
				
			$error['update_staff'] = " <section class='content-header'><span class='label label-success'>Staff Details updated Successfully</span></section>";
			} else {
				$error['update_staff'] = " <span class='label label-danger'>Failed to update</span>";
			}
		}
	} 


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM staffs WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "staffs.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Staff<small><a href='staffs.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Staffs</a></small></h1>
	<small><?php echo isset($error['update_staff']) ? $error['update_staff'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-10">
		
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Edit Product</h3>
				</div><!-- /.box-header -->
				<!-- form start -->
				<form id="edit_staff_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
					    <input type="hidden" id="old_image" name="old_image"  value="<?= $res[0]['image']; ?>">
						<div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                            <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                            <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Role</label> <i class="text-danger asterik">*</i><?php echo isset($error['role']) ? $error['role'] : ''; ?>
                                        <input type="text" class="form-control" name="role" value="<?php echo $res[0]['role']; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                            <label for="exampleInputEmail1"> Mobile Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                            <input type="number" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Password</label> <i class="text-danger asterik">*</i><?php echo isset($error['password']) ? $error['password'] : ''; ?>
                                        <input type="password" class="form-control" name="password" value="<?php echo $res[0]['password']; ?>" />
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                            <label for="exampleInputEmail1">Github</label> <i class="text-danger asterik">*</i><?php echo isset($error['github']) ? $error['github'] : ''; ?>
                                            <input type="text" class="form-control" name="github" value="<?php echo $res[0]['github']; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Upi</label> <i class="text-danger asterik">*</i><?php echo isset($error['upi']) ? $error['upi'] : ''; ?>
                                        <input type="text" class="form-control" name="upi" value="<?php echo $res[0]['upi']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <br>
						   <div class="row">
							    <div class="form-group">
								    <div class="col-md-6">
                                            <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                            <input type="email" class="form-control" name="email" value="<?php echo $res[0]['email']; ?>" />
                                    </div>
									 <div class="col-md-4">
									     <label for="exampleInputFile">Image</label>
                                        
                                        <input type="file" accept="image/png,  image/jpeg" onchange="readURL(this);"  name="image" id="image">
                                        <p class="help-block"><img id="blah" src="<?php echo $res[0]['image']; ?>" style="height:120px;max-width:100%" /></p>
									 </div>
								</div>
						   </div>
						</div><!-- /.box-body -->
                       
					<div class="box-footer">
						<button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
					
					</div>
				</form>
			</div><!-- /.box -->
		</div>
	</div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
