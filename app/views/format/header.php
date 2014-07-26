<html>
<body>
<div class="header">
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">
                    <img id="logo-header" src="assets/img/logo.png" alt="Logo">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="devider">
                        <a href="#"></a>
                    </li>
                    <?php foreach($headers as $headerId => $header) { ?>
                        <?php if(in_array($headerId, $acl)){ ?>
                        <li class="devider">
                            <a href="<?php echo $header['url']?>"><?php echo $header['label']?></a>
                        </li>
                        <?php } ?>
                    <?php } ?>

                    <!--<li class="hidden-sm"><a class="search"><i class="icon-search search-btn"></i></a></li>-->
                </ul>
                <!--<div class="search-open">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn-u" type="button">Go</button>
                        </span>
                    </div><!-- /input-group
                </div>-->
            </div><!-- /navbar-collapse -->
        </div>
    </div>
</div>
</body>
</html>