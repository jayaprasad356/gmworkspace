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
	$client_name = $db->escapeString($_POST['client_name']);
	$description = $db->escapeString($_POST['description']);
	$error = array();


        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($client_name)) {
            $error['client_name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
      
		if (!empty($name) && !empty($client_name) && !empty($description))
		 {	
             $sql_query = "UPDATE project SET name='$name',client_name='$client_name',description='$description' WHERE id =  $ID";
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
				
			$error['update_project'] = " <section class='content-header'><span class='label label-success'>Project Details updated Successfully</span></section>";
			} else {
				$error['update_project'] = " <span class='label label-danger'>Failed to update</span>";
			}
		}
	} 


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM projects WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "projects.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Project<small><a href='projects.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Projects</a></small></h1>
	<small><?php echo isset($error['update_project']) ? $error['update_project'] : ''; ?></small>
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
				<form id="edit_project_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
						   <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                            <label for="exampleInputEmail1"> Project Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                            <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                    </div>
                                    <div class="col-md-6">
									    <label for="">Client Name</label> <i class="text-danger asterik">*</i>
                                                <select id='client_name' name="client_name" class='form-control' required>
                                                    <option value="">Select</option>
                                                        <?php
                                                        $sql = "SELECT id,name FROM `clients`";
                                                        $db->sql($sql);
                                                        $result = $db->getResult();
                                                        foreach ($result as $value) {
                                                        ?>
													 <option value='<?= $value['name'] ?>' <?= $value['name']==$res[0]['client_name'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Description</label> <i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                        <textarea type="text" rows="5" class="form-control" name="description"><?php echo $res[0]['description']; ?></textarea>
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
