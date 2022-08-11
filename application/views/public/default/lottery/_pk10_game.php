<?php
$currentResult = explode(',', $data_current->lastResult);
function getResult($array)
{
    $data = array();
    $data['sum'] = $array[0] + $array[1];
    $data['even_odd'] = $array % 2 === 0 ? 'Chẵn' : 'Lẻ';
    if ($data['sum'] > 3 && $data['sum'] < 11) {
        $data['final'] = 'Xỉu';
    } elseif ($data['sum'] > 10 && $data['sum'] < 18) {
        $data['final'] = 'Tài';
    } else {
        $data['final'] = 'Bão';
    }
    return $data;
}
$arrayResult = getResult($currentResult);
?>
<article class="mb-2">
    <?php if (!empty($data_current)) : ?>
        <h2 class="breadcrumb-table-title">» Hiện tại</h2>
        <div class="game-block d-flex  count_ajax">
            <div class="game-detail text-center">
                <h6>Ván hiện tại: <span class="light"><?= $data_current->lastNo ?></span></h6>
                <div id="pk" class="dice">
                    <?php foreach ($currentResult as $key => $value) : ?>
                        <div class="pk" data-id="<?= $value ?>"></div>
                    <?php endforeach; ?>
                </div>
                <p class="text-result">
                    <span class="black"><?= $arrayResult['sum'] ?></span>
                    <span class="light"><?= $arrayResult['final'] ?></span>
                    <span class="light"><?= $arrayResult['even_odd'] ?></span>
                </p>

            </div>
            <div class="time-end text-center">
                <h6>Ván tiếp theo sau:</h6>
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
                <div class="game-block d-flex result">
                    <div class="time-end text-center">
                        <span class="light"><?= $value->issueNo ?></span>
                    </div>
                    <div class="game-detail text-center">
                        <div id="pk" class="dice">
                            <?php foreach ($currentResult as $key => $value) : ?>
                                <div class="pk" data-id="<?= $value ?>"></div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-result">
                            <span class="black"><?= $arrayResult['sum'] ?></span>
                            <span class="light"><?= $arrayResult['final'] ?></span>
                            <span class="light"><?= $arrayResult['even_odd'] ?></span>
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
        <h3>Quán quân và á quân</h3>
        <div class="block-chance">
            <div class="wrap">
                <div class="chance">Tài</div>
                <p>2.12</p>
            </div>
            <div class="wrap">
                <div class="chance">Xỉu</div>
                <p>1.95</p>
            </div>
            <div class="wrap">
                <div class="chance">Lẻ</div>
                <p>1.78</p>
            </div>
            <div class="wrap">
                <div class="chance">Chẵn</div>
                <p>2.12</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Quán quân đặc biệt</h3>
        <div class="block-chance">
            <div class="wrap">
                <div class="chance">Tài</div>
                <p>1.95</p>
            </div>
            <div class="wrap">
                <div class="chance">Xỉu</div>
                <p>1.95</p>
            </div>
            <div class="wrap">
                <div class="chance">Lẻ</div>
                <p>1.95</p>
            </div>
            <div class="wrap">
                <div class="chance">Chẵn</div>
                <p>1.95</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Quán quân</h3>
        <div class="block-chance small">
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">1</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">2</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">3</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">4</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">5</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">6</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">7</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">8</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">9</div>
                <p>9.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">10</div>
                <p>9.80</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Tổng quán quân và á quân</h3>
        <div class="block-chance small" style="justify-content: space-evenly;">
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">3</div>
                <p>44.50</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">4</div>
                <p>44.50</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">5</div>
                <p>22.20</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">6</div>
                <p>22.20</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">7</div>
                <p>14.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">8</div>
                <p>14.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">9</div>
                <p>11.10</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">10</div>
                <p>11.10</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">11</div>
                <p>8.90</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">12</div>
                <p>11.10</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">13</div>
                <p>11.10</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">14</div>
                <p>14.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">15</div>
                <p>14.80</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">16</div>
                <p>22.20</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">17</div>
                <p>22.20</p>
            </div>

            <div class="wrap" style="flex-basis:20%">
                <div class="chance">18</div>
                <p>44.50</p>
            </div>
            <div class="wrap" style="flex-basis:20%">
                <div class="chance">19</div>
                <p>44.50</p>
            </div>
        </div>
    </div>
</article>