<html>
<body>
<div class="header">
    <div class="navbar navbar-default" role="navigation">
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
                <a class="navbar-brand" href="index.html">
                    <img id="logo-header" src="/assets/img/logo.png" alt="Logo">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php if (!$data){ ?>
                    <li class="divider">
                        <a href="login">首页</a>
                        <!--                    <li class="divider">-->
                        <!--                        <a href="instant">即时订场</a>-->
                        <?php } ?>
                        <?php if ($data){ ?>
                        <?php foreach ($data['headers'] as $headerId => $header) { ?>
                        <?php if (in_array($headerId, $data['acl'])){ ?>
                        <?php if ($header['children']){ ?>
                <li class="dropdown">
                <a href="<?php echo $header['url'] ?>" class="dropdown-toggle" data-toggle="dropdown"
                   data-hover="dropdown" data-delay="0" data-close-others="false"><?php echo $header['label'] ?>
                    <!--  <i class="icon-angle-down"></i>-->
                </a>
                <ul class="dropdown-menu">

                    <?php foreach ($header['children'] as $children) { ?>
                        <li><a href="<?php echo $children['url'] ?>"><?php echo $children['label'] ?></a></li>
                    <?php } ?>
                </ul>
                <?php } ?>
                <?php if (!$header['children']) { ?>
                <li class="divider">
                <a href="<?php echo $header['url'] ?>"><?php echo $header['label'] ?>
                    <!--  <i class="icon-angle-down"></i>-->
                </a>
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
</body>
</html>