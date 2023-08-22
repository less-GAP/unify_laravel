<?php foreach ($tags as $index => $tag) { ?>
    <a class="<?=$class?$class:'hover:text-black'?>" title="<?= $tag ?>" href="<?= tag_url($tag) ?>"><?= $tag ?> </a><?= $index < count($tags)-1 ? ', ' : '' ?>
<?php } ?>
