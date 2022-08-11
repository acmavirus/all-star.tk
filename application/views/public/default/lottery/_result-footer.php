<?php $key = random_int(0, 1000)?>
<div class="py-2 bg-grey1 d-flex amp-hidden">
    <label class="check-radio">
        <input type="radio" checked="checked" name="show<?php echo $key?>" value="0">
        <i class="before"></i>
        <i class="after"></i>
        <span>Đầy đủ</span>
    </label>
    <label class="check-radio">
        <input type="radio" name="show<?php echo $key?>" value="2">
        <i class="before"></i>
        <i class="after"></i>
        <span>2 số</span>
    </label>
    <label class="check-radio">
        <input type="radio" name="show<?php echo $key?>" value="3">
        <i class="before"></i>
        <i class="after"></i>
        <span>3 số</span>
    </label>
</div>