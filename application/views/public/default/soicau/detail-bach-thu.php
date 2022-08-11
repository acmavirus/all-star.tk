<?php $reward = ['', 'ĐB', 'G1', 'G2', 'G3', 'G4', 'G5', 'G6', 'G7']; ?>
<style>
    .table-result-soicau-xsmb {
        --bs-table-bg: transparent;
        --bs-table-striped-color: #212529;
        --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
        --bs-table-active-color: #212529;
        --bs-table-active-bg: rgba(0, 0, 0, 0.1);
        --bs-table-hover-color: #212529;
        --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        vertical-align: top;
        border-color: #dee2e6;
    }

    .table-result-soicau-xsmb .text-number {
        letter-spacing: -3px;
    }

    .table-result-soicau-xsmb .w-15 {
        flex-wrap: wrap;
        width: 15%;
    }

    .table-result-soicau-xsmb .w-85 {
        flex-wrap: wrap;
        width: 85%;
    }

    .table-result-soicau-xsmb .w-20 {
        flex-wrap: wrap;
        width: 20%;
    }

    .table-result-soicau-xsmb .w-33 {
        flex-wrap: wrap;
        width: 33%;
    }

    .table-result-soicau-xsmb .text-number {
        color: #3e3f42;
        font-size: 20px;
        line-height: 1.8rem;
    }
</style>
<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600">Soi cầu bạch thủ <?php echo $oneItem->code ?> hôm nay </h1>
    </div>
</div>
<main class="container">
    <div class="row">
        <div class="w-100 mt-2">
            <?php if (!empty($breadcrumb)) echo $breadcrumb ?>
        </div>
    </div>
    <div class="row">
        <section class="col-xl-610 jsSoiCau">
            <div class="viewStatistic">
                <article>
                    <div class="static-search mt-0">
                        <div class="aside-title-red text-center">
                            <h2 class="title"> Soi cầu bạch thủ <?php echo $oneItem->code ?> </h2>
                        </div>
                        <div class="container form-search">
                            <div class="row align-items-end">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-6">
                                    <label class="label">Biên độ ngày</label>
                                    <div class="input-datetick flex-grow-1">
                                        <input id="lich" class="form-control form-control-sm mr-2" type="text" value="<?= date('d/m/Y') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-6"> <label class="label">Số ngày cầu chạy:</label>
                                    <div class="input-group static-kn">
                                        <div class="input-group-append"> <span id="btn-minus" class="btn btn-sm btn-minus d-flex align-items-center"> <i class="fa fa-minus"></i> </span> </div>
                                        <input name="so_cau" class="count form-control form-control-sm" value="<?php echo !empty($_GET['so_cau']) ? $_GET['so_cau'] : '3' ?>" type="number" max="6" min="2">
                                        <div class="input-group-prepend"> <span id="btn-plus" class="btn btn-sm btn-plus d-flex align-items-center"> <i class="fa fa-plus"></i> </span> </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-12 mt-md-0 mt-2">
                                    <button class="btn btn-danger btn-sm btnLogan btnFilterBachThu" type="submit">Soi cầu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="mt-2 bg-white">
                    <div class="logan-result">
                        <div class="title-blue"> Soi cầu bạch thủ <?php echo $oneItem->code ?> hôm nay </div>
                        <div class="container soi-cau">
                            <div class="row cau-bach-thu-loto">
                                <div class="col-12">
                                    <div class="mt-4"> *Hướng dẫn: di chuột đến ô cầu để xem các vị trí tạo cầu. Nhấn vào một ô cầu để xem cách tính cầu đó. Số lần - số lần xuất hiện của cầu tương ứng. <span class="text-primary">Bấm xem số cầu cho từng bộ số</span> để xem thêm. </div>
                                    <div class="border-top"></div>
                                </div>
                                <div class="col-12">
                                    <div class="table-soi-cau">
                                        <div class="overflow-auto my-3">
                                            <table class="table table-bordered text-center text-nowrap table-soicau table-fixed">
                                                <tbody>
                                                    <?php for ($tr = 0; $tr <= 9; $tr++) : ?>
                                                        <tr>
                                                            <td class="fw-bold align-middle fs-13">
                                                                <div class="bg-blue1 h-100 d-flex align-items-center justify-content-center">Đầu <?php echo $tr ?></div>
                                                            </td>
                                                            <?php for ($td = 0; $td <= 9; $td++) : ?>
                                                                <td>
                                                                    <div class="block-number bg-white d-none">
                                                                        <span class="d-block text-red fw-bold"><?php echo $tr . $td ?></span>
                                                                        <span class="text-grey2 fs-9">
                                                                            <number id="count_<?php echo $tr . $td ?>">0</number> lần
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            <?php endfor ?>
                                                        </tr>
                                                    <?php endfor ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div id="kqcaumb">
                <div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i> Loading ...
                </div>
            </div>
            <div id="ketqua" class="mb-3 pt-2 bg-white">
                <?php if ($oneItem->code == 'XSMB') { ?>
                    <?php foreach ($data_soicau['data_list_result'] as $key => $item) { ?><?php $disTime = $item['displayed_time']; ?>
                    <?php $reward = ['', 'ĐB', 'G1', 'G2', 'G3', 'G4', 'G5', 'G6', 'G7'] ?>
                    <div class="table-responsive mt-4">
                        <?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-result', ["oneParent" => $oneParent, "disTime" => $disTime]) ?>
                        <table class="table table-bordered table-result table-result-soicau-xsmb text-center bg-white fs-18 fs-lg-24">
                            <tbody>
                                <?php
                                $data_result = json_decode($item['data_result']);
                                unset($data_result[0]);
                                ?>
                                <?php
                                $countSingleNum = 1;
                                if (!empty($data_result)) foreach ($data_result as $k => $item) : ?>
                                    <tr>
                                        <td class="fs-13"><?php echo $reward[$k] ?></td>
                                        <td>
                                            <?php if (!empty($item)) foreach ($item as $count => $number) :
                                                $arrNum = str_split($number) ?>
                                                <span class="text-number">
                                                    <?php foreach ($arrNum as $singleNum) : ?>
                                                        <span data-number="<?php echo $countSingleNum ?>" class="soicau_mb clickLotoColor" id="mb_<?php echo $countSingleNum ?>_<?php echo date('dmY', strtotime($data['displayed_time'])) ?>"><?php echo $singleNum ?></span>
                                                    <?php $countSingleNum++;
                                                    endforeach ?>
                                                </span>
                                            <?php endforeach ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="new-content p-2 loto-bachthu"> <strong>Loto: </strong>
                        <?php foreach (json_decode($item['loto']) as $item_loto) { ?>
                            <span data-num="<?php echo $item_loto ?>"><?php echo $item_loto ?></span>,
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php foreach ($data_soicau['data_list_result'] as $key => $item) { ?>
                    <?php $disTime = $item['displayed_time']; ?>
                    <?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-result', ["oneParent" => $oneParent, "disTime" => $disTime]) ?>
                    <table class="table table-bordered border-primary table-result-soicau-xsmb">
                        <tbody>
                            <?php
                            $data_result = (array)json_decode($item['data_result']);
                            unset($data_result[0]);
                            ?>
                            <?php
                            $countSingleNum = 1;
                            if (!empty($data_result)) foreach ($data_result as $k => $itemz) : ?>
                                <tr class="d-flex">
                                    <td class="w-15"><?php echo $reward[$k] ?></td>
                                    <td class="w-85 d-flex justify-content-center flex-grow-1 pl-4 pr-4">
                                        <?php if (!empty($itemz)) foreach ($itemz as $count => $number) :
                                            $arrNum = str_split($number) ?>
                                            <span class="<?php echo ($k == 4 || $k == 6) ? 'w-20 ' : ''; ?>text-number ml-4 mr-4">
                                                <?php foreach ($arrNum as $singleNum) :
                                                ?>
                                                    <span data-number="<?php echo $countSingleNum ?>" class="soicau_mb clickLotoColor" id="mb_<?php echo $countSingleNum ?>_<?php echo date('dmY', strtotime($item['displayed_time'])) ?>"><?php echo $singleNum ?></span>
                                                <?php $countSingleNum++;
                                                endforeach ?>
                                            </span>
                                        <?php endforeach ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="new-content p-2 loto-bachthu"> <strong>Loto: </strong>
                        <?php foreach (json_decode($item['loto']) as $item_loto) { ?>
                            <span data-num="<?php echo $item_loto ?>"><?php echo $item_loto ?></span>,
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
        </section>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
        <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
    </div>
</main>

<script defer src="<?php echo site_url('Soicau/' . $js) ?>"></script>
<script defer src="<?php echo TEMPLATE_ASSET . 'js/soicau.js' ?>"></script>
<script type="text/javascript">
    function clickLotoColor(pos) {
        let lt1 = '10';
        let lt2 = '10';
        setFlag();
        setloto(pos);
        <?php if (!empty($data_soicau['data_list_result'])) foreach ($data_soicau['data_list_result'] as $idx => $item) :
            $dateSelector = date('dmY', strtotime($item['displayed_time']));
            if (!empty($data[$idx + 1]))
                $dateSelectorMinus = date('dmY', strtotime($data[$idx + 1]['displayed_time']));
            else $dateSelectorMinus = $dateSelector;
        ?>
            $('#mb_' + String(pos) + '_<?php echo $dateSelector ?>').removeClass("soicau_mb");

            $('#mb_' + String(pos) + '_<?php echo $dateSelector ?>').addClass(classSelected);
            if (pos1 > 0) lt1 = A<?php echo $dateSelectorMinus ?>[pos1 - 1];
            else lt1 = '10';
            if (pos2 > 0) lt2 = A<?php echo $dateSelectorMinus ?>[pos2 - 1];
            else lt2 = '10';
            if ((lt1 != '10') && (lt2 != '10')) {
                for (let index = 0; index < A<?php echo $dateSelectorMinus ?>.length; index++) {
                    if (Aloto[index] == 1) {
                        if ((A<?php echo $dateSelector ?>[index] == lt1) && (A<?php echo $dateSelector ?>[index + 1] == lt2) || (A<?php echo $dateSelector ?>[index] == lt2) && (A<?php echo $dateSelector ?>[index + 1] == lt1)) {
                            $('#mb_' + String(index + 1) + '_<?php echo $dateSelector ?>').removeClass("soicau_mb");
                            $('#mb_' + String(index + 1) + '_<?php echo $dateSelector ?>').removeClass(classSelected);
                            $('#mb_' + String(index + 1) + '_<?php echo $dateSelector ?>').addClass("text-warning");
                            $('#mb_' + String(index + 2) + '_<?php echo $dateSelector ?>').removeClass("soicau_mb");
                            $('#mb_' + String(index + 2) + '_<?php echo $dateSelector ?>').removeClass(classSelected);
                            $('#mb_' + String(index + 2) + '_<?php echo $dateSelector ?>').addClass("text-warning");
                        }
                    }
                }
            }
        <?php endforeach ?>
    }
</script>