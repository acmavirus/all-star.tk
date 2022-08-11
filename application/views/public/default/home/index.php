<div class="container">
      <div style="margin-top: 8px;width: 100%" class="text-center">
            <h1 style=" color: #D0021B; font-size: 18px; font-weight: 600"><?= $SEO['meta_title'] ?></h1>
      </div>
</div>
<main class="main-home">
      <div class="container">
            <div class="row main-content">
                  <div class="col-xl-610">
                        <?php $this->load->view(TEMPLATE_PATH . 'block/_reward_open_today') ?>
                        <?php if (date("H") == 16) : ?>
                              <?php if (!empty($oneParentMN)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MN, 'oneParent' => $oneParentMN]); ?>
                              <?php if (!empty($data_MT)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT]) ?>
                              <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['oneParent' => $oneParentMB ?? '']) ?>

                        <?php elseif (date("H") == 17) : ?>
                              <?php if (!empty($data_MT)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT]) ?>
                              <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['oneParent' => $oneParentMB ?? '']) ?>
                              <?php if (!empty($oneParentMN)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MN, 'oneParent' => $oneParentMN]); ?>

                        <?php else : ?>
                              <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', ['oneParent' => $oneParentMB ?? '']) ?>
                              <?php if (!empty($data_MT)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT]) ?>
                              <?php if (!empty($oneParentMN)) $this->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MN, 'oneParent' => $oneParentMN]); ?>
                        <?php endif; ?>
                        <?php $this->load->view(TEMPLATE_PATH . 'lottery/_table_vietlot_645'); ?>
                  </div>
                  <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-left') ?>
                  <?php $this->load->view(TEMPLATE_PATH . 'block/_sidebar-right') ?>
            </div>
            <?php $this->load->view(TEMPLATE_PATH . 'block/_list-post') ?>
</main>