<?php if (!empty($one_data_vietlot)) :
    $data_result = json_decode($one_data_vietlot['data_result']);
    if (empty($reward)) {
        $reward = getRewardVietlot(44);
    }
    $disTime = $one_data_vietlot['displayed_time'];
    if (empty($oneParent->code)) {
        $oneParent = $oneItem;
    }
    ?>
	<style>
    .ketqua table td {
      border: 1px #e8e8e8 solid;
      padding: 5px;
      font-family: Arial, sans-serif;
      text-align: center;
    }
    .g_db {
      font-size: 33px;
      color: red;
      font-weight: 700;
    }
    .bg_tr {
      background-color: #f9f9f9;
    }
	</style>
	<article class="mb-2">
		<div class="ketqua">
			<h2 class="breadcrumb-table-title">
				<a href="<?php echo getUrlCategory($oneParent) ?>" title="<?php echo $oneParent->title?>"><?php echo $oneParent->code?></a>
				»
				<a href="<?php echo getUrlWeekday($oneParent, date('w', strtotime($disTime))); ?>" title="<?php echo $oneParent->code?> <?php echo getDayOfWeek($disTime) ?>"><?php echo $oneParent->code?> <?php echo getDayOfWeek($disTime) ?></a>
				»
				<a href="max3d-<?php echo date('d-m-Y', strtotime($disTime)); ?>" title="<?php echo $oneParent->code?> <?php echo date('d/m/Y', strtotime($disTime)); ?>"><?php echo $oneParent->code?> <?php echo date('d/m/Y', strtotime($disTime)); ?></a>
			</h2>
			<div class="border">
				<table width="100%">
					<tbody>
          <?php foreach ($data_result as $key => $item): ?>
						<tr class="<?php echo $key % 2 == 0 ? 'bg_tr' : '' ?>">
							<td style="border-left:0">
                  <?php echo $reward[$key]; ?>
							</td>
							<td>
								<table width="100%" border="0">
									<tbody>
                  <?php foreach ($item as $keyNumber => $number) :
                      $row = false;
                      $numRow = count($item) / 2;
                      $numRow;
                      if(count($item) > 5 && $keyNumber == $numRow) $row = true;
	                  ?>
                      <?php if($row) echo '<tr>' ?>
												<td style="border:0;">
													<span class="<?php echo $key == 0 ? 'g_db' : '' ?>">
															<?php echo !empty($number) ? $number : '<i class="fas fa-spinner fa-pulse"></i>'  ?>
													</span>
												</td>
                  <?php endforeach; ?>
									</tbody>
								</table>
							</td>
						</tr>
          <?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</article>
<?php endif; ?>