<header class="bar bar-nav">
    <?php if(MobileLayout::$previousUrl) {?>
    <a class="icon icon-left-nav pull-left" href="<?= url_wrapper(MobileLayout::$previousUrl)?>" data-ignore="push"></a>
    <?php }?>
    <h1 class="title"><?= MobileLayout::$title?></h1>
</header>