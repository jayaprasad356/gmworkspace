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
	    $error = array();
        $date = $db->escapeString($fn->xss_clean($_POST['date']));
        $name = $db->escapeString($fn->xss_clean($_POST['name']));
		$status = $db->escapeString($fn->xss_clean($_POST['status']));

        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($date)) {
            $error['date'] = " <span class='label label-danger'>Required!</span>";
        }
        
        if ( !empty($name) && !empty($date))
        {	
		    $project_id = $db->escapeString($fn->xss_clean($_POST['project_id']));
            $description = $db->escapeString($fn->xss_clean($_POST['description']));
            $hours = $db->escapeString($fn->xss_clean($_POST['hours'])); 
             $sql_query = "UPDATE timesheets SET date='$date',staff_id='$name',project_id='$project_id',description='$description',hours='$hours',status='$status' WHERE id =$ID";
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
				
			$error['update_timesheet'] = " <section class='content-header'><span class='label label-success'>Timesheet Details updated Successfully</span></section>";
			} else {
				$error['update_timesheet'] = " <span class='label label-danger'>Failed to update</span>";
			}
		}
	} 
// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM timesheets WHERE id=$ID";
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "timesheets.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Timesheet<small><a href='timesheets.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Timesheets</a></small></h1>
	<small><?php echo isset($error['update_timesheet']) ? $error['update_timesheet'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-12">
		
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form id="edit_timesheet_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
				     	<div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1"> Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <select id='name' name="name" class='form-control'>
                                        <?php
                                        $sql = "SELECT * FROM `staffs`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                         <option value='<?= $value['id'] ?>' <?=$res[0]['staff_id'] == $value['id'] ? ' selected="selected"' : '';?>><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Date</label><i class="text-danger asterik">*</i>
                                    <input type="date" class="form-control" name="date" value="<?php echo $res[0]['date'] ?>" />
                               </div>
                            </div>
                        </div>
                        <br>
						<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Project Name</label><i class="text-danger asterik">*</i>
										<select id='project_id' name="project_id" class='form-control' required>
										<option value="">Select</option>
													<?php
													$sql = "SELECT * FROM `projects`";
													$db->sql($sql);

													$result = $db->getResult();
													foreach ($result as $value) {
													?>
														<option value='<?= $value['id'] ?>' <?=$res[0]['project_id'] == $value['id'] ? ' selected="selected"' : '';?>><?= $value['name'] ?></option>
													<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i>
										<textarea type="text" rows="4" class="form-control" name="description"><?php echo $res[0]['description'] ?></textarea>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Worked Hours</label><i class="text-danger asterik">*</i>
										<input type="number" class="form-control" name="hours" value="<?php echo $res[0]['hours'] ?>" />
									</div>
								</div>
                            </div>
							<div class="row">
									<div class="form-group col-md-4">
										<label class="control-label">Status</label><i class="text-danger asterik">*</i>
										<div id="status" class="btn-group">
											<label class="btn btn-success" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
												<input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Verified
											</label>
											<label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
												<input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Not-verified
											</label>
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
