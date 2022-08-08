<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">

            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-1 order-xl-2 m--align-right">
                        <button type="submit"
                            class="btn btn-primary m-btn m-btn--icon m-btn--air m-btn--pill btnAddForm">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    Update Setting
                                </span>
                            </span>
                        </button>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>

            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-key="data_seo" data-toggle="tab"
                                    href="#tab_general" role="tab" aria-selected="true">
                                    <i class="la la-search"></i>
                                    Thông tin SEO
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-key="data_301" data-toggle="tab" href="#tab_301"
                                    role="tab" aria-selected="true">
                                    <i class="la la-angellist"></i>
                                    301 url
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" data-key="data_social"
                                    href="#tab_social" role="tab" aria-selected="false">
                                    <i class="la la-facebook"></i>
                                    Mạng xã hội
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" data-key="data_email"
                                    href="#tab_email" role="tab" aria-selected="false">
                                    <i class="la la-cog"></i>
                                    Cấu hình email
                                </a>
                            </li>


                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab_general" role="tabpanel">
                            @include("admin/setting/seo");
                        </div>

                        <div class="tab-pane" id="tab_301" role="tabpanel">
                            @include("admin/setting/301");
                        </div>

                        <div class="tab-pane" id="tab_social" role="tabpanel">
                            @include("admin/setting/social");
                        </div>

                        <div class="tab-pane" id="tab_email" role="tabpanel">
                            @include("admin/setting/email");
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var url_update_setting = '<?php echo site_url('admin/setting/update_setting'); ?>';

</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
</body>
</html>