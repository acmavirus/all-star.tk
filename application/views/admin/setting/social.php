<div class="tab-content">
    <form action="" id="data_social">
        <input type="hidden" value="data_social" name="key_setting">
        <div class="form-group">
            <label>Facebook</label>
            <input name="facebook" class="form-control" value="<?php echo isset($data_social->facebook) ? $data_social->facebook : ''; ?>">
        </div>
        <div class="form-group">
            <label>Google</label>
            <input name="google" class="form-control" value="<?php echo isset($data_social->google) ? $data_social->google : ''; ?>">
        </div>
        <div class="form-group">
            <label>Twitter</label>
            <input name="twitter" class="form-control" value="<?php echo isset($data_social->twitter) ? $data_social->twitter : ''; ?>">
        </div>
        <div class="form-group">
            <label>Youtube</label>
            <input name="youtube" class="form-control" value="<?php echo isset($data_social->youtube) ? $data_social->youtube : ''; ?>">
        </div>
        <div class="form-group">
            <label>Instagram</label>
            <input name="instagram" class="form-control" value="<?php echo isset($data_social->instagram) ? $data_social->instagram : ''; ?>">
        </div>
        <div class="form-group">
            <label>Pinterest</label>
            <input name="pinterest" class="form-control" value="<?php echo isset($data_social->pinterest) ? $data_social->pinterest : ''; ?>">
        </div>
    </form>
</div>