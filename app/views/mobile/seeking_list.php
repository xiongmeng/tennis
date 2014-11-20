<header class="bar bar-nav">
    <form style="display: inline" class="search" action="/mobile_home/reserve/recommend">
        <input style="width: 65%;font-size:15px;"
               type="search" name="hall_name" class="pull-left" placeholder="请输入场馆名" value="<?=isset($queries['hall_name']) ? $queries['hall_name'] : ''?>">
        <input type="submit" class="btn btn-primary" value="search">
    </form>
</header>

<div class="content" style="margin-bottom: 50px; padding-top: 66px">
    <ul class="table-view hall-on-sale">
        <?php $levels = option_tennis_level();?>
        <?php foreach($seekingList as $key =>$seeking){?>
        <li class="table-view-cell">
            <a style="padding: 10px" href="<?= url_wrapper('/seeking/detail/' . $seeking->id)?>"  data-ignore="push">
                <img class="media-object pull-left head-img"
                     src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $seeking->hall_id . '.jpg' ?>">
                <div class="media-body description" style="width:52%;float: left">
                    <p><span class="name"><?= $seeking->hall_name ?></span> &nbsp;<span><?=$seeking->court_num?></span>片</p>
                    <p>
                        <span class="header">时间：</span>
                        <span><?= substr($seeking->event_date, 5, 5)?></span>日&nbsp;
                        <span><?= sprintf('%02d',$seeking->start_hour)?></span>-
                        <span><?= sprintf('%02d',$seeking->end_hour)?></span>时
                    <p>
                        <span class="header">坑位：</span>
                        <span style="font-size: 20px;color: red;font-weight: bold"><?= $seeking->on_sale?></span>坑&nbsp;/&nbsp;<span><?= $seeking->store?></span>坑
                    </p>
                </div>
                <div class="price">
                    <p style="text-align: right"><span style="color: darkolivegreen;font-size: 20px;font-weight: bold">
                                <?= $levels[$seeking->tennis_level] ?>
                            </span></p>
                    <p style="text-align: right"><span class="symbol">￥</span><span class="money">
                                <?= intval($seeking->personal_cost) ?>
                            </span></p>
                </div>
            </a>
        </li>
        <?php }?>
    </ul>
</div>
