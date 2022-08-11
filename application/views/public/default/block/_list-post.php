<?php
$list_soicau = getDataPost([
  'type' => 'post',
  'category_id' => 97,
  'limit' => 8
]);
$list_xoso = getDataPost([
  'type' => 'post',
  'category_id' => 98,
  'limit' => 8
]);
$arr = array();
$arr[0] = [
  'title' => 'Soi cầu',
  'url' => 'soi-cau.html',
  'list_post' => $list_soicau
];
$arr[1] = [
  'title' => 'Xổ số',
  'url' => 'ket-qua-xo-so.html',
  'list_post' => $list_xoso
]
?>
<div class="row">
  <?php if (!empty($arr)) :  ?>
    <?php foreach ($arr as $list) : ?>
      <?php if (!empty($list['list_post'])) : ?>
        <div class="col-12 my-3 d-flex flex-wrap">
          <div class="title-bg-danger">
            <a href="<?php echo base_url($list['url']) ?>" title="" class="text-white font-18"><?php echo $list['title'] ?></a>
            <a href="<?php echo base_url($list['url']) ?>" title="" class="text-white font-12 px-3">Xem thêm</a>
          </div>
        </div>
        <?php foreach ($list['list_post'] as $item) : ?>
          <div class="col-6 col-lg-3 mb-3">
            <a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>">
              <img loading="lazy" class="w-100 img-fluid" src="<?php echo getImageThumb($item->thumbnail, 255, 145) ?>" alt="<?php echo $item->title; ?>">
            </a>
            <a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>" class="text-black2 font-14 mt-2 max-line-2"><?php echo $item->title; ?></a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>