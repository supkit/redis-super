<div class="contents-header">
    <h2><span class="key-name"><?=$key?></span><span class="tag"><?=$viewType?></span></h2>
</div>
<div class="data-display type-set">
    <table>
        <col width="6%" />
        <col width="80%" />
        <col width="7%" />
        <col width="7%" />
        <tr>
            <th>ID</th>
            <th>MEMBER</th>
            <th>编辑</th>
            <th>删除</th>
        </tr>
        <?php foreach ($value as $id => $key) : ?>
            <tr class="row">
                <td align="center"><?=$id?></td>
                <td><?=$key?></td>
                <td align="center"><i class="md-edit"></i></td>
                <td align="center"><i class="md-delete"></i></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>