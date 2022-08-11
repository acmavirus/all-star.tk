<div class="container">
      <div style="margin-top: 8px;width: 100%" class="text-center">
            <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?php echo (!empty($SEO)) ? $SEO['meta_title'] : '' ?></h1>
      </div>
</div>

<main class="main-home">
    <div class="container">
        <div class="row main-content">
            <div class="col-xl-610">
                <aside class="mb-2 border-radius-4">
                    <div class="aside-title-red text-center title">
                        <p class="title"> QUAY THỬ XỔ SỐ </p>
                    </div>
                    <div class="container-fluid mb-4 mt-3 spin-search">
                        <form>
                            <div class="row align-items-end">
                                <div class="col-12 text-center">
                                    <div class="check-radio">
                                        <?php if ($oneItem->parent_id == 0) { ?>
                                            <label for="list_region">
                                                <input type="radio" checked="" id="list_region" value="1" name="quaythu">
                                                <span class="checkbox"><i class="fas fa-check"></i></span>
                                                <span>Quay thử theo miền</span>
                                            </label>
                                            <label for="list_province">
                                                <input type="radio" id="list_province" name="quaythu">
                                                <a class="text-black2" href="<?=getUrlQuayThu($list_province[0]->code)?>" title="Quay thử <?=$list_province[0]->title?>">
                                                    <span class="checkbox"><i class="fas fa-check"></i></span>
                                                    <span>Quay thử theo tỉnh</span>
                                                </a>
                                            </label>
                                        <?php } else { ?>
                                            <label for="list_region">
                                                <input type="radio" id="list_region" name="quaythu">
                                                <a class="text-black2" href="<?=getUrlQuayThu($list_parent[0]->code)?>" title="Quay thử <?=$list_parent[0]->title?>">
                                                    <span class="checkbox"><i class="fas fa-check"></i></span>
                                                    <span>Quay thử theo miền</span>
                                                </a>
                                            </label>
                                            <label for="list_province">
                                                <input type="radio" checked="" id="list_province" value="2" name="quaythu">
                                                <span class="checkbox"><i class="fas fa-check"></i></span>
                                                <span>Quay thử theo tỉnh</span>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center spin-select p-0 mt-3">
                                    Chọn miền
                                    <?php
                                        if ($oneItem->parent_id == 0)
                                            $show_province = $list_parent;
                                        else
                                            $show_province = $list_province;
                                    ?>
                                    <select class="selected custom-select text-form w-50 text-capitalize" id="selectProvince">
                                        <?php foreach ($show_province as $item) { ?>
                                            <option value="<?php echo getUrlQuayThu($item->code)?>" <?php echo $item->code == $oneItem->code ? 'selected' : ''?>>
                                                <?php echo $item->title ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button class="btn btn_spin btn-danger">Quay thử</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </aside>
                <article class="mb-2 boxSpin">
                    <?php $spinTime = array_values(getSpinTime($oneItem->code))[0];?>
                    <h2 class="breadcrumb-table-title">
                        <?php echo 'Quay thử ' .$oneItem->code. ' Ngày '. date('j/n/Y', strtotime($spinTime)) ?>
                    </h2>

                    <?php
                        if ($oneItem->code == 'XSMB') {
                            echo viewResultMB();
                        } elseif (in_array($oneItem->code, ['XSMT', 'XSMN'])) {
                            echo viewResultMTMN($oneItem, [], date('N', strtotime($spinTime)));
                        } else {
                            echo viewResultProvince($oneItem);
                        }
                    ?>
                </article>
            </div>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>

            <?php if (!empty($oneItem->content)) : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="new-content mb-2 bg-white mt-3 p-3 text-justify">
                            <?php
                                $content = str_replace('span', 'p', $oneItem->content);
                                echo $content;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="/public/js/quay_thu.js"></script>
<script>
    document.getElementById("selectProvince").onchange = function() {
        let url = document.getElementById("selectProvince").value;
        location.assign(url);
    };
</script>