<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!--Basic Table Option (Spacing)-->
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">名称</th>
                    <th width="15%">地址</th>
                    <th width="10%">用户名称</th>
                    <th width="10%">手机/QQ</th>
                    <th width="30%">推荐理由</th>
                    <th width="15%">添加时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($registers as $register) { ?>
                    <?php if ($register instanceof HallRegister) { ?>
                        <tr>
                            <td><?= $register->id ?></td>
                            <td><?= $register->name ?></td>
                            <td><?= Area::area($register->area_text, $register->county, $register->city, $register->province) ?></td>
                            <td><?= $register->username ?></td>
                            <td><?= $register->contact?></td>
                            <td><?= $register->reason ?>片</td>
                            <td><?= date("Y-m-d H:i", $register->createtime) ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>


                </tbody>
            </table>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $registers->links(); ?>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    $(document).ready(function () {
        seajs.use('datetimePicker', function () {
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
        });
    });
</script>
