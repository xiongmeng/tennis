<!--=== Content ===-->

<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-12" id="worktable">
            <div class="panel panel-default" id="base">
                <div class="panel-heading">场馆基本信息</div>
                <div class="panel-body">
                    <form class="form-horizontal" action="/hall/create" method="post">
                        <div class="form-group">
                            <label class="control-label col-md-3">场馆名称：</label>

                            <div class="col-md-8">
                                <input class="form-control" placeholder="场馆名称" type="text" name="name" value="<?= Input::get('name')?>">
                            </div>

                            <p class="col-md-8 col-md-offset-3 error"><?php if ($errors->first('name')) {
                                    echo $errors->first('name');
                                } ?></p>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">简称：</label>

                            <div class="col-md-8">
                                <input class="form-control" placeholder="场馆简称" type="text" name="code" value="<?= Input::get('code')?>">
                            </div>
                            <p class="col-md-8 col-md-offset-3 error"><?php if ($errors->first('code')) {
                                    echo $errors->first('code');
                                } ?></p>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button class="btn-u btn-u-green btn-block" >提交保存，之后可以继续补充其他信息</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>