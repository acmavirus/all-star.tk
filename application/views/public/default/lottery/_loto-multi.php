<?php
//covid
if (empty($data)){
    if (empty($dow)) $dow = date('N') + 1;
    if ($oneItem->id == 2) $cateToDay = getCatChildByDOW($oneItem->id, $dow);
    if (!empty($cateToDay)) foreach ($cateToDay as $k => $item){
        //if (in_array($item->code, ['XSKT', 'XSQNG', 'XSDNO', 'XSNT'])) continue;
        $data[$k]['data_result'] = json_encode([[''], [''], ['','',''], [''], ['','','','','','',''], ['',''], [''], [''], ['']]);
        $data[$k]['category_id'] = $item->api_id;
        $data[$k]['displayed_time'] = date('Y-m-d');
    }
}
?>
<?php if (!empty($data)): ?>
<div class="my-3">
    <table class="table table-bordered table-striped table-loto mb-0 table-result-<?php echo count($data)?>" data-code="<?php echo $oneItem->code?>">
        <thead>
        <tr>
            <th class="text-center font-weight-normal text-white">Đầu</th>
            <?php foreach ($data as $item): $item = (object) $item?>
                <?php $oneCategory = getCateById($item->category_id)?>
                <th class="font-weight-normal text-white"><?php echo $oneCategory->title?></th>
            <?php endforeach?>
        </tr>
        </thead>
        <tbody class="bg-white">
        <?php for ($k = 0; $k <= 9; $k++):?>
            <tr>
                <td class="text-center fw-bold text-red1"><?php echo $k?></td>
                <?php foreach ($data as $item): $item = (object) $item;
                    $dataLoto = getLoto($item->data_result);
                   ?>
                    <td>
                        <?php echo $dataLoto[$k]['tail']?>
                    </td>
                    <?php endforeach?>
            </tr>
        <?php endfor?>
        </tbody>
    </table>
</div>
<?php endif; ?>