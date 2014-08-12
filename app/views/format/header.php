<div class="header margin-bottom-10">
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
                    <?php foreach ($data['headers'] as $headerId => $header) { ?>
                        <?php if (in_array($headerId, $data['acl'])) { ?>
                            <li class="divider <?php if(strstr($header['url'], Request::decodedPath())){?>active<?php }?>">
                                <a href="<?php echo $header['url'] ?>"><?php echo $header['label'] ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </div>
</div>