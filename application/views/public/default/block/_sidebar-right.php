<?php
$vietlot = getDataCategory('vietlot');
$parent;
$items;
foreach ($vietlot as $key => $item) {
	if ($item->parent_id == 0) {
		$parent = $item;
	} else {
		$items[] = $item;
	}
};
$not_in = 0;
if(!empty($oneItem)) $not_in = $oneItem->id;
$list_soicau = getDataPost([
	'type' => 'post',
	'category_id' => 206,
	'limit' => 8,
	'not_in' => $not_in
]);
$list_xoso = getDataPost([
	'type' => 'post',
	'category_id' => 205,
	'limit' => 8,
	'not_in' => $not_in
]);
?>
<div class="col-xl-300 mt-3 mt-md-0 position-relative sidebar-right" id="fixerror">
	<!-- Calendar -->
	<div style="margin-top: -0.5rem;" id="datepicker"></div>
	<aside class="border-radius-4">
		<div class="aside-title-red">
			<h3 class="title"><?= $parent->title ?></h3>
		</div>
		<nav class="aside-province">
			<ul>
				<?php foreach ($items as $key => $item) : ?>
					<li>
						<a rel="<?= $item->description ?>" href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo "{$item->title}"; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</aside>
	<aside class="border-radius-4 mt-2 container">
		<div class="aside-title-red">
			<h3 class="title">Bài viết xổ số</h3>
		</div>
		<div class="row justify-content-center">
			<?php if (!empty($list_xoso)) :  ?>
				<?php foreach ($list_xoso as $item) : ?>
					<div class="col-12 col-lg-12 mb-3">
						<a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>">
							<img loading="lazy" class="w-100 img-fluid" src="<?php echo getImageThumb($item->thumbnail, 255, 145) ?>" alt="<?php echo $item->title; ?>">
						</a>
						<a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>" class="text-black2 font-14 mt-2 max-line-2"><?php echo $item->title; ?></a>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				Đang cập nhật
			<?php endif; ?>
		</div>
		<nav class="aside-province">
			<ul>
				<li><a href="/xo-so.html" title="Xem thêm xổ số">Xem thêm</a></li>
			</ul>
		</nav>
	</aside>
	<aside class="border-radius-4 mt-2 container">
		<div class="aside-title-red">
			<h3 class="title">Bài viết soi cầu</h3>
		</div>
		<div class="row justify-content-center">
			<?php if (!empty($list_soicau)) :  ?>
				<?php foreach ($list_soicau as $item) : ?>
					<div class="col-12 col-lg-12 mb-3">
						<a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>">
							<img loading="lazy" class="w-100 img-fluid" src="<?php echo getImageThumb($item->thumbnail, 255, 145) ?>" alt="<?php echo $item->title; ?>">
						</a>
						<a href="<?= getUrlPost($item) ?>" title="<?php echo $item->title; ?>" class="text-black2 font-14 mt-2 max-line-2"><?php echo $item->title; ?></a>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				Đang cập nhật
			<?php endif; ?>
		</div>
		<nav class="aside-province">
			<ul>
				<li><a href="/soi-cau.html" title="Xem thêm soi cầu">Xem thêm</a></li>
			</ul>
		</nav>
	</aside>
</div>