<div class="databases">
    <div class="logo"><img src="<?=assets()?>/images/redis.svg"></div>
    <div class="connection-data">
        <p>Redisuper</p>
    </div>
    <ul class="db-list">
        <?php for ($i = 0; $i < $databases; $i++) : ?>
            <li><a data-index="<?=$i?>" href="<?=route('index.entry', ['index' => $i])?>"<?php if ($i == $index){ echo ' class="active"'; } ?>><i class="fa fa-database"></i><span><?=$i?></span></a></li>
        <?php endfor; ?>
    </ul>
</div>