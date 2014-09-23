
    <div class="content" style="margin-top: 50px">
        <form id="form1" name="form1" action="<?= url_wrapper('/mobile_change_user') ?>" method="post">
            <div class="form-controller">
                <div class="item">
                    <label class="label">账号：</label>
                    <input class="input-normal" type="text" name="nickname" placeholder="昵称/手机号"
                           id="nickname" value="<?=isset($queries['nickname']) ? $queries['nickname'] : ''?>">
                </div>

                <div class="error" id="nick"><?php if ($errors->first('nickname')) {
                        echo $errors->first('nickname');
                    } ?></div>
            </div>

            <div class="form-controller">
                <div class="item">
                    <label class="label">密码：</label>
                    <input class="input-normal" type="password" style="width: 40%" name="password"
                           id="password" value="<?=isset($queries['password']) ? $queries['password'] : ''?>">
                    <a class="input-normal" style="width: 20%; " href="<?= url_wrapper('/get_password?redirect=/mobile_change_user')?>" data-ignore="push">忘记密码？</a>

                </div>

                <div class="error" id="pwd"><?php if ($errors->first('password')) {
                        echo $errors->first('password');
                    } ?></div>
            </div>
            <input type="submit" id="bond" name="bond" style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
                   value="<?=$user->telephone ? '更换账号' : '绑定账号'?>">
        </form>
    </div>