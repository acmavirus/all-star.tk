<?php if (!empty($oneItem)): ?>
    <main class="container">
        <div class="row">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
		        <div class="col-12 col-md-6">
	              <?php echo showContainerBanner('top_left', 0, '') ?>
		        </div>
		        <div class="col-12 col-md-6">
	              <?php echo showContainerBanner('top_right', 0, '') ?>
		        </div>
		        <div class="col-12">
			        <h1  class="title-home font-weight-bold my-3 text-center">
                  <?php if (!empty($SEO['meta_title'])) {
                      echo $SEO['meta_title'];
                  } else
                      echo $this->_settings->meta_title ?? '' ?>
			        </h1>
		        </div>
            <div class="col-12 col-lg-8">
                <div id="ajax_content" class="row ajax-content">
                    <?php if(!empty($data)) foreach ($data as $item): ?>
                        <div class="col-12 col-md-6">
                            <a href="javascript:;" title="<?php echo $item->category_title ?>" class="input-comment text-uppercase text-white py-1 px-2 d-inline-block font-11 position-absolute cate-absolute"><?php echo $item->category_title ?></a>
                            <a href="<?php echo getUrlPost($item) ?>" title="<?php echo $item->title ?>">
                                <?php echo getThumbnail($item, 350, 200, "img-fluid w-100 mb-3") ?>
                            </a>
                            <a href="<?php echo getUrlPost($item) ?>" title="<?php echo $item->title ?>">
                                <h3 class="font-16 text-black2 max-line-2"><?php echo $item->title ?></h3>
                            </a>
                            <span class="text-secondary font-12"><?php echo timeAgo($item->displayed_time,"d/m/Y") ?></span>
                            <p class="max-line-2 text-secondary font-13"><?php echo $item->description ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
		            <div class="text-center">
			            <button class="btn btn-danger mx-auto my-3 btnLoadMore" data-page="2" data-url="<?php echo getUrlCategory($oneItem) ?>" type="button">Xem thêm</button>
		            </div>
	              <div class="post-content-detail">
                    <?php echo getTableOfContent($oneItem->content)?>
	              </div>
            </div>
		        <div class="col-12 col-lg-4">
			        <div class="position-sticky" style="top: 45px">
                  <?php echo showContainerBanner('sidebar_middle', 0, '') ?>
				        <iframe id="chatbox" src="https://www5.cbox.ws/box/?boxid=927201&boxtag=3jw66g" width="100%" height="500" allowtransparency="yes" allow="autoplay" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto"></iframe>
                  <?php echo showContainerBanner('sidebar_bottom', 0, '') ?>
			        </div>
		        </div>
        </div>
    </main>
<?php endif; ?>