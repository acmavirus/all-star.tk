<article class="mb-2 boxSpin">
    <?php $spinTime = array_values(getSpinTime($oneItem->code))[0]; ?>
    <?php if (!empty($itemSpin)) $spinTime = $itemSpin; ?>
    <h2 class="breadcrumb-table-title">
        <?php echo 'Quay thử ' . $oneItem->code . ' Ngày ' . date('j/n/Y', strtotime($spinTime)) ?>
    </h2>
    <?php echo showContainerBanner('table', 0) ?>

    <?php
    if ($oneItem->code == 'XSMB') {
        echo viewResultMB();
    } elseif (in_array($oneItem->code, ['XSMT', 'XSMN'])) {
        echo viewResultMTMN($oneItem, [], date('N', strtotime($spinTime)));
    } else {
        echo viewResultProvince($oneItem);
    }
    ?>
</article>

<script src="/public/js/quay_thu.js"></script>