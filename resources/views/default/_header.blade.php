<div style="background: #fff;">
    <!-- Top bar -->
<div class="container">
    <div class="row">
        <div class="col-md-2 noo-res"></div>
        <div class="col-md-10">
            <div class="top-bar">
                <div class="col-md-3">
                    <ul class="social_icons">
                        <?php foreach (getSetting('data_social') as $key => $value): if(empty($value)) continue; ?>
                        <li><a href="<?= $value ?>" target="_blank" rel="noopener noreferrer" title="social <?= $key ?>"><i class="fa fa-<?= $key ?>"></i></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Social Icon -->
                <div class="col-md-9">
                    <ul class="some-info font-montserrat">
                        <li><i class="fa fa-phone"></i> <?= getSetting('data_seo')->phone; ?></li>
                        <li><i class="fa fa-envelope"></i><a href="mailto: <?= getSetting('data_email')->email_admin; ?>" class="text-white" target="_blank" rel="noopener noreferrer" title="<?= getSetting('data_email')->name_from; ?>"><?= getSetting('data_email')->name_from; ?></a></li>
                        <li><i class="fa fa-weixin"></i> <a href="<?= url('auth/login'); ?>" target="_blank" class="text-white" rel="noopener noreferrer" title="Login">Login</a></li>
                        <li><i class="fa fa-question-circle"></i> <a href="<?= url('auth/register'); ?>" class="text-white" target="_blank" rel="noopener noreferrer" title="Register">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header coporate-header">
    <div class="sticky">
        <div class="container">
            <div class="logo"> <a href="<?= url('/'); ?>" title="Logo"><img src="<?= TEMPLATE_DEFAULT ?>images/logo.png" width="48px" height="48px" alt="logo"></a> </div>
            <!-- Nav -->
            <nav>
                <ul id="ownmenu" class="ownmenu">
                    <li class="active"><a href="<?= url('/'); ?>" title="Home">HOME</a></li>
                    <li><a href="<?= url('/page/blog.html'); ?>" title="Blog"> BLOG </a></li>
                    <!--======= SEARCH ICON =========-->
                    <li class="search-nav right"><a href="#."><i class="fa fa-search"></i></a>
                        <ul class="dropdown">
                            <li>
                                <form>
                                    <input type="search" class="form-control" placeholder="Enter Your Keywords..."
                                        required>
                                    <button type="submit"> SEARCH </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!-- End Header -->

</div>