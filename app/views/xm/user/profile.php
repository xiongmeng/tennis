<?php //echo Form::open(array('method' => 'get')) ?>
<?php echo Form::model($queries, array('method' => 'GET'))?>
    <?php echo Form::label('卖家：')?><?php echo Form::input('text', 'nicknam')?>

    <?php echo Form::submit('查询')?>
<?php echo Form::close() ?>

<div class="container">
    <table>
        <tr>
            <th>订单号</th>
            <th>场馆ID</th>

        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user->nickname; ?></td>
                <td><?php echo $user->telephone; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php echo $users->appends($queries)->links(); ?>
