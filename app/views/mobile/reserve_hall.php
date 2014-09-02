<header class="bar bar-nav">
    <form style="display: inline" class="search">
        <input style="width: 65%;font-size:15px;"
               type="search" name="hall_name" class="pull-left" placeholder="请输入场馆名" value="<?=isset($queries['hall_name']) ? $queries['hall_name'] : ''?>">
        <input type="submit" class="btn btn-primary" value="search">
    </form>
</header>

<div class="bar bar-standard bar-header-secondary" style="padding: 1px;border: none; height: 30px">
    <div class="segmented-control worktable" style="top: 0;margin-bottom: 2px; border: none">
        <?php foreach($types as $typekey =>$type){?>
            <a class="date <?php if($typekey==$curType){echo 'active';}?>" style="font-size: 14px;padding: 10px" href="<?= url_wrapper($type['url'])?>" >
                <?= $type['label']?>
            </a>
        <?php }?>
    </div>
</div>

<!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
<div class="content" style="margin-bottom: 50px; padding-top: 66px">
    <ul class="table-view hall-on-sale">
        <?php if($curType == 'nearby'){?>
            <?php if(!$Halls){?>
                <div class="alert alert-warning"><strong>您还没有同意上传地理位置信息哦！</strong></div>
            <?php }?>
        <?php }elseif ($curType != 'nearby' && $Halls->count() <= 0) {?>
            <div class="alert alert-warning"><strong>未找到有合适场地的场馆！</strong></div>
        <?php } else { ?>
        <?php foreach($halls as $key =>$hall){?>
        <li class="table-view-cell media">
            <a style="padding: 10px" href="<?= url_wrapper('/hall_reserve?hall_id='.$hall['id'])?>"  data-ignore="push">
                <img class="media-object pull-left head-img"
                     src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $hall->id . '.jpg' ?>">
                <div class="media-body description" style="width: 70%; float: left">
                    <p class="name"><?= $hall['name'] ?></p>
                    <p><span class="header">地址：</span><?= $hall['area_text'] ?></p>
                </div>
            </a>
            <a style="padding: 10px" href="<?= url_wrapper('/hall_reserve?hall_id='.$hall['id'])?>"  data-ignore="push">
            <table>
                <thead><tr>
                    <th>
                        时段
                    </th>
                    <th>
                        门市价
                    </th>
                    <th>普通会员</th>
                    <th>金卡会员</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($hall->HallPrices as $price){?>

                    <tr>
                    <td>                    <?=$price['name']?>
                    </td>
                    <td>                    <?=$price['market']?>
                    </td>
                    <td>                    <?=$price['member']?>
                    </td>
                    <td>                    <?=$price['vip']?>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            </a>
        </li>
        <?php }?><?php }?>
    </ul>
</div>
