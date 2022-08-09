<?php if (!empty($domain_fix) && !empty($position)): ?>

    <?php if ($position === 'D1'): ?>
        <div class="form-group">
            <label>Tên</label>
            <div class="input-group m-input-group m-input-group--air">
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][name]"
                       placeholder="Tên vị trí"
                       value="<?php echo !empty($banner[$domain_fix][$position]['name']) ? $banner[$domain_fix][$position]['name'] : '' ?>"
                       class="form-control m-input">
            </div>
        </div>
        <div class="form-group">
            <label>Text chữ chạy trên Player</label>
            <textarea name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][content]" class="form-control summernote"><?php echo !empty($banner[$domain_fix][$position]['content']) ? $banner[$domain_fix][$position]['content'] : '' ?></textarea>
        </div>
    <?php else: ?>
        <div class="form-group">
            <label>Link PC</label>
            <div class="input-group m-input-group m-input-group--air">
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][name]"
                       placeholder="Tên vị trí"
                       value="<?php echo !empty($banner[$domain_fix][$position]['name']) ? $banner[$domain_fix][$position]['name'] : '' ?>"
                       class="form-control m-input">
            </div>
            <div class="input-group m-input-group m-input-group--air">
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][link]"
                       value="<?php echo !empty($banner[$domain_fix][$position]['link']) ? $banner[$domain_fix][$position]['link'] : '' ?>"
                       class="form-control m-input">
            </div>
        </div>
        <div class="form-group">
            <label>Banner PC</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                        <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                            <i class="la la-picture-o"></i>
                        </span>
                </div>
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][content]"
                       value="<?php echo !empty($banner[$domain_fix][$position]['content']) ? $banner[$domain_fix][$position]['content'] : '' ?>"
                       class="form-control m-input chooseImage">
            </div>
            <!--<div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <a target="_blank"
                   href="//media.vuivetv.com/<?php /*echo !empty($banner[$domain_fix][$position]['content']) ? $banner[$domain_fix][$position]['content'] : '' */?>">
                    Xem ảnh
                </a>
            </div>-->
        </div>
        <hr>
        <div class="form-group">
            <label>Link Mobile</label>
            <div class="input-group m-input-group m-input-group--air">
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][link_mobile]"
                       value="<?php echo !empty($banner[$domain_fix][$position]['link_mobile']) ? $banner[$domain_fix][$position]['link_mobile'] : '' ?>"
                       class="form-control m-input">
            </div>

            <label>Banner Mobile</label>
            <div class="input-group m-input-group m-input-group--air">
                <div class="input-group-prepend">
                    <span class="input-group-text" onclick="FUNC.chooseImage(this)">
                        <i class="la la-picture-o"></i>
                    </span>
                </div>
                <input type="text" name="banner[<?php echo $domain_fix ?>][<?php echo $position ?>][content_mobile]"
                       value="<?php echo !empty($banner[$domain_fix][$position]['content_mobile']) ? $banner[$domain_fix][$position]['content_mobile'] : '' ?>"
                       class="form-control m-input chooseImage">
            </div>
            <!--<div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
                <a target="_blank"
                   href="//media.vuivetv.com/<?php /*echo !empty($banner[$domain_fix][$position]['content_mobile']) ? $banner[$domain_fix][$position]['content_mobile'] : '' */?>">
                    Xem thử
                </a>
            </div>-->
        </div>
    <?php endif; ?>
<?php endif; ?>