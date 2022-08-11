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
    <h2 class="breadcrumb-table-title">» Hiện tại</h2>
    <div class="game-block d-flex  count_ajax">
        <div class="game-detail text-center">
            <h6>Ván hiện tại: <span class="light"><?= $data_current->lastNo ?></span></h6>
            <div class="dice">
                <?php foreach ($currentResult as $key => $value) : ?>
                    <div class="face face-<?= $value ?> "></div>
                <?php endforeach; ?>
            </div>
            <p class="text-result">
                <?php if ($arrayResult['final'] != 'Bão') : ?>
                    <span class="black"><?= $arrayResult['sum'] ?></span>
                    <span class="light"><?= $arrayResult['final'] ?></span>
                    <span class="light"><?= $arrayResult['even_odd'] ?></span>
                <?php else : ?>
                    <span class="light"><?= $arrayResult['final'] ?></span>
                <?php endif; ?>
            </p>
        </div>
        <div class="time-end text-center">
            <h6>Hết ván sau:</h6>
            <?php  ?>
            <span class="light"><?= $data_current->stopBetTime - 3 ?> giây</span>
            <p class="watch-result" data-toggle="modal" data-target="#game-modal">Kết quả</p>

            <p class="bet">Đặt cược</p>


        </div>
    </div>
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
                            <?php foreach (explode(',', $value->prizeResult) as $key => $value) : ?>
                                <div class="face face-<?= $value ?> "></div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-result">
                            <?php if ($arrayResult['final'] != 'Bão') : ?>
                                <span class="black"><?= $arrayResult['sum'] ?></span>
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
        <h3>Tài Xỉu</h3>
        <div class="block-chance">
            <div class="wrap">
                <div class="chance">Tài</div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="chance">Xỉu</div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="chance">Chẵn</div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="chance">Lẻ</div>
                <p>1.96</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Đơn</h3>
        <div class="block-chance dice">
            <div class="wrap">
                <div class="face face-1"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="face face-2"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="face face-3"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="face face-4"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="face face-5"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">
                <div class="face face-5"></div>
                <p>1.96</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Đôi</h3>
        <div class="block-chance dice">
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-1"></div>
                    <div class="face face-1"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-2"></div>
                    <div class="face face-2"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-3"></div>
                    <div class="face face-3"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-4"></div>
                    <div class="face face-4"></div>
                </div>
                <p>9.81</p>
            </div>

            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-5"></div>
                    <div class="face face-5"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-6"></div>
                    <div class="face face-6"></div>
                </div>
                <p>9.81</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Tiến lên</h3>
        <div class="block-chance dice">
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-1"></div>
                    <div class="face face-2"></div>
                    <div class="face face-3"></div>
                </div>
                <p>30.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-2"></div>
                    <div class="face face-3"></div>
                    <div class="face face-4"></div>
                </div>
                <p>30.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-3"></div>
                    <div class="face face-4"></div>
                    <div class="face face-5"></div>
                </div>
                <p>30.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>
                    <div class="face face-4"></div>
                    <div class="face face-5"></div>
                    <div class="face face-6"></div>
                </div>
                <p>30.00</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Bão</h3>
        <div class="block-chance dice">
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-1"></div>
                    <div class="face face-1"></div>
                    <div class="face face-1"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-2"></div>
                    <div class="face face-2"></div>
                    <div class="face face-2"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-3"></div>
                    <div class="face face-3"></div>
                    <div class="face face-3"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-4"></div>
                    <div class="face face-4"></div>
                    <div class="face face-4"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-5"></div>
                    <div class="face face-5"></div>
                    <div class="face face-5"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">
                    <div class="face face-6"></div>
                    <div class="face face-6"></div>
                    <div class="face face-6"></div>
                </div>

                <p>169.00</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Tổng</h3>
        <div class="block-chance small">
            <div class="wrap">
                <div class="chance">4</div>
                <p>64.08</p>
            </div>
            <div class="wrap">
                <div class="chance">5</div>
                <p>32.04</p>
            </div>
            <div class="wrap">
                <div class="chance">6</div>
                <p>19.22</p>
            </div>
            <div class="wrap">
                <div class="chance">7</div>
                <p>12.81</p>
            </div>
            <div class="wrap">
                <div class="chance">8</div>
                <p>9.15</p>
            </div>
            <div class="wrap">
                <div class="chance">9</div>
                <p>7.68</p>
            </div>
            <div class="wrap">
                <div class="chance">10</div>
                <p>7.12</p>
            </div>
            <div class="wrap">
                <div class="chance">11</div>
                <p>7.12</p>
            </div>
            <div class="wrap">
                <div class="chance">12</div>
                <p>7.68</p>
            </div>
            <div class="wrap">
                <div class="chance">13</div>
                <p>9.15</p>
            </div>
            <div class="wrap">
                <div class="chance">14</div>
                <p>12.81</p>
            </div>
            <div class="wrap">
                <div class="chance">15</div>
                <p>19.22</p>
            </div>
            <div class="wrap">
                <div class="chance">16</div>
                <p>32.04</p>
            </div>
            <div class="wrap">
                <div class="chance">17</div>
                <p>64.08</p>
            </div>
        </div>
    </div>
</article>