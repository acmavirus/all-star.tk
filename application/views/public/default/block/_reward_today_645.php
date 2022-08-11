<?php
	$result = getResultVietlot(42);
	$maxReward_json = json_decode($result[0]['data_info']);
	$maxReward = $maxReward_json[1][0][1];
?>
<div>
    <div class="breadcrumb-table-title">
        Kết quả xổ số vietlott 645 - xs mega 645 hôm nay
    </div>
    <div style="text-align: center; width: 96%; font-size: 22px;
    font-weight: 600; margin:8px auto">
        GIẢI THƯỞNG XS MEGA 645 ƯỚC TÍNH
    </div>
    <div style="
                                        text-align: center;
                                        width: 80%;
                                        font-size: 28px;
                                        font-weight: 700;
                                        color: #d0021b;
                                        border: 1px solid;
                                        margin: auto;
                                        background-color: white;
                                        border-radius: 57px;
                                        margin-bottom: 8px;
                                    ">
	    <?php echo number_format($maxReward, 0, '', '.')?>đ
    </div>
</div>