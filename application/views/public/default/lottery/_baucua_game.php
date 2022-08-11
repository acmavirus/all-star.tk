<?php
$rule = ['ca', 'tom', 'cua', 'bau', 'ga', 'nai'];
?>
<article class="mb-2">
    <h2 class="breadcrumb-table-title">» Hiện tại</h2>
    <div class="game-block d-flex count_ajax">
        <div style="padding-bottom: 18px" class="game-detail text-center ">
            <h6>Ván hiện tại: <span class="light"><?= $data_current->lastNo ?></span></h6>
            <div style="width: unset;" class="dice">
                <?php foreach (explode(',', $data_current->lastResult) as $key => $value) : ?>
                    <div class="baucua <?= $rule[(int)$value - 1] ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="time-end text-center">
            <h6>Ván tiếp theo sau:</h6>
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
            foreach ($data->records as $key => $value) : ?>
                <div class="game-block result d-flex">
                    <div class="time-end text-center">
                        <span class="light"><?= $value->issueNo ?></span>
                    </div>
                    <div class="game-detail text-center">
                        <div class="dice">
                            <?php foreach (explode(',', $value->prizeResult) as $key => $value) : ?>
                                <div class="baucua <?= $rule[(int)$value - 1] ?>"></div>
                            <?php endforeach; ?>
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

    <div class="chance-board">
        <h3>Đơn</h3>
        <div class="block-chance dice">
            <div class="wrap">

                <div class="baucua nai"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">

                <div class="baucua bau"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">

                <div class="baucua ga"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">

                <div class="baucua ca"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">

                <div class="baucua cua"></div>
                <p>1.96</p>
            </div>
            <div class="wrap">

                <div class="baucua tom"></div>
                <p>1.96</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Đôi</h3>
        <div class="block-chance dice">
            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua nai"></div>

                    <div class="baucua nai"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua bau"></div>

                    <div class="baucua bau"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua ga"></div>

                    <div class="baucua ga"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua ca"></div>

                    <div class="baucua ca"></div>
                </div>
                <p>9.81</p>
            </div>

            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua cua"></div>

                    <div class="baucua cua"></div>
                </div>
                <p>9.81</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div>

                    <div class="baucua tom"></div>

                    <div class="baucua tom"></div>
                </div>
                <p>9.81</p>
            </div>
        </div>
    </div>
    <div class="chance-board">
        <h3>Bão</h3>
        <div class="block-chance dice">
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua nai"></div>

                    <div class="baucua nai"></div>

                    <div class="baucua nai"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua bau"></div>

                    <div class="baucua bau"></div>

                    <div class="baucua bau"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua ga"></div>

                    <div class="baucua ga"></div>

                    <div class="baucua ga"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua ca"></div>

                    <div class="baucua ca"></div>

                    <div class="baucua ca"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua cua"></div>

                    <div class="baucua cua"></div>

                    <div class="baucua cua"></div>
                </div>

                <p>169.00</p>
            </div>
            <div style="width: 50%;" class="wrap">
                <div class="div">

                    <div class="baucua tom"></div>

                    <div class="baucua tom"></div>

                    <div class="baucua tom"></div>
                </div>

                <p>169.00</p>
            </div>
        </div>
    </div>
</article>