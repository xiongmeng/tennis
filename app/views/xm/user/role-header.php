<?php //echo Form::open(array('method' => 'get')) ?>

<div class="container">
    <table>
        <tr>
            <th>id</th>
            <th>导航名称</th>
        </tr>
        <?php foreach ($headers as $header): ?>
            <tr>
                <td><?php echo $header->header_id; ?></td>
                <td><?php echo $header->label; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>