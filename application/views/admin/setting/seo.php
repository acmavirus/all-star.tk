<div class="tab-content">
    <form action="" id="data_seo">
        <input type="hidden" value="data_seo" name="key_setting">

        <div class="form-group">
            <label>Tên Website</label>
            <input name="title"
                   placeholder="Tên Website"
                   class="form-control" type="text"
                   value="<?php echo isset($data_seo->title) ? $data_seo->title : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>Tiêu đề SEO</label>
            <input name="meta_title"
                   placeholder="Tiêu đề SEO"
                   class="form-control" type="text"
                   value="<?php echo isset($data_seo->meta_title) ? $data_seo->meta_title : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>Mô tả SEO Website</label>
            <textarea name="meta_description" class="form-control"><?php echo isset($data_seo->meta_description) ? $data_seo->meta_description : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Từ khóa SEO Website</label>
            <input name="meta_keyword"
                   placeholder="Từ khóa SEO Website"
                   class="form-control" type="text"
                   value="<?php echo isset($data_seo->meta_keyword) ? $data_seo->meta_keyword : ''; ?>"/>
        </div>
        <div class="form-group">
            <label>Content trang chủ</label>
            <textarea name="content" class="form-control summernote"><?php echo isset($data_seo->content) ? $data_seo->content : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Content footer</label>
            <textarea name="content_footer" class="form-control summernote"><?php echo isset($data_seo->content_footer) ? $data_seo->content_footer : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Địa chỉ trụ sở chính</label>
            <input name="address" class="form-control" value="<?php echo isset($data_seo->address) ? $data_seo->address : ''; ?>">
        </div>
        <div class="form-group">
            <label>Domain</label>
            <input name="domain" class="form-control" value="<?php echo isset($data_seo->domain) ? $data_seo->domain : ''; ?>">
        </div>
        <div class="form-group">
            <label>Phone Hotline</label>
            <input name="phone" class="form-control" value="<?php echo isset($data_seo->phone) ? $data_seo->phone : ''; ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input name="email" class="form-control" value="<?php echo isset($data_seo->email) ? $data_seo->email : ''; ?>">
        </div>

        <div class="form-group">
            <label for="thumbnail">Favicon</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="la la-picture-o"></i>
                    </span>
                </div>
                <input type="text" name="favicon" value="<?php echo !empty($data_seo->favicon) ? $data_seo->favicon : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
            </div>
            <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <img height="70" src="<?php echo !empty($data_seo->favicon) ? getImageThumb($data_seo->favicon,100,100) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="thumbnail">Logo</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="la la-picture-o"></i>
                    </span>
                </div>
                <input type="text" name="logo" value="<?php echo !empty($data_seo->logo) ? $data_seo->logo : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
            </div>
            <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <img height="70" src="<?php echo !empty($data_seo->logo) ? getImageThumb($data_seo->logo,100,100) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="thumbnail">Logo footer</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="la la-picture-o"></i>
                    </span>
                </div>
                <input type="text" name="logo_footer" value="<?php echo !empty($data_seo->logo_footer) ? $data_seo->logo_footer : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
            </div>
            <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <img height="70" src="<?php echo !empty($data_seo->logo_footer) ? getImageThumb($data_seo->logo_footer,100,100) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="thumbnail">Social share</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="la la-picture-o"></i>
                        </span>
                </div>
                <input type="text" name="thumbnail" value="<?php echo !empty($data_seo->thumbnail) ? $data_seo->thumbnail : '' ?>" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh">
            </div>
            <div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <img height="70" src="<?php echo !empty($data_seo->thumbnail) ? getImageThumb($data_seo->thumbnail,100,100) : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả chân trang</label>
            <textarea name="des_footer" class="form-control summernote"><?php echo isset($data_seo->des_footer) ? $data_seo->des_footer : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Custom Style</label>
            <textarea name="style" rows="5" class="form-control"><?php echo isset($data_seo->style) ? $data_seo->style : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Custom Script</label>
            <textarea name="script" rows="5" class="form-control"><?php echo isset($data_seo->script) ? $data_seo->script : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Mã Google Analytics</label>
            <input name="analytics" class="form-control" value="<?php echo isset($data_seo->analytics) ? $data_seo->analytics : ''; ?>">
        </div>
    </form>
</div>