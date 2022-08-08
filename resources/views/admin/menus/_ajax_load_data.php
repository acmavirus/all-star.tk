
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item active">
            <a href="#tab_general" class="nav-link" data-toggle="tab">Chung</a>
        </li>
        <li class="nav-item">
            <a href="#tab_category" class="nav-link" data-toggle="tab">Chuyên mục - Page</a>
        </li>
    </ul>
    <div id="listDataItem" class="tab-content">
        <div class="tab-pane active" id="tab_general">
            <input type="hidden" value="" name="type">
            <select class="form-control select2"   style="width: 100%;" tabindex="-1" aria-hidden="true">
                <option value="#">Link khác</option>
                <option value="/">Trang chủ</option>
            </select>
        </div>

        <div class="tab-pane" id="tab_category">
            <input type="hidden" value="category" name="type">
            <select class="form-control select2"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                <?php
                if(!empty($list_category)) foreach ($list_category as $cat):
                    switch ($cat->type):
                        case "tournament": $url = str_replace(base_url(),"",getUrlTournament($cat));break;
                        case "tag": $url = str_replace(base_url(),"",getUrlTag($cat));break;
                        default: $url = str_replace(base_url(),"",getUrlCategory($cat));
                    endswitch;
                    ?>
                    <option value="<?php echo $url; ?>" value-id="<?php echo $cat->id ?>"><?php echo $cat->title; ?></option>
                <?php endforeach; ?>
            </select>
            <br/>
        </div>



    </div>
    <!-- /.tab-content -->
    <button type="button" class="btn btn-success addtonavmenu  mt-3"><i class="glyphicon glyphicon-plus"></i> Thêm vào menu</button>
</div>
<!-- nav-tabs-custom -->
