<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/29/2018
 * Time: 12:35 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>
        Login | Website
    </title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script>
        WebFontConfig = {
            google: { families: ["Nunito Sans:300,400,500,600,700","Roboto:300,400,500,600,700"] }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="<?php echo $this->templates_assets ?>assets/vendors/base/vendors.bundle.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo $this->templates_assets ?>assets/demo/default/base/style.bundle.css" rel="stylesheet"
          type="text/css"/>
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="<?php echo $this->templates_assets ?>assets/demo/default/media/img/logo/favicon.ico"/>
    <style>
        .g-recaptcha{
            margin: 15px 0 25px;
        }
        .g-recaptcha > div{
            margin: 0 auto;
        }
    </style>
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3"
         id="m_login"
         style="background-image: url(<?php echo $this->templates_assets ?>assets/app/media/img//bg/bg-2.jpg);">
        <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <img src="<?php echo $this->templates_assets ?>assets/app/media/img//logos/logo-1.png">
                    </a>
                </div>
                <div class="m-login__signin">
                    <div class="m-login__head">
                        <h3 class="m-login__title">
                            Sign In To Admin
                        </h3>
                    </div>
                    <?php echo form_open(site_admin_url('user/ajax_login'), ['class' => 'm-login__form m-form']); ?>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" placeholder="Username / Email" name="identity"
                               autocomplete="off">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password"
                               placeholder="Password" name="password">
                    </div>
                    <?php if(GG_CAPTCHA_MODE == TRUE): ?>
                    <div class="form-group m-form__group">
                        <div class="g-recaptcha" data-sitekey="<?php echo GG_CAPTCHA_SITE_KEY ?>"></div>
                    </div>
                    <?php endif; ?>
                    <div class="row m-login__form-sub">
                        <div class="col m--align-left m-login__form-left">
                            <label class="m-checkbox  m-checkbox--light">
                                <input type="checkbox" name="remember">
                                Remember me
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="m-login__form-action">
                        <input type="hidden" name="url_redirect" value="<?php echo $this->input->get('url') ?>">
                        <button type="submit" id="m_login_signin_submit"
                                class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                            Sign In
                        </button>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="<?php echo $this->templates_assets ?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="<?php echo $this->templates_assets ?>assets/demo/default/base/scripts.bundle.js"
        type="text/javascript"></script>
<!--end::Base Scripts -->

<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    $(document).ready(function () {
        $('form').ajaxForm({
            //target:        '#output1',   // target element(s) to be updated with server response
            beforeSubmit: function showRequest(formData, jqForm, options) {
                jqForm.find('[type="submit"]').append('<i class="fa fa-spinner fa-spin ml-2" style="font-size:24px;color: #ffffff;"></i>');
                let queryString = $.param(formData);
                return true;
            },  // pre-submit callback
            success: function showResponse(response, statusText, xhr, $form) {
                if(response.csrf_form) $form.find('input[name="' + response.csrf_form.csrf_name + '"]').val(response.csrf_form.csrf_value);
                $form.find('.fa-spin').remove();
                if (typeof response.type !== 'undefined') {
                    toastr[response.type](response.message);
                    if (response.type === "warning") {
                        $form.find('.form-group').removeClass('has-danger');
                        $form.find('.form-control-feedback').remove();
                        $.each(response.validation, function (key, val) {
                            $form.find('[name="' + key + '"]').after(val).parent().addClass('has-danger');
                        });
                    } else {
                        $form.find('.form-group').removeClass('has-danger');
                        $form.find('.form-control-feedback').remove();
                        //$form.reset();
                        setTimeout(function () {
                            if(response.url_redirect) location.href = response.url_redirect;
                        },2000);
                    }
                }
            },  // post-submit callback
            error: function showError(jqXHR, exception) {
                toastr['error']("The action you have requested is not allowed.");
            },
            type: 'POST',        // 'get' or 'post', override for form's 'method' attribute
            dataType: 'JSON'        // 'xml', 'script', or 'json' (expected server response type)
            //clearForm: true        // clear all form fields after successful submit
            //resetForm: true        // reset the form after successful submit
            // $.ajax options can be used here too, for example:
            //timeout:   3000
        });
    });
</script>
</body>
<!-- end::Body -->
</html>
