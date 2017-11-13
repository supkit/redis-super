<div class="contents-header">
    <h2><span class="key-name"><?=$key?></span><span class="tag"><?=$viewType?></span></h2>
</div>
<?php if ($serialize) :?>
<script>Prism.highlightAll();</script>
<pre><code class="language-php"><?=var_export($value)?></code></pre>
<?php else : ?>
<div class="data-display type-string">
    <div class="string-content">
        <p>
            <?=$value?>
        </p>
    </div>
</div>
<?php endif; ?>