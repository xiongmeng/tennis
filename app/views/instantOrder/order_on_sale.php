<!--=== Breadcrumbs ===-->
<div class="breadcrumbs margin-bottom-40">
    <div class="container">
        <h1 class="pull-left">即时场地</h1>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->
<div class="container">
    <div class="col-lg-3">
        <div class="input-group">
            <input type="text" class="form-control">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
        </div><!-- /input-group -->
    </div>

</div><br/>
<!--=== Content Part ===-->
<div class="container">
        <div class="row">
    <?php function weekday($time){if(is_numeric($time)){$weekday = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');return $weekday[date('w',$time)];}}?>
    <?php foreach ( $instants as $instant){?>
    <?php   $fsm = new InstantOrderFsm($instant);?>
    <?php if ($fsm->can('buy')){?>

            <div class="col-md-3 col-sm-6">
                <div class="pricing hover-effect">
                    <div class="pricing-head" >
                        <h4><i class="icon-jpy"></i><?php echo strstr($instant->quote_price,'.',TRUE); ?></i>
                        <span><?php echo substr($instant->event_date , 0 , 10).'     '.weekday(strtotime($instant->event_date)); ?></span>
                            <span><?php echo $instant->hall_name.' '.$instant->court_tags; ?></span>
                            <span><?php echo $instant->start_hour.':00-'.$instant->end_hour.':00'; ?></span></h4>
                        <a class="btn-u btn-u-large" href="fsm-operate/<?php echo $instant->id;?>/buy"<i class="icon-shopping-cart">购买</i></a>
                    </div>

                </div>
            </div>


<?php } ?>

    <?php } ?></div>
    <!--/container-->
<!--=== End Content Part ===-->

