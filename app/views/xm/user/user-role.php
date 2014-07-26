<?php //echo Form::open(array('method' => 'get')) ?>

<div class="container">
    <table>
        <tr>
            <th>id</th>
            <th>角色名称</th>
        </tr>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?php echo $role->role_id; ?></td>
                <td><?php echo $role->label; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>