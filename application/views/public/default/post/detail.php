<main class="main-home">
    <div class="container">
	    <div class="row">
		    <div class="w-100 mt-2">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
		    </div>
	    </div>
        <div class="row">
            <div class="col-xl-610">
                <article class="my-2 detail-post" data-url="<?= str_replace(BASE_URL, '', getUrlPost($oneItem)) ?>">
                    <div class="bg-white p-2 rounded">
                        <h1 class="font-20 my-2">
                            <strong><?= $SEO['meta_title'] ?></strong>
                        </h1>
                        <div class="d-flex font-13 justify-content-between my-3 flex-wrap">
                            <div class="text-gray">
                                <div class="new-date"><?php echo date_post_vn($oneItem->displayed_time) ?> - <?php echo timeAgo($oneItem->displayed_time, "H:i") ?></div>
                                <div class="allRate">
                                    <input type="range" value="<?= $reviews->avg ?>" step="0.25" id="backing5" style="display: none" />
                                    <div class="rateit rateit-font" data-rateit-backingfld="#backing5" data-rateit-resetable="false" data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5" data-rateit-mode="font" data-rateit-icon="" style="font-family: fontawesome">

                                    </div>
                                    <span class="danhgia">
                                        <?php if (!is_Null($reviews->avg)) : ?>
                                            <span class="avg-rate"><?= $reviews->avg ?></span> /<span>5</span> của
                                            <span class="count-rate"><?= $reviews->count_vote ?></span> đánh giá</span>
                                <?php else : ?>
                                    <span>Chưa có đánh giá nào</span>
                                <?php endif; ?>
                                </span>
                                </div>
                            </div>
                        </div>
                        <h2 class="new-description fulljustify my-3">
                            <strong><?= html_entity_decode($oneItem->description) ?></strong>
                        </h2>
                        <div class="text-center">
                            <?php echo getThumbnail($oneItem, 600, 314) ?>
                        </div>
                        <div class="new-content text-justify mt-2" id="danh-muc">
                            <?php echo getTableOfContent($oneItem->content) ?>
                        </div>
                    </div>
                </article>

            </div>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
        </div>
    </div>
</main>