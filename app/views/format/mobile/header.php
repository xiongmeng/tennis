<header class="bar bar-nav">
    <?php if(MobileLayout::$previousUrl) {?>
    <a class="icon icon-left-nav pull-left" href="<?= url_wrapper(MobileLayout::$previousUrl)?>" data-ignore="push"></a>
    <?php }?>
    <h1 class="title"><?= MobileLayout::$title?></h1>
    <?php if(MobileLayout::$setUrl) {?>
        <a class="icon icon-gear pull-right" href="<?= url_wrapper(MobileLayout::$setUrl)?>" data-ignore="push">设置</a>
    <?php }?>
</header>