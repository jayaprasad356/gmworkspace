<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {

    $project_id = $db->escapeString(($_POST['project_id']));
    $amount = $db->escapeString($_POST['amount']);
    $date = $db->escapeString($_POST['date']);

    // get image info
    $menu_image = $db->escapeString($_FILES['product_image']['name']);
    $image_error = $db->escapeString($_FILES['product_image']['error']);
    $image_type = $db->escapeString($_FILES['product_image']['type']);

    // create array variable to handle error
    $error = array();
    // common image file extensions
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    // get image file extension
    error_reporting(E_ERROR | E_PARSE);
    $extension = end(explode(".", $_FILES["product_image"]["name"]));

    if (empty($project_id)) {
        $error['project_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($amount)) {
        $error['amount'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($date)) {
        $error['date'] = " <span class='label label-danger'>Required!</span>";
    }


    if (!empty($project_id) && !empty($amount) && !empty($date))
     {
        $result = $fn->validate_image($_FILES["product_image"]);
        // create random image file name
        $string = '0123456789';
        $file = preg_replace("/\s+/", "_", $_FILES['product_image']['name']);
        $menu_image = $function->get_random_string($string, 4) . "-" . date("Y-m-d") . "." . $extension;

        // upload new image
        $upload = move_uploaded_file($_FILES['product_image']['tmp_name'], 'upload/receipt/' . $menu_image);

        // insert new data to menu table
        $upload_image = 'upload/receipt/' . $menu_image;



        $sql_query = "INSERT INTO project_bill (project_id,amount,image,date)VALUES('$project_id','$amount','$upload_image','$date')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {

            $error['add_bill'] = "<section class='content-header'>
                                                <span class='label label-success'>Projevt Bill Added Successfully</span> </section>";
        } else {
            $error['add_bill'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Project Bill <small><a href='project-bill.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Project Bill</a></small></h1>

    <?php echo isset($error['add_bill']) ? $error['add_bill'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
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
                <form name="add_project_bill_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['date']) ? $error['date'] : ''; ?>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                            </div>
                            <br>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Project</label> <i class="text-danger asterik">*</i><?php echo isset($error['project_id']) ? $error['project_id'] : ''; ?>
                                    <select id='project_id' name="project_id" class='form-control' required>
                                        <option value="">Select</option>
                                        <?php
                                        $sql = "SELECT id,name FROM `projects`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Amount</label> <i class="text-danger asterik">*</i><?php echo isset($error['amount']) ? $error['amount'] : ''; ?>
                                    <input type="text" class="form-control" name="amount" required />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputFile">Receipt</label> <i class="text-danger asterik">*</i><?php echo isset($error['product_image']) ? $error['product_image'] : ''; ?>
                                    <input type="file" name="product_image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="product_image" required />
                                    <img id="blah" src="#" alt="" />
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
    $('#add_project_bill_form').validate({

        ignore: [],
        debug: false,
        rules: {
            project_id: "required",
            amount: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>

<?php $db->disconnect(); ?>