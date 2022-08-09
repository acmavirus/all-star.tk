<div class="tab-content">
    <form action="" id="data_301">
        <input type="hidden" value="data_301" name="key_setting">

        <div class="form-group">
            <label>Danh sách url cần 301</label>
            <textarea name="content" rows="20"
                      class="form-control" type="text"><?php echo isset($data_301->content) ? $data_301->content : ''; ?></textarea>
        </div>
    </form>
</div>