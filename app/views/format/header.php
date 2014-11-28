<div class="container">
    <div class="row header top">
        <div class="col-md-2">
            <a class="navbar-brand" href="javascript:void(0)">
                <img id="logo-header" src="/assets/img/logo.png" alt="Logo">
            </a>
        </div>
        <div class="row col-md-10">
            <div class="top">
                <div class="container">
                    <ul class="loginbar pull-right">
                        <?php if (!empty($user)) { ?>
                            <?php $roleTypes = option_roles();?>
                            <li>欢迎 <?= $roleTypes[current_role()]?><?php if (!empty($roles) && count($roles) > 1) { ?>
                                （<a href="/role/active">切换角色</a>）
                            <?php } ?>:</li>
                            <li><a href="/login"><?php echo $user->nickname ?></a></li>

                            <li class="devider"></li>
                            <li><a href="/logout">退出登录</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>


            <div class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-responsive-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-responsive-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <?php if (isset($headers) && isset($acl)) { ?>
                                <?php foreach ($headers as $headerId => $header) { ?>
                                    <?php if (in_array($headerId, $acl)) { ?>
                                        <li class="dropdown
                                            <?php if (Layout::$highlightHeader == $headerId || (isset($header['children']) && array_key_exists(Layout::$highlightHeader, $header['children']))) { ?>active<?php } ?>">
                                            <a href="<?= $header['url'] ?>"
                                                <?php if (!empty($header['children'])) { ?>
                                                    class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                                <?php } ?>
                                                ><?= $header['label'] ?></a>

                                            <!--二级菜单-->
                                            <?php if (isset($header['children'])) { ?>
                                                <ul class="dropdown-menu">
                                                    <?php foreach ($header['children'] as $childHeaderId => $childHeader) { ?>
                                                        <?php if (in_array($childHeaderId, $acl)) { ?>
                                                            <li class="dropdown <?php if (Layout::$highlightHeader == $childHeaderId) { ?>active<?php } ?>">
                                                                <a href="<?= $childHeader['url'] ?>"><?= $childHeader['label'] ?></a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>

                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
