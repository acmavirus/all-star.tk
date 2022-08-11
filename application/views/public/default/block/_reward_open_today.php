<?php
$statics = getCatByWeekDay(date('N') + 1);
?>
<aside class="mb-2">
	<div class="show-today">
		<h3 class="title">
			Mở thưởng hôm nay - ngày <?php echo date('d/m/Y') ?>
		</h3>
	</div>
	<table class="table-b">
		<tbody>
			<?php
			foreach ($statics as $key => $item) : ?>
				<?php echo $key == 0 ? '<tr>' : '' ?>
				<?php echo ($key) % 3 == 0 && $key != 0 ? '</tr><tr>' : '' ?>
				<?php if ($item->slug == "mien-bac") : ?>
					<td><a href="<?php echo getUrlCategoryRS($item); ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo $item->title; ?></a></td>
				<?php else : ?>
					<td><a href="<?php echo getUrlCategory($item); ?>" title="<?php echo $item->meta_title ?? ''; ?>"><?php echo $item->title; ?></a></td>
				<?php endif; ?>
				<?php echo ($key + 1 == count($statics)) ? '</tr>' : '' ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</aside>