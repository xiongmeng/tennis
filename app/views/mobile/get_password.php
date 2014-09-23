<div class="content" style="margin-top: 50px">
    <form style="display: inline" id="from" name="form1" action="<?= url_wrapper('/get_password') ?>" method="post">
        <input type="hidden" name="app_id" value="<?= isset($queries['app_id']) ? $queries['app_id'] : '' ?>">
        <input type="hidden" name="app_user_id"
               value="<?= isset($queries['app_user_id']) ? $queries['app_user_id'] : '' ?>">
        <?php if (isset($queries['redirect'])) { ?>
            <input type="hidden" name="redirect" value="<?= $queries['redirect'] ?>">
        <?php } ?>
        <div class="form-controller">
            <div class="item">
                <label class="label">手机号：</label>
                <input class="input-normal" type="text" name="telephone" placeholder="请输入您绑定的手机号"
                       id="telephone" value="<?= isset($queries['telephone']) ? $queries['telephone'] : '' ?>">
            </div>

            <div class="error" id="error"><?= $error ?></div>
        </div>

        <input type="submit" id="get" name="bond" style="width:90%; margin: 5px auto ;"
               class="btn btn-primary btn-block"
               value="获取密码">

        <a style="width: 80%; margin-left: 10%;float: left;margin-bottom: 18px"
           href="<?= url_wrapper('/get_password') ?>" data-ignore="push">
            <p>如果您长时间未收到短信，可直接拨打（4000 66 5189），由客服人员帮忙找回密码</p></a>
    </form>