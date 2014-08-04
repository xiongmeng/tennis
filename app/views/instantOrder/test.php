<?php //echo Form::open(array('method' => 'get')) ?>
<?php echo Form::model($queries, array('method' => 'GET'))?>
<?php echo Form::label('卖家：')?><?php echo Form::input('text', 'seller')?>
<?php echo Form::label('场馆ID：')?><?php echo Form::input('text', 'hall_id')?>
<?php echo Form::label('状态：')?><?php echo Form::input('text', 'state')?>
<?php echo Form::submit('查询')?>
<?php echo Form::close() ?>

<div class="container">
    <table>
        <tr>
            <th>订单号</th>
            <th>场馆ID</th>
            <th>场地类型</th>
            <th>活动时间</th>
            <th>时段</th>
            <th>售价</th>
            <th>卖家</th>
            <th>买家</th>
            <th>状态</th>
        </tr>
        <?php foreach ( $instants as $instant): ?>
            <tr>
                <td><?php echo $instant->id; ?></td>
                <td><?php echo $instant->hall_id; ?></td>
                <td><?php echo $instant->court_tags; ?></td>
                <td><?php echo $instant->event_date; ?></td>
                <td><?php echo $instant->start_hour.'-'.$instant->end_hour; ?></td>
                <td><?php echo $instant->quote_price; ?></td>
                <td><?php echo $instant->seller; ?></td>
                <td><?php echo $instant->buyer; ?></td>
                <td><?php echo $instant->state; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php echo $instants->appends($queries)->links(); ?>
