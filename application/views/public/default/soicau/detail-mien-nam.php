<main class="main-home">
    <div class="container">
        <div class="row">
            <div class="w-100 mt-2">
                <?php if (!empty($breadcrumb)) echo $breadcrumb ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-610">
                <div class="viewStatistic">
                    <article>
                        <div class="static-search">
                            <div class="aside-title-red text-center">
                                <h2 class="title"> Miền Nam </h2>
                            </div>
                            <div class="container form-search">
                                <form>
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-6"> <label for="chon_tinh" class="label">Tỉnh/thành</label>
                                            <div class="select-arow">
                                                <select class="form-control form-control-sm select-logan" name="selectProvince" id="selectProvince">
                                                <?php if (!empty($cate_today)) { ?>
                                                    <?php foreach ($cate_today as $item) { ?>
                                                        <option data-code="<?php echo $item->code ?>" value="<?php echo getUrlSoiCau($item->code) ?>"><?php echo $item->title ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-6"> <label for="chon_tinh" class="label">Chọn thứ</label>
                                            <div class="select-arow">
                                                <select class="form-control form-control-sm select-dow" name="selectProvinc" id="selectProvinc">
                                                    <option value="2">Thứ 2</option>
                                                    <option value="3">Thứ 3</option>
                                                    <option value="4">Thứ 4</option>
                                                    <option value="5">Thứ 5</option>
                                                    <option value="6">Thứ 6</option>
                                                    <option value="7">Thứ 7</option>
                                                    <option selected="" value="8">Chủ Nhật</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mt-md-0 mt-2"> <button data-url="load_logan" id="showSoiCau" class="btn btn-danger btn-sm btnLogan" type="submit">Kết quả </button> </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="static-other">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="border-top"></div>
                                            <div class="p-2 font-16"> Xem thêm: <a href="/soi-cau-bach-thu.html" class="font-weight-bold text-info"><span class="text-red">•</span> Soi cầu Miền Bắc</a> <a href="soi-cau-mien-nam.html" class="font-weight-bold text-info"><span class="text-red">•</span> Soi cầu Miền Nam</a> </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </article>
                    <article class="mt-2 rounded bg-white pb-4">
                        <div class="logan-result">
                            <h3 class="title-red"> Cầu Xổ số Miền Nam </h3>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="font-16 font-weight-bold">Cầu các tỉnh Miền Nam ngày hôm nay, ngày <?php echo date('d/m/Y') ?></div>
                                    <ul class="p-0 style-list list-soi-cau-today mt-3">
                                        <?php if (!empty($cate_today)) { ?>
                                            <?php foreach ($cate_today as $item) { ?>
                                                <li class="pl-2"><span class="text-red">•</span> <a class="text-primary" href="<?php echo getUrlSoiCau($item->code) ?>" title="Soi cầu <?php echo $item->title ?>"><?php echo $item->title ?></a> </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
        </div>
    </div>
</main>