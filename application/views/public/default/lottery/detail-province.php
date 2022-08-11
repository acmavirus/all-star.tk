<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
    </div>
</div>
<?php if (!empty($oneItem)) : ?>
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
                    <div id="ajax_content">
                        <?php if (!empty($data_province)) { ?>
                            <?php if ($oneParent->code !== 'XSMB') :  ?>
                                <?php foreach ($data_province as $key => $item) : $newarr[0] = $item ?>
                                    <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $newarr]) ?>
                                <?php endforeach; ?>
                        <?php endif;
                        } ?>
                    </div>
                    <button class="btnLoadMore mt-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>
                    <?php if (!empty($oneItem->content)) : ?>
                        <div class="new-content bg-white mt-3 p-3 text-justify">
                            <?php echo $oneItem->content; ?>
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