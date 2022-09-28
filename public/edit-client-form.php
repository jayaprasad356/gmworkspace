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
	$address = $db->escapeString($_POST['address']);
	$email = $db->escapeString($_POST['email']);
	$mobile = $db->escapeString($_POST['mobile']);
	$error = array();


        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($email)) {
            $error['email'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($mobile)) {
            $error['mobile'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($address)) {
            $error['address'] = " <span class='label label-danger'>Required!</span>";
        }
       

		

		if (!empty($name) && !empty($email) && !empty($mobile)&& !empty($address))
		 {	
             $sql_query = "UPDATE clients SET name='$name',address='$address',email='$email',mobile='$mobile' WHERE id =  $ID";
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
				
			$error['update_client'] = " <section class='content-header'><span class='label label-success'>Client Details updated Successfully</span></section>";
			} else {
				$error['update_client'] = " <span class='label label-danger'>Failed to update</span>";
			}
		}
	} 


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM clients WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "clients.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Client<small><a href='clients.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Client</a></small></h1>
	<small><?php echo isset($error['update_client']) ? $error['update_client'] : ''; ?></small>
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
				</div><!-- /.box-header -->
				<!-- form start -->
				<form id="edit_client_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                            <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                            <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                        <input type="text" class="form-control" name="email" value="<?php echo $res[0]['email']; ?>"/>
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
                                        <label for="exampleInputEmail1">Address</label> <i class="text-danger asterik">*</i><?php echo isset($error['address']) ? $error['address'] : ''; ?>
                                        <textarea type="text" rows="5" class="form-control" name="address"><?php echo $res[0]['address']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>
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
