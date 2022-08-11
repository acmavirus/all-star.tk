<?php
    $reward = [
        0 => '',
        1 => 'ĐB',
        2 => 'G1',
        3 => 'G2',
        4 => 'G3',
        5 => 'G4',
        6 => 'G5',
        7 => 'G6',
        8 => 'G7'
    ];
    if (empty($data['data_result']))
        $arrNumber = [['','',''], [''], [''], ['',''], ['','','','','',''], ['','','',''], ['','','','','',''], ['','',''], ['','','','']];
    else $arrNumber = json_decode($data['data_result'], true);
?>
<div class="table-responsive">
    <div class="result result-single rounded">
        <table class="table table-bordered table-result table-result-xsmb mb-0 bg-white text-center fs-18 fs-lg-24" data-code="XSMB">
            <tr>
                <td></td>
                <td>
                    <div class="d-flex justify-content-around text-red1 fs-17 fs-lg-20">
                        <?php foreach ($arrNumber[0] as $num):?>
                            <span class="py-1 text-number fw-normal"><?php echo !empty($num) ? $num : "<i class='fas fa-spinner fa-pulse'></i>"?></span>
                        <?php endforeach?>
                    </div>
                </td>
            </tr>
            <?php unset($arrNumber[0])?>
            <?php foreach ($arrNumber as $k => $item):?>
                <tr>
                    <td class="fs-13"><?=$reward[$k]?></td>
                    <td>
                        <?php foreach ($item as $number):?>
                            <span class="text-number" data-nc="<?php echo $k<5? '5': ($k<7? '4': ($k==7? '3': '2'))?>"><?=!empty($number) ? $number : "<i class='fas fa-spinner fa-pulse'></i>"?></span>
                        <?php endforeach?>
                    </td>
                </tr>
            <?php endforeach?>
        </table>
        <?php echo $this->load->view(TEMPLATE_PATH.'lottery/_result-footer', [], true)?>
    </div>
</div>
<?php $this->load->view(TEMPLATE_PATH.'lottery/_loto-single')?>