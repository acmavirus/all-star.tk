<div class="tab-content">
    <form action="" id="data_email">
        <input type="hidden" value="data_email" name="key_setting">
        <div class="form-group">
            <label>Email quản trị</label>
            <input type="text" name="email_admin" placeholder="Email quản trị" class="form-control" value="<?php echo isset($data_email->email_admin) ? $data_email->email_admin : ''; ?>">
        </div>
        <div class="form-group">
            <label>Name From</label>
            <input type="text" name="name_from" placeholder="Name Form" class="form-control" value="<?php echo isset($data_email->name_from) ? $data_email->name_from : ''; ?>">
        </div>
    </form>
</div>