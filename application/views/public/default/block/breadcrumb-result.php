<h2 class="breadcrumb-table-title">
    <?php if ($oneParent->slug == "mien-bac") : ?>
        <a href="<?= getUrlCategoryRS($oneParent); ?>" title="Xổ số Miền Bắc - Trực tiếp KQXSMB, XSMB tại trường quay">XSMB</a>
    <?php elseif ($oneParent->slug == "mien-trung" || $oneParent->slug == "mien-nam") : ?>
        <a href="<?= getUrlCategoryRS($oneParent); ?>" title="<?= $oneParent->code; ?> - KQ<?php echo $oneParent->code; ?> - Kết quả xổ số <?php echo $oneParent->title; ?> hôm nay - Xosow.com"><?php echo $oneParent->code; ?></a>
    <?php else : ?>

    <?php endif; ?>
    »
    <a href="<?php echo getUrlWeekday($oneParent, date('w', strtotime($disTime))); ?>" title="<?= $oneParent->code; ?> <?php echo getDayOfWeek($disTime) ?>"><?= $oneParent->code; ?> <?php echo getDayOfWeek($disTime) ?></a>
    »
    <a href="<?= getUrlDate($oneParent, $disTime) ?>" title="<?= $oneParent->code; ?> <?php echo date('d/m/Y', strtotime($disTime)); ?>"><?= $oneParent->code; ?> <?php echo date('d/m/Y', strtotime($disTime)); ?></a>
</h2>