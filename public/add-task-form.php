<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {

        $project_id = $db->escapeString(($_POST['project_id']));
        $description = $db->escapeString($_POST['description']);
        
        if (empty($project_id)) {
            $error['project_id'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
       
       if (!empty($project_id)  && !empty($description)) 
       {  
            $sql_query = "INSERT INTO tasks (project_id,assign_id,description,status)VALUES('$project_id',1,'$description',0)";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }
            if ($result == 1) {
                $error['add_task'] = "<section class='content-header'>
                                                <span class='label label-success'>Task Added Successfully</span> </section>";
            } else {
                $error['add_task'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add Task <small><a href='tasks.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to tasks</a></small></h1>
    <?php echo isset($error['add_task']) ? $error['add_task'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_staff_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                           <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Select Project</label><i class="text-danger asterik">*</i>
                                            <select id='project_id' name="project_id" class='form-control' required>
                                            <option value="">Select</option>
                                                        <?php
                                                        $sql = "SELECT * FROM `projects`";
                                                        $db->sql($sql);

                                                        $result = $db->getResult();
                                                        foreach ($result as $value) {
                                                        ?>
                                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                            <label for="exampleInputEmail1"> Description</label> <i class="text-danger asterik">*</i><?php echo isset($error['address']) ? $error['address'] : ''; ?>
                                            <textarea type="text" rows="4" class="form-control" name="description" required></textarea>
                                    </div>

                                 </div>
                            </div>
         
                    </div>
                  
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_staff_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            email: "required",
            address: "required",
            mobile:"required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>

<?php $db->disconnect(); ?>