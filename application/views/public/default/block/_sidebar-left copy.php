<?php
$listCategoryYesterday = getCatByWeekDay(date('N'));
//$cat_mien_trung = sidebarCategory(2);
//$cat_mien_nam = sidebarCategory(3);
$lottery = getDataCategory('lottery');
$mientrung = array();
$miennam = array();
foreach ($lottery as $key => $item) {
    if ((int)$item->parent_id === 2) {
        $mientrung[] = $item;
        continue;
    };
    if ((int)$item->parent_id === 3) {
        $miennam[] = $item;
        continue;
    };
}
?>
<div class="col-xl-200 w-100 mx-lg-3 mx-md-0 pr-md-3 pr-lg-0">
    <aside class="border-radius-4">
        <div class="aside-title-red">
            <h3 class="title">Kết quả XS hôm qua</h3>
        </div>
        <nav class="aside-province">
            <ul>
                <?php foreach ($listCategoryYesterday as $key => $item) : ?>
                    <li>
                        <a rel="<?= $item->description ?>" href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo "{$item->title}"; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>
    <aside class="mt-2 border-radius-4">
        <div class="aside-title-red">
            <h3 class="title">Miền Trung</h3>
        </div>
        <nav class="aside-province">
            <ul>
                <?php
                foreach ($mientrung as $key => $item) : ?>
                    <li>
                        <a rel="<?= $item->description ?>" href="<?= getUrlCategory($item) ?>"><?= $item->title ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>
    <aside class="mt-2 border-radius-4">
        <div class="aside-title-red">
            <h3 class="title">Miền Nam</h3>
        </div>
        <nav class="aside-province">
            <ul>
                <?php
                foreach ($miennam as $key => $item) : ?>
                    <li>
                        <a rel="<?= $item->description ?>" href="<?= getUrlCategory($item) ?>"><?= $item->title ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>
</div>