<?php
function getResultHnLottery(object $prize)
{
    $result = array();
    $result['db'] = [$prize->grandPrize];
    $result['g1'] = [$prize->firstPrize];
    $result['g2'] = explode(',', $prize->secondPrize);
    $result['g3'] = explode(',', $prize->thirdPrize);
    $result['g4'] = explode(',', $prize->fourthPrize);
    $result['g5'] = explode(',', $prize->fifthPrize);
    $result['g6'] = explode(',', $prize->sixthPrize);
    $result['g7'] = explode(',', $prize->seventhPrize);
    return $result;
}

$result_current = getResultHnLottery($data_current->hanoiLotteryResultResp);
?>
<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Hiện tại</h2>
    <div class="game-block d-flex  count_ajax">
        <div class="game-detail hanoi text-center">
            <h6>Ván hiện tại: <span class="light"><?= $data_current->lastNo ?></span></h6>
            <div class="result">
                <span class="label ml-2">ĐB</span>
                <?php foreach ($result_current['db'] as $key => $value) {
                ?> <span class="numbers light ml-2"><?= $value ?></span> <?php } ?>
                <i class="mr-4 ml-auto fas fa-chevron-down"></i>
                <div class="detail-result">
                    <div class="py-2">
                        <span class="label mx-2">ĐB</span>
                        <?php foreach ($result_current['db'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G1</span>
                        <?php foreach ($result_current['g1'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G2</span>
                        <?php foreach ($result_current['g2'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G3</span>
                        <?php foreach ($result_current['g3'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G4</span>
                        <?php foreach ($result_current['g4'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G5</span>
                        <?php foreach ($result_current['g5'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G6</span>
                        <?php foreach ($result_current['g6'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                    <div class="py-2">
                        <span class="label mx-2">G7</span>
                        <?php foreach ($result_current['g7'] as $key => $value) { ?>
                            <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="hanoi time-end text-center">
            <h6>Ván tiếp theo sau:</h6>
            <span class="light"><?= $data_current->stopBetTime - 3 ?> giây</span>
            <p class="bet">Đặt cược</p>
        </div>
    </div>
</article>
<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Lịch sử kết quả</h2>
    <div id="ajax_content">
        <?php if (count($data->records) > 0) :
            foreach ($data->records as $key => $value) :
                $result_current = getResultHnLottery($value->hanoiLotteryResultResp); ?>
                <div class="game-block hanoi pb-2 result d-flex">
                    <div class="time-end text-center">
                        <span class="light"><?= $value->issueNo ?></span>
                    </div>
                    <div class="game-detail text-center">
                        <div class="result">
                            <span class="label ml-2">ĐB</span>
                            <?php foreach ($result_current['db'] as $key => $value) {
                            ?> <span class="numbers light ml-2"><?= $value ?></span> <?php } ?>
                            <i class="mr-2 ml-auto fas fa-chevron-down"></i>
                            <div class="detail-result">
                                <div class="py-2">
                                    <span class="label mx-2">ĐB</span>
                                    <?php foreach ($result_current['db'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G1</span>
                                    <?php foreach ($result_current['g1'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G2</span>
                                    <?php foreach ($result_current['g2'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G3</span>
                                    <?php foreach ($result_current['g3'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G4</span>
                                    <?php foreach ($result_current['g4'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G5</span>
                                    <?php foreach ($result_current['g5'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G6</span>
                                    <?php foreach ($result_current['g6'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                                <div class="py-2">
                                    <span class="label mx-2">G7</span>
                                    <?php foreach ($result_current['g7'] as $key => $value) { ?>
                                        <span class="numbers light ml-1"><?= $value ?></span> <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php endforeach;
        endif; ?>
    </div>
</article>
<button class="btnLoadMore mb-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>
<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Bảng tỉ lệ thắng cược</h2>
    <div class="d-flex">
        <div class="w-50 chance-board" style="    border: 1px solid #e3e3e3">
            <h3>Lô 27 giải</h3>
            <div class="block-chance">
                <div class="wrap">
                    <p style="font-weight: bold;">Tỷ lệ: 3.64</p>
                </div>
            </div>
        </div>
        <div class="w-50 chance-board" style="    border: 1px solid #e3e3e3">
            <h3>ĐB-Đề 2 số</h3>
            <div class="block-chance">
                <div class="wrap">
                    <p style="font-weight: bold;">Tỷ lệ: 98.00</p>
                </div>
            </div>
        </div>
    </div>
</article>