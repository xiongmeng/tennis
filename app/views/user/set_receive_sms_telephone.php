<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-2 col-sm-12">
            <div class="reg-header">
                <h2>设置预订信息短信通知手机号</h2>
            </div>
            <form class="form-horizontal" action="/hall/create" method="post">
                <div class="form-group">
                    <label class="col-sm-4 control-label">当前手机号：</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?=$user->receive_sms_telephone?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="telephone" class="col-sm-4 control-label">新手机号：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="telephone" name="telephone"
                               value="<?= Input::get('telephone')?>" placeholder="输入当前接受短信的手机号">
                    </div>
                    <p class="col-md-8 col-md-offset-4" style="color: red; font-weight: bold"><?php if ($errors->first('telephone')) {
                            echo $errors->first('telephone');
                        } ?></p>
                </div>

                <div class="row">
                    <div class="col-md-4 col-md-offset-4" >
                        <input class="btn btn-primary btn-block" type="submit" value="设置">
                    </div>
                    <div class="col-md-4" >
                        <a class="btn btn-primary btn-block" href="/order_court_manage">直接进入场地管理</a>
                    </div>
                </div>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->