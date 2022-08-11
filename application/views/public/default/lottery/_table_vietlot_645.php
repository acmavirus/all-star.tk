<?php if (!empty($one_data_vietlot)) :
    $data_result_json = $one_data_vietlot['data_result'];
    $data_result = json_decode($data_result_json);
    $data_info = json_decode($one_data_vietlot['data_info']);
    $data_detail = $data_info[1];
    $disTime = $one_data_vietlot['displayed_time'];
    if(empty($oneParent->code)) $oneParent = $oneItem;
		?>
		<div class="frame-kq" style="margin-top: 8px; background-color: white">
			<h2 class="breadcrumb-table-title">
				<a href="<?php echo base_url('/mega-6-45.html')?>" title="Mega645">Mega645</a>
				»
				<a href="<?php echo getUrlWeekday($oneParent, date('w', strtotime($disTime))); ?>" title="Mega645 <?php echo getDayOfWeek($disTime) ?>">Mega645 <?php echo getDayOfWeek($disTime) ?></a>
				»
				<a href="mega-<?php echo date('d-m-Y', strtotime($disTime)); ?>" title="">Mega645 <?php echo date('d/m/Y', strtotime($disTime)); ?></a>
			</h2>
			<div class="ketqua font-28">
				<div class="mega_result">
					<ul style="margin-bottom: 8px; margin-top: 8px">
						<?php foreach($data_result as $result) : ?>
							<li><?php echo $result?></li>
            <?php endforeach; ?>
					</ul>
				</div>
				<div style="font-size: 16px">
					<table width="100%">
						<tbody>
						<tr>
							<td>Giải thưởng</td>
							<td>Trùng khớp</td>
							<td>Số giải</td>
							<td>Giá trị</td>
						</tr>
						</tbody>
						<tbody>
						<tr>
							<td>Jackpot</td>
							<td class="circle-num">
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
							</td>
							<td><?php echo $data_detail[0][0]?></td>
							<td><?php echo number_format($data_detail[0][1], 0, '', '.') ?>₫</td>
						</tr>
						<tr>
							<td>Giải nhất</td>
							<td class="circle-num">
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
							</td>
							<td><?php echo $data_detail[1][0]?></td>
							<td><?php echo number_format($data_detail[1][1], 0, '', '.') ?>₫</td>
						</tr>
						<tr>
							<td>Giải nhì</td>
							<td class="circle-num">
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
							</td>
							<td><?php echo $data_detail[2][0]?></td>
							<td><?php echo number_format($data_detail[2][1], 0, '', '.') ?>₫</td>
						</tr>
						<tr>
							<td>Giải ba</td>
							<td class="circle-num">
								<i>&nbsp;</i>
								<i>&nbsp;</i>
								<i>&nbsp;</i>
							</td>
							<td><?php echo $data_detail[3][0]?></td>
							<td><?php echo number_format($data_detail[3][1], 0, '', '.') ?>₫</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?php endif; ?>
