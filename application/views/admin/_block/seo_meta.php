<?php
/**
* Created by PhpStorm.
* User: ducto
* Date: 18/12/2017
* Time: 11:21 SA
*/
defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="form-group">
    <label>Meta Title</label>
    <label for="title"><span class="count-title">0</span> / <?php echo $this->config->item('SEO_title_maxlength') ?></label>
    <input name="meta_title" placeholder="Meta Title" class="form-control" type="text" maxlength="<?php echo $this->config->item('SEO_title_maxlength') ?>"/>
</div>
<div class="form-group">
    <label>Url</label>
    <input name="slug" placeholder="Link" class="form-control" type="text" />
</div>
<div class="form-group">
    <label>Meta Description</label>
    <label for="desc"><span class="count-desc">0</span> / <?php echo $this->config->item('SEO_description_maxlength') ?></label>
    <textarea name="meta_description" placeholder="Meta Description" class="form-control content_product" rows="5"></textarea>
</div>
<div class="form-group">
    <label>Meta Keyword</label>
    <label for="key"><span class="count-key">0</span> / <?php echo $this->config->item('SEO_keyword_maxlength') ?></label>
    <input value="" name="meta_keyword" placeholder="Meta Keyword" class="form-control tagsinput" data-role="tagsinput" type="text" />
</div>
<div class="google">
    <h2 class="cgg"><span class="gg_1">Google!</span></h2>
    <input type="text" class="gg-result" readOnly/>
    <div class="box">
        <h3 class="gg-title"></h3>
        <cite class="gg-url"></cite>
        <span class="gg-desc"></span>
    </div>
</div>