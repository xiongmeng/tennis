<?php //echo Form::open(array('method' => 'get')) ?>
<?php echo Form::model($queries, array('method' => 'GET'))?>
    <?php echo Form::label('昵称：')?><?php echo Form::input('text', 'nickname')?>
    <?php echo Form::label('手机号：')?><?php echo Form::input('text', 'telephone')?>
    <?php echo Form::submit('查询')?>
<?php echo Form::close() ?>

<div class="container">
    <table>
        <tr>
            <th>昵称</th>
            <th>手机号</th>
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
