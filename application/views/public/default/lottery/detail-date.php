<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
    </div>
</div>
<?php if (!empty($oneParent)) : ?>
    <main>
        <div class="container">
            <?php if (!empty($listweekday)) : ?>
                <div class="row">
                    <div class="w-100">
                        <div class="submenu2">
                            <ul class="text-center text-capitalize rounded lh25 submenu2-bg colum-4 pl-0 pl-md-3">
                                <li class="<?php echo getUrlCategory($oneParent) === current_url() ? 'active' : ''; ?>">
                                    <a href="<?php echo getUrlCategory($oneParent) ?>" title="<?php echo $oneParent->title ?>"><?php echo $oneParent->code ?></a>
                                </li>
                                <?php foreach ($listweekday as $item) :  ?>
                                    <li class="mx-md-4 <?php echo getUrlCategory($item) === current_url() ? 'active' : ''; ?>">
                                        <a href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->title ?>"><?php echo $item->title ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="w-100 mt-2">
                    <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-610">
                    <?php if($oneParent->code == 'MEGA')  :  ?>
                        <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_645') ?>
                    <?php elseif($oneParent->code == 'POWER') :?>
                        <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_655') ?>
                    <?php endif; ?>
                    <div id="ajax_content">
                        <?php if (!empty($data_date)) {
                            if ($oneParent->code === 'XSMB') : ?>
                                <?php foreach ($data_date as $key => $item) : ?>
                                    <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['data_MB' => $item]) ?>
                                <?php endforeach; ?>
                            <?php elseif(in_array(strtoupper($oneParent->code), ['XSMT', 'XSMN'])) : ?>
                                <?php foreach ($data_date as $key => $item) : ?>
                                    <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_date[$key]]) ?>
                                <?php endforeach; ?>
                            <?php elseif($oneParent->code === 'MEGA') : ?>
                                <?php foreach ($data_date as $key => $item) :  ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_645', ['one_data_vietlot' => reset($item)]) ?>
                                <?php endforeach; ?>
                            <?php elseif($oneParent->code === 'POWER') : ?>
                                <?php foreach ($data_date as $key => $item) :  ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_655', ['one_data_vietlot' => reset($item)]) ?>
                                <?php endforeach; ?>
                            <?php elseif($oneParent->code === 'MAX3D') : ?>
                                <?php foreach ($data_date as $key => $item) :  ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_3d', ['one_data_vietlot' => reset($item)]) ?>
                                <?php endforeach; ?>
                            <?php elseif($oneParent->code === 'MAX4D') : ?>
                                <?php foreach ($data_date as $key => $item) :  ?>
                                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_4d', ['one_data_vietlot' => reset($item)]) ?>
                                <?php endforeach; ?>
                        <?php endif;
                        } ?>
                    </div>
                    <?php if (!empty($oneParent->content)) : ?>
                        <div class="new-content bg-white mt-3 p-3 text-justify">
                            <?php echo $oneParent->content; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
                <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
	            <div class="row">
		            <div class="col-12">
			            <div class="new-content ketqua text-justify mt-2 bg-white p-3" id="danh-muc">
                      <?php echo getTableOfContent($oneItem->content) ?>
			            </div>
		            </div>
	            </div>
            </div>
        </div>
    </main>
<?php else : ?>
    <article class="my-2">
        <h3>Đang cập nhật</h3>
    </article>
<?php endif; ?>