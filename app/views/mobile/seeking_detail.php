<div class="content" style="margin-bottom: 50px; padding-top: 66px">

    <?php $levels = option_tennis_level(); ?>
    <?php $sexy = option_sexy(); ?>


    <ul class="table-view" style="color: #777">
        <li class="table-view-cell">
            <label>时间：</label>
            <label><?=
                sprintf('%s日 %s时', substr($seeking->event_date, 0, 10),
                    display_time_interval($seeking->start_hour, $seeking->end_hour))?></label>
        </li>
        <li class="table-view-cell">
            <label>地点：</label>
            <label><?= sprintf('%s馆 %s片场地', $seeking->Hall->name, $seeking->court_num) ?></label>
        </li>
        <li class="table-view-cell">
            <label>坑位：</label>
            <label><?= sprintf('共%s坑，剩余%s坑', $seeking->store, $seeking->on_sale) ?></label>
        </li>
        <li class="table-view-cell">
            <label>级别：</label>
            <label><?= $levels[$seeking->tennis_level] ?>
        </li>
        <?php if ($seeking->sexy != -1) { ?>
            <li class="table-view-cell">
                <label>性别：</label>
                <label><?= $sexy[$seeking->sexy] ?>
            </li>
        <?php } ?>
        <li class="table-view-cell">
            <label>费用：</label>
            <label>人均<?= $seeking->personal_cost ?>元</label>
        </li>
        <?php if (!empty($seeking->comment)) { ?>
            <li class="table-view-cell">
                <label>备注：</label>
                <label><?= $seeking->comment ?></label>
            </li>
        <?php } ?>
    </ul>
</div>
