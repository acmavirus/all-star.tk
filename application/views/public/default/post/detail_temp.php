<?php if(!empty($oneItem)): ?>
    <main class="container main-detail"  data-url="<?php echo getUrlPost($oneItem) ?>" >
        <div class="row">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
            <article class="col-12 col-lg-8">
                <div class="bg-gray p-3">
                    <h1 class="title-home font-weight-bold ">
                        <?php echo getStringCut($oneItem->title,0,120) ?>
                    </h1>
                    <div class="d-flex flex-wrap justify-content-between my-3">
                        <div class="font-13 text-secondary"><?php echo date_post_vn($oneItem->displayed_time) ?> - <?php echo timeAgo($oneItem->displayed_time,"H:i") ?></div>
                        <?php $this->load->view(TEMPLATE_PATH . "block/rate") ?>
                    </div>
                    <div class="font-14 font-weight-bold">
                        <?php echo $oneItem->description ?>
                    </div>
                    <div class="content-news text-justify mt-3">
                        <?php $content = $oneItem->content;
                        $content = str_replace($oneItem->description, "", html_entity_decode($content));
                        $content = str_replace($oneItem->title, "11met - trực tiếp bóng đá", $content);
                        echo getTableOfContent($content)
                        ?>
                    </div>
                    <div class="social-share p-3 text-center border-bottom"></div>
                    <div class="list-tag text-nowrap overflow-auto my-3">
                        <?php if(!empty($data_tag))  foreach ($data_tag as $item): ?>
                            <?php if(!is_null($item->slug) && !is_null($item->title)) : ?>
                            <a href="<?php echo getUrlTag($item) ?>" title="<?php echo $item->title ?>" class="btn btn-secondary text-white mr-2"><?php echo $item->title ?></a>
		                        <?php endif;?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
            <div class="col-12 col-lg-4">
                <?php $this->load->view(TEMPLATE_PATH . 'block/new_post')?>
            </div>
        </div>
    </main>
<?php endif; ?>