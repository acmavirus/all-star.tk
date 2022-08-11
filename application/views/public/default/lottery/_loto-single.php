<?php
if (empty($data['data_result']))
    $arrNumber = [['','',''], [''], [''], ['',''], ['','','','','',''], ['','','',''], ['','','','','',''], ['','',''], ['','','','']];
else $arrNumber = json_decode($data['data_result'], true);
?>
<div class="loto loto-multi rounded">
    <?php $lotoMB = getLoto($arrNumber)?>
    <table class="table table-bordered table-striped mb-0 table-loto table-loto-single mb-0">
        <thead class="text-white">
        <tr>
            <th class="text-center font-weight-normal">Đầu</th>
            <th class="font-weight-normal">Lô tô</th>
            <th class="text-center font-weight-normal">Đuôi</th>
            <th class="font-weight-normal">Lô tô</th>
        </tr>
        </thead>
        <tbody class="bg-white">
        <?php foreach ($lotoMB as $number => $item):?>
            <tr>
                <td class="text-center fw-bold text-red1"><?php echo $number?></td>
                <td>
                    <?php echo $item['tail']?>
                </td>
                <td class="text-center fw-bold text-red1"><?php echo $number?></td>
                <td>
                    <?php echo $item['head']?>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
</div>