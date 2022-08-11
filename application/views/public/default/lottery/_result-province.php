<?php
$key = random_int(0, 1000);
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
if (empty($oneParent)) $oneParent = getCateById($oneItem->parent_id);
if (empty($data['data_result']))
    $arrNumber = [[''], [''], ['', '', ''], [''], ['', '', '', '', '', '', ''], ['', ''], [''], [''], ['']];
else $arrNumber = json_decode($data['data_result'], true);
?>
<div class="table-responsive position-relative" style="margin-bottom: 80px; overflow-x:unset">
    <div class="result result-single rounded">
        <table class="table table-bordered table-result table-result-province mb-0 text-center bg-white fs-18 fs-lg-24" data-code="<?php echo $oneItem->code ?>">
            <tbody>
                <?php foreach ($arrNumber as $k => $item) : ?>
                    <tr>
                        <td class="fs-13"><?php echo $reward[$k] ?></td>
                        <td>
                            <?php foreach ($item as $j => $number) : ?>
                                <span class="text-number" data-nc="<?php echo $k == 0 ? '2' : ($k == 1 ? '3' : ($k < 4 ? '4' : ($k < 8 ? '5' : '6'))) ?>">
                                    <?php echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>'; ?>
                                </span>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot style="position: absolute; left: 0; width:100%; box-shadow: 0 4px 6px 0 #d4cdcd;">
                <tr style="width: 100%; display:block">
                    <td colspan="100%" style="width: 100%">
                        <div class="check-radio">
                            <label for="showmb_02021-08-10">
                                <input type="radio" checked="" id="showmb_02021-08-10" name="showmb_2021-08-10" value="0">
                                <span class="checkbox"><i class="fas fa-check"></i></span>
                                <span>Đầy đủ</span>
                            </label>
                            <label for="showmb_22021-08-10">
                                <input type="radio" id="showmb_22021-08-10" name="showmb_2021-08-10" value="2">
                                <span class="checkbox"><i class="fas fa-check"></i></span>
                                <span>2 số</span>
                            </label>
                            <label for="showmb_32021-08-10">
                                <input type="radio" id="showmb_32021-08-10" name="showmb_2021-08-10" value="3">
                                <span class="checkbox"><i class="fas fa-check"></i></span>
                                <span>3 số</span>
                            </label>
                        </div>
                        <div class="check-number">
                            <ul>
                                <li>0</li>
                                <li>1</li>
                                <li>2</li>
                                <li>3</li>
                                <li>4</li>
                                <li>5</li>
                                <li>6</li>
                                <li>7</li>
                                <li>8</li>
                                <li>9</li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <!-- <?php //echo $this->load->view(TEMPLATE_PATH . 'lottery/_result-footer', [], true) ?> -->
    </div>
</div>
<?php $this->load->view(TEMPLATE_PATH . 'lottery/_loto-single') ?>