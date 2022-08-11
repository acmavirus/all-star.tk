<?php
$currentResult = explode(',', $data_current->lastResult);
function getResult($array)
{
    $data = array();
    $data['sum'] = array_sum($array);
    $data['even_odd'] = $data['sum'] % 2 === 0 ? 'Chẵn' : 'Lẻ';
    if ($array[0] === $array[1] &&  $array[2] === $array[1]) {
        $data['final'] = 'Bão';
        return $data;
    }

    if ($data['sum'] > 3 && $data['sum'] < 11) {
        $data['final'] = 'Xỉu';
    } else {
        $data['final'] = 'Tài';
    }
    return $data;
}
$arrayResult = getResult($currentResult);

?>
<article class="mb-2">
    <?php if (!empty($data_current)) : ?>
        <h2 class="breadcrumb-table-title">» Hiện tại</h2>

        <div class="game-block d-flex count_ajax">
            <div class="game-detail text-center">
                <h6>Ván hiện tại: <span class="light"><?= $data_current->lastNo ?></span></h6>
                <div class="dice">
                    <?php foreach ($currentResult as $key => $value) : ?>
                        <div class="face face-<?= $value ?> "></div>
                    <?php endforeach; ?>
                </div>
                <p class="text-result">
                    <?php if ($arrayResult['final'] != 'Bão') : ?>
                        <span class="light"><?= $arrayResult['final'] ?></span>
                        <span class="light"><?= $arrayResult['even_odd'] ?></span>
                    <?php else : ?>
                        <span class="light"><?= $arrayResult['final'] ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="time-end text-center">
                <h6>Ván tiếp theo sau: </h6>
                <span class="light"><?= $data_current->stopBetTime - 3 ?> giây</span>
                <p class="watch-result" data-toggle="modal" data-target="#game-modal">Kết quả</p>

                <p class="bet">Đặt cược</p>
            </div>
        </div>
    <?php endif; ?>

</article>

<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Lịch sử kết quả</h2>
    <div id="ajax_content">
        <?php if (count($data->records) > 0) :
            foreach ($data->records as $key => $value) :
                $currentResult = explode(',', $value->prizeResult);
                $arrayResult = getResult($currentResult); ?>
                <div class="game-block result d-flex">
                    <div class="time-end text-center">
                        <span class="light"><?= $value->issueNo ?></span>
                    </div>
                    <div class="game-detail text-center">
                        <div class="dice">
                            <?php foreach ($currentResult as $key => $value) : ?>
                                <div class="face face-<?= $value ?> "></div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-result">
                            <?php if ($arrayResult['final'] != 'Bão') : ?>
                                <span class="light"><?= $arrayResult['final'] ?></span>
                                <span class="light"><?= $arrayResult['even_odd'] ?></span>
                            <?php else : ?>
                                <span class="light"><?= $arrayResult['final'] ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
        <?php endforeach;
        endif; ?>
    </div>
</article>
<button class="btnLoadMore mb-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>
<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Bảng tỉ lệ thắng cược</h2>
    <div class="chance-board">
        <div class="d-flex justify-content-space-between">
            <div class="block-chance" style="flex: 2">
                <div style="width: 50%;" class="wrap">
                    <div class="m-auto chance">Tài</div>
                    <p>1.96</p>
                </div>
                <div style="width: 50%;" class="wrap">
                    <div class="m-auto chance">Xỉu</div>
                    <p>1.96</p>
                </div>
                <div style="width: 50%;" class="wrap">
                    <div class="m-auto chance">Chẵn</div>
                    <p>1.96</p>
                </div>
                <div style="width: 50%;" class="wrap">
                    <div class="m-auto chance">Lẻ</div>
                    <p>1.96</p>
                </div>
            </div>
            <div class="block-chance align-items-center flex-grow-1">
                <div class="wrap">
                    <div class="chance">Bão<br>(1-6)</div>
                    <p>28.00</p>
                </div>
            </div>
        </div>
    </div>
</article>