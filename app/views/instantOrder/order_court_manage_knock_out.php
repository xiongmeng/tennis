<!--=== Content ===-->
<div class="container ">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="\order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                            <h6><?php echo $hall->name; ?></h6></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_manage?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <h4><?= date('m-d', $time) ?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?></h4>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 table-responsive" id="table_court">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav" id="toolbar" data-bind="visible: selected().length > 0">
                    <li><a class="btn btn-primary" data-bind="click: submitSelected">提交</a></li>
                    <li><a class="btn btn-danger" data-bind="click: cancelSelected">取消</a> </li>
                </ul>
            </div>
            <!-- ko if: instantOrdersByHours().length<=0 -->
            <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
            <!-- /ko -->

            <!-- ko if: instantOrdersByHours().length>0 -->
            <table class="table table-court">
                <thead>
                <tr>
                    <th></th>
                    <!-- ko foreach:courts-->
                    <th>
                        <a class="btn btn-primary btn-lg btn-block" data-bind="text: number()+'号场'"></a>
                    </th>
                    <!-- /ko-->
                </tr>
                </thead>
                <tbody>

                <!-- ko foreach: instantOrdersByHours -->
                <tr>
                    <td>
                        <a class="btn btn-primary btn-block btn-lg" data-bind="text: start() + '-' + end()"></a>
                    </td>
                    <!-- ko foreach: instantOrders -->
                    <td>
                        <a data-bind="attr:{class: state_text.hall_class}, css: {active: select}, html: state_text.hall_label, click: $root.select"></a>
                    </td>
                    <!-- /ko -->
                </tr>
                <!-- /ko -->
                </tbody>
            </table>
            <!-- /ko -->
        </div>
    </div>
</div>

<script>
    seajs.use('court/manage', function(courtManage){
        courtManage.init($('#table_court')[0]);

        $('#toolbar').stickUp();
    });
</script>