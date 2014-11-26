<!--=== Content ===-->
<div class="container" id="workspace" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">约球信息(<span style="color: red;font-weight: bold"><?=$states[$seeking->state]?></span>)</div>
                <div class="panel-body">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-3">时间：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('%s日 %s时', substr($seeking->event_date, 0, 10),
                                        display_time_interval($seeking->start_hour, $seeking->end_hour))?>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">地点：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('%s馆 %s片场地', $seeking->Hall->name, $seeking->court_num)?>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">要求：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    级别：<span data-bind="text: tennis_level.text"></span>
                                    性别：<span data-bind="text: sexy.text"></span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">坑位：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('共%s坑，剩余%s坑', $seeking->store, $seeking->on_sale)?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">人均费用：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= $seeking->personal_cost?>元
                                </p>
                            </div>
                        </div>

<!--                        <div class="form-group">-->
<!--                            <label class="control-label col-md-3">活动形式：</label>-->
<!---->
<!--                            <div class="col-md-9" data-bind="foreach: content.options">-->
<!--                                <label class="checkbox-inline">-->
<!--                                    <input type="checkbox" data-bind="attr:{id:id}, value:id,checked:$parent.content">-->
<!--                                    <span data-bind="text:name"></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="form-group">
                            <label class="control-label col-md-3 ">备注：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= $seeking->comment?>
                                </p>
                            </div>
                        </div>

                        <?php $fsm = new SeekingFsm($seeking);?>
                        <div class="form-group">
                            <div class="col-md-12 pull-right">
                                <?php if ($fsm->can('modify')) { ?>
                                    <a class="btn btn-primary" href="/seeking/modify/<?= $seeking->id ?>" target="_blank">修改</a>
                                <?php } ?>
                                <?php if ($fsm->can('open')) { ?>
                                    <a class="btn btn-primary" href="/seeking/operate/<?= $seeking->id ?>/open">开门</a>
                                <?php } ?>
                                <?php if ($fsm->can('close')) { ?>
                                    <a class="btn btn-primary" href="/seeking/operate/<?= $seeking->id ?>/close">关门</a>
                                <?php } ?>
                                <?php if ($fsm->can('cancel')) { ?>
                                    <a class="btn btn-sm btn-primary" href="/seeking/operate/<?= $seeking->id ?>/cancel">取消</a>
                                <?php } ?>
                                <?php if ($fsm->can('increase')) { ?>
                                    <a class="btn btn-primary" href="/seeking/increase/<?= $seeking->id ?>/1">加人</a>
                                <?php } ?>
                                <?php if ($fsm->can('decrease')) { ?>
                                    <a class="btn btn-primary" href="/seeking/decrease/<?= $seeking->id ?>/1">减人</a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-default" id="order">
                <div class="panel-heading">报名信息</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="6%">id</th>
                            <th width="10%">报名时间</th>
                            <th width="12%">报名人</th>
                            <th width="12%">状态</th>
                            <th width="12%">过期时间</th>
                            <th width="12%">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $orderFsm = new SeekingOrderFsm(); ?>
                        <?php foreach ($orders as $order) { ?>
                            <?php $orderFsm->resetObject($order); ?>
                            <tr>
                                <td><?= $order->id; ?></td>
                                <td><?= substr($order->created_at, 0, 16);?></td>
                                <td><?= href_user_detail($order->Joiner->user_id, $order->Joiner->nickname) ?></td>
                                <td><?= $orderStates[$order->state]?></td>
                                <td><?= date('m-d H:i', $order->expire_time);?></td>
                                <td>
                                    <?php if ($orderFsm->can('cancel')) { ?>
                                        <a class="btn btn-primary" href="/seeking/order/operate/<?= $order->id ?>/cancel" target="_blank">取消</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['seeking/seeking'], function (Seeking) {
        var model = new Seeking(<?= json_encode($seeking)?>);
        ko.applyBindings(model, $('#workspace')[0]);
    });


</script>
