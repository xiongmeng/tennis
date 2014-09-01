<!--==nav==-->
<nav class="bar bar-tab">
    <?php foreach(MobileLayout::$services as $key => $service){?>
        <a class="tab-item <?php if(MobileLayout::$activeService == $key){?>active<?php }?>" href="<?= url_wrapper($service['url']) ?>" data-transition="slide-in" data-ignore="push">
            <span class="tab-label"><?= $service['label']?></span>
        </a>
    <?php }?>
</nav>
<!--==end nav==-->
