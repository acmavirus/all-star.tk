<?php
$listCategoryYesterday = getCatByWeekDay(date('N'));
$mientrung = sidebarCategory(2);
$miennam = sidebarCategory(3);

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
                        <?php if ($item->slug == "mien-bac") : ?>
                            <a rel="<?= $item->description ?>" href="<?php echo getUrlCategoryRS($item) ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo "{$item->title}"; ?></a>
                        <?php else : ?>
                            <a rel="<?= $item->description ?>" href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo "{$item->title}"; ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>
    <?php $this->load->view(TEMPLATE_PATH . 'block/banner_fixed_left') ?>
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