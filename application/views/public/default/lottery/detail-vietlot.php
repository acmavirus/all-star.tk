<div class="container">
    <div style="margin-top: 8px;width: 100%" class="text-center">
        <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
    </div>
</div>
<main class="main-home">
    <div class="container">
	    <div class="row">
		    <div class="w-100 mt-2">
            <?php $this->load->view(TEMPLATE_PATH . "block/breadcrumb") ?>
		    </div>
	    </div>
      <div class="row main-content">
          <div class="col-xl-610" >
              <?php if($oneItem->code == 'MEGA')  :  ?>
                <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_645') ?>
              <?php elseif($oneItem->code == 'POWER') :?>
                  <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_today_655') ?>
	          <?php endif; ?>
	            <div class="" id="ajax_content">
                  <?php if (!empty($data_vietlot)) :   ?>
                    <?php if( $oneItem->code == 'MEGA')  :  ?>
                      <?php foreach ($data_vietlot as $item):?>
                          <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_645', ['one_data_vietlot'=> $item] ) ?>
                      <?php endforeach; ?>
                    <?php elseif($oneItem->code == 'POWER')  :  ?>
                        <?php foreach ($data_vietlot as $item):?>
                            <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_655', ['one_data_vietlot'=> $item] ) ?>
                        <?php endforeach; ?>
                    <?php elseif($oneItem->code == 'MAX3D')  :  ?>
                        <?php foreach ($data_vietlot as $item):?>
                            <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_3d', ['one_data_vietlot'=> $item] ) ?>
                        <?php endforeach; ?>
                    <?php elseif($oneItem->code == 'MAX4D')  :  ?>
                        <?php foreach ($data_vietlot as $item):?>
                            <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_4d', ['one_data_vietlot'=> $item] ) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endif; ?>
	            </div>
            <button class="btnLoadMore mt-2" data-url="<?php echo getUrlCategory($oneItem); ?>" data-page="2">Xem thêm kết quả</button>

          </div>
          <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
          <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
      </div>
      <?php $this->load->view(TEMPLATE_PATH . 'block/_list-post') ?>
	    <div class="row">
		    <div class="col-12">
			    <div class="new-content ketqua text-justify mt-2 bg-white p-3" id="danh-muc">
              <?php echo getTableOfContent($oneItem->content) ?>
			    </div>
		    </div>
	    </div>
    </div>
</main>