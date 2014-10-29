<!--=== Content ===-->

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">基本信息
                    (<?php if ($user->privilege == USER_PRIVILEGE_VIP) { ?>
                        <span style="font-weight: bold; color: red">金卡会员</span>
                    <?php } else { ?>
                        <span style="font-weight: bold;">普通会员</span>
                    <?php } ?>
                    )
                </div>
                <div class="panel-body">
                    <div class="item col-md-6">
                        <span class="title">昵称：</span><span class="content"><?= $user->nickname ?></span>
                    </div>
                    <div class="item col-md-6">
                        <span class="title">手机号：</span><span class="content"><?= $user->telephone ?></span>
                    </div>
                    <div class="item col-md-6">
                        <span class="title">姓名：</span><span class="content"><?= $user->realname ?></span>
                    </div>
                    <div class="item col-md-6">
                        <span class="title">账户余额：</span><span class="content"><?= cache_balance($user->user_id) ?>
                            元</span>
                    </div>
                    <div class="item col-md-6">
                        <span class="title">积分：</span><span class="content"><?= cache_points($user->user_id) ?>分</span>
                    </div>
                    <div class="item col-md-6">
                        <span class="title">性别：</span><span class="content"><?php $sexy = option_sexy();
                            echo isset($sexy[$user->sexy]) ? $sexy[$user->sexy] : '未知'?></span>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">微信信息</div>
                <div class="panel-body">
                    <?php if (empty($weChatProfile)) { ?>
                        <div class="alert alert-warning col-md-12">该用户还未绑定微信号</div>
                    <?php } else { ?>
                        <div class="item col-md-6">
                            <span class="title">openid：</span><span class="content"><?= $weChatProfile->openid ?></span>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">昵称：</span><span class="content"><?= $weChatProfile->nickname ?></span>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">国家：</span><span class="content"><?= $weChatProfile->country ?></span>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">省份：</span><span class="content"><?= $weChatProfile->province ?></span>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">城市：</span><span class="content"><?= $weChatProfile->city ?></span>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">头像：</span>
                            <?php if ($weChatProfile->headimgurl) { ?>
                                <a target="_blank" href="<?= $weChatProfile->headimgurl ?>">点击查看</a>
                            <?php } ?>
                        </div>
                        <div class="item col-md-6">
                            <span class="title">性别：</span><span class="content">
                                <?php if ($weChatProfile->sexy == 1) {
                                    echo '男';
                                } elseif ($weChatProfile->sexy == 2) {
                                    echo '女';
                                } else {
                                    echo '';
                                } ?>
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>