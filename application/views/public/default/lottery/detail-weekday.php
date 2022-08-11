<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
    </div>
</div>
<?php if (!empty($oneItem)) : ?>
    <main>
        <div class="container">
            <?php $this->load->view(TEMPLATE_PATH . 'block/breadcrumb-weekday') ?>
            <div class="row">
                <div class="w-100 mt-2">
                    <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-610">
                    <?php if ($oneParent->code == 'MEGA') :  ?>
                        <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_645') ?>
                    <?php elseif ($oneParent->code == 'POWER') : ?>
                        <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_655') ?>
                    <?php endif; ?>
                    <div id="ajax_content">
                        <?php if (!empty($data_api)) {
                            if ($oneParent->code === 'XSMB') : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php foreach ($item as $key1 => $item1) : ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['data_MB' => $item[$key1][0]]) ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php elseif (in_array(strtoupper($oneParent->code), ['XSMT', 'XSMN'])) : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $item]) ?>
                                <?php endforeach; ?>
                            <?php elseif ($oneParent->code === 'MEGA') : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php foreach ($item as $key1 => $item1) : ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_645', ['one_data_vietlot' => $item[$key1][0]]) ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php elseif ($oneParent->code === 'POWER') : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php foreach ($item as $key1 => $item1) : ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_655', ['one_data_vietlot' => $item[$key1][0]]) ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php elseif ($oneParent->code === 'MAX3D') : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php foreach ($item as $key1 => $item1) : ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_3d', ['one_data_vietlot' => $item[$key1][0]]) ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php elseif ($oneParent->code === 'MAX4D') : ?>
                                <?php foreach ($data_api as $key => $item) : ?>
                                    <?php foreach ($item as $key1 => $item1) : ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_4d', ['one_data_vietlot' => $item[$key1][0]]) ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                        <?php endif;
                        } ?>
                    </div>
                    <button class="btnLoadMore mt-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>
                    <?php if (!empty($oneItem->content)) : ?>
                        <div class="new-content bg-white mt-3 p-3 text-justify">
                            <?php echo getTableOfContent($oneItem->content) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
                <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
            </div>
        </div>
    </main>
<?php else : ?>
    <article class="my-2">
        <h3>Đang cập nhật</h3>
    </article>
<?php endif; ?>