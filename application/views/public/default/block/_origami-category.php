<?php $allCategory = getCategoryChild(1); ?>
<aside>
    <h3><span>Category</span></h3>
    <ul id="category">
        <?php foreach ($allCategory as $key => $itemCategory) : ?>
        <li><a href="<?= base_url($itemCategory->slug."-page.html"); ?>"><?= $itemCategory->title; ?></a></li>
        <?php endforeach; ?>
    </ul>
</aside>