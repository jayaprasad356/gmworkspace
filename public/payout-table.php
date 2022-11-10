
<section class="content-header">
    <h1>Payout /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                       <div class="box-header">

                                <div class="form-group col-md-4">
                                       <h4 class="box-title">Filter by Month </h4>
                                        <select id='month' name="month" class='form-control'>
                                            <option value="">select</option>
                                                <?php
                                                $sql = "SELECT id,month FROM `months`";
                                                $db->sql($sql);
                                                $result = $db->getResult();
                                                $month = date('m');
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?=$month == $value['id'] ? ' selected="selected"' : '';?>><?= $value['month'] ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                        </div>
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table"  data-url="api-firebase/get-bootstrap-table-data.php?table=payout" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="staff_name" data-sortable="true">Staff Name</th>
                                    <th  data-field="total_hours" data-sortable="true">Total Hours</th>
                                    <th  data-field="cost_per_hour" data-sortable="true" >Cost Per Hour</th>
                                    <th  data-field="total_amount" data-sortable="true" >Total Amount</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

 <script>
        $('#staff_id').on('change', function() {
            id = $('#staff_id').val();
            $('#users_table').bootstrapTable('refresh');
        });
        $('#month').on('change', function() {
            id = $('#month_id').val();
            $('#users_table').bootstrapTable('refresh');
        });

function queryParams(p) {
    return {
        "staff_id": $('#staff_id').val(),
        "month": $('#month').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
</script>

