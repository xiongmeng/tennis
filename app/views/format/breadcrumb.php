<?php if (!empty(Layout::$breadcrumb)) { ?>
    <div class="breadcrumbs margin-bottom-10">
        <div class="container">
            <ul class="pull-left breadcrumb">
                <?php foreach (Layout::$breadcrumb as $index => $breadcrumb) { ?>
                    <?php if ($index == count(Layout::$breadcrumb)-1) { ?>
                        <li class="active"><?= $breadcrumb['label'] ?></li>
                    <?php } else { ?>
                        <li><a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['label'] ?></a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>