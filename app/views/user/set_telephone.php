<?php use Sports\Constant\Finance as FinanceConstant; ?>
<!--=== Content ===-->
<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-12">
            <div class="reg-header">
                <h2>设置预订信息短信通知手机号</h2>
            </div>
            <form class="form-horizontal" action="/set_telephone" method="post">
                <div class="form-group">
                    <label class="col-sm-2 control-label">当前手机号：</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?=$user->telephone?>></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label">新手机号：</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                    </div>
                    <input type="text" name="nickname" placeholder="昵称/手机号" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="btn-u pull-right btn-block" type="submit" value="设置">
                    </div>
                </div>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->