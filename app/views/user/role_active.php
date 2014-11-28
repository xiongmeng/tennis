<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-12">
            <div class="reg-header">
                <h2>请选择您要登录的角色</h2>
            </div>
            <?php $roleOptions = option_roles(); ?>
            <form class="form-horizontal" action="/hall/create" method="post">
                <?php foreach ($roles as $role) { ?>

                <div class="row">
                        <div class="form-group">
                            <a class="btn btn-lg btn-primary btn-block" href="/role/active/<?=$role->role_id?>">我是<?=$roleOptions[$role->role_id];?></a>
                        </div>
                </div>                    <?php } ?>

            </form>
        </div>
    </div>
    <!--/row-->
</div><!--/container-->