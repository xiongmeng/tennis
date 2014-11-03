
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?= Form::input('text', 'id', null,
                    array('class' => 'form-control', 'placeholder' => 'ID'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'name', null,
                    array('class' => 'form-control', 'placeholder' => '名称'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'court_name', null,
                    array('class' => 'form-control', 'placeholder' => '场地类型'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'court_num_lower_bound', null,
                    array('class' => 'form-control', 'placeholder' => '场地数目下限'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'court_num_upper_bound', null,
                    array('class' => 'form-control', 'placeholder' => '场地数目上限'))?>
            </div>
            <div class="form-group">
                <?= Form::select('stat', $stats, null, array('class' => 'form-control'))?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?= Form::close() ?><br/>
            <div class="tab-v1">
                <ul class="nav nav-tabs">
                    <?php foreach ($tabs as $tabKey => $tab) { ?>
                        <li class="<?php if ($tabKey == $curTab) { ?>active<?php } ?>">
                            <a href="<?php echo $tab['url'] ?>"><?php echo $tab['label'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!--Basic Table Option (Spacing)-->
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="5%">排序</th>
                    <th width="5%">ID</th>
                    <th>名称</th>
                    <th width="15%">地址</th>
                    <th>电话</th>
                    <th width="10%">场地</th>
                    <th width="10%">添加时间</th>
                    <th width="8%">状态</th>
                    <th width="12%">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($halls as $hall) { ?>
                        <tr>
                            <td><?= $hall->sort; ?></td>
                            <td><?= $hall->id?></td>
                            <td><a href="/hall/detail/<?= $hall->id?>" target="_blank"><?= $hall->name?></a></td>
                            <td>TBD</td>
                            <td><?= $hall->telephone; ?></td>
                            <td><?= $hall->court_name . ' ' . $hall->court_num?>片</td>
                            <td><?= date("Y-m-d", $hall->createtime)?></td>
                            <td><?= $stats[$hall->stat]?></td>
                            <td></td>
                        </tr>

                    <?php } ?>


                </tbody>
            </table>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $halls->appends($queries)->links(); ?>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    $(document).ready(function(){
        seajs.use('datetimePicker', function(){
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
        });
    });
</script>
