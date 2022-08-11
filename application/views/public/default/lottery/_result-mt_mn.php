<?php
    $key = random_int(0,1000);
    $reward = [
        0 => 'G8',
        1 => 'G7',
        2 => 'G6',
        3 => 'G5',
        4 => 'G4',
        5 => 'G3',
        6 => 'G2',
        7 => 'G1',
        8 => 'ĐB'
    ];
    if (empty($data)){
        if (empty($dow)) $dow = date('N');
        $cateToDay = getCatChildByDOW($oneItem->id, $dow + 1);
        //covid
        if (!empty($cateToDay)) foreach ($cateToDay as $k => $item){
            //if (in_array($item->code, ['XSKT', 'XSQNG', 'XSDNO', 'XSNT'])) continue;
            $data[$k]['data_result'] = json_encode([[''], [''], ['','',''], [''], ['','','','','','',''], ['',''], [''], [''], ['']]);
            $data[$k]['category_id'] = $item->id;
            $data[$k]['displayed_time'] = date('Y-m-d');
        }
    }
?>
<?php if (!empty($data)): ?>
<div class="table-responsive">
    <div class="result result-multi rounded">
    <table class="table table-bordered table-result mb-0 bg-white text-center fs-18 fs-lg-24 table-result-<?=count($data)?>" data-code="<?php echo $oneItem->code?>">
        <tr>
            <td></td>
            <?php foreach ($data as $i => $item):?>
                <?php $oneCategory = getCateById($item['category_id'])?>
                <td>
                    <a href="<?=getUrlCategory($oneCategory)?>" class="text-black1 fs-14 fs-lg-16 d-block"
                       title="<?=$oneCategory->title?>"><?=$oneCategory->title?></a>
                </td>
            <?php endforeach?>
        </tr>
        <?php foreach ($reward as $k => $titleReward):?>
            <tr>
                <td class="fs-13"><?php echo $titleReward?></td>
                <?php foreach ($data as $item):
                    $dataTable = json_decode($item['data_result']);
                  ?>
                    <td>
                        <?php foreach ($dataTable[$k] as $number):?>
                            <span class="text-number" data-nc="<?php echo $k == 0 ? '2' : ($k == 1 ? '3' : ($k < 4 ? '4' : ($k < 8 ? '5' : '6')))?>">
                                <?php echo !empty($number) ? $number : "<i class='fas fa-spinner fa-pulse'></i>"?>
                            </span>
                        <?php endforeach?>
                    </td>
                <?php endforeach?>
            </tr>
        <?php endforeach?>
    </table>
    <?php echo $this->load->view(TEMPLATE_PATH.'lottery/_result-footer', [], true)?>
    </div>
</div>

<div class="my-3">
    <table class="table table-bordered table-striped table-loto mb-0 table-result-<?php echo count($data)?>" data-code="<?php echo $oneItem->code?>">
        <thead>
        <tr>
            <th class="text-center fw-normal text-white">Đầu</th>
            <?php foreach ($data as $item): $item = (object) $item?>
                <?php $oneCategory = getCateById($item->category_id)?>
                <th class="fw-normal text-white"><?php echo $oneCategory->title?></th>
            <?php endforeach?>
        </tr>
        </thead>
        <tbody class="bg-white">
        <?php for ($k = 0; $k <= 9; $k++):?>
            <tr>
                <td class="text-center fw-bold text-red"><?php echo $k?></td>
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