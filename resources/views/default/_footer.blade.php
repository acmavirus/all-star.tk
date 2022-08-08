<!-- Footer -->
<footer>
    <div class="container">
        <!-- Footer Info -->
        <div class="footer-info">
            <div class="container">
                <div class="row">
                    <!-- About -->
                    <div class="col-md-4"> <img class="margin-bottom-30"
                            src="<?= TEMPLATE_DEFAULT ?>images/logo-footer.png" alt="logo footer">
                        <p>Origami paper comes in a variety of different types and sizes. It can be confusing to figure
                            out
                            which one is best for your project. In this article, we explain the pros and cons of each
                            different type of origami paper and discuss when each is best used.</p>
                        <ul class="personal-info">
                            <li><i class="fa fa-envelope"></i> <a
                                    href="mailto: <?= getSetting('data_email')->email_admin; ?>" class="text-white"
                                    target="_blank" rel="noopener noreferrer"
                                    title="<?= getSetting('data_email')->name_from; ?>"><?= getSetting('data_email')->name_from; ?></a>
                            </li>
                            <li><i class="fa fa-phone"></i> <?= getSetting('data_seo')->phone; ?> </li>
                        </ul>
                    </div>

                    <!-- Service provided -->
                    <div class="col-md-3">
                        <h6>Privacy Policy</h6>
                        <ul class="links" style="display: grid">
                            <li><a href="<?= url('/page/about-me.html'); ?>">About</a></li>
                            <li><a href="<?= url('/page/contact-me.html'); ?>">Contact</a></li>
                            <li><a href="<?= url('/page/sitemap.html'); ?>">Sitemap</a></li>
                            <li><a href="<?= url('/page/privacy-terms.html'); ?>">Privacy Terms</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h6>Tags</h6>
                        <ul class="links">
                            <li><a href="#origami">Origami</a></li>
                        </ul>
                    </div>
                    <!-- Quote -->
                    <div class="col-md-3">
                        <h6>CONTACT ME ^^</h6>
                        <div class="quote">
                            <form action="page/contact-me.html" method="post">
                                <input class="form-control" type="text" name="username" placeholder="Name">
                                <input class="form-control" type="text" name="phone" placeholder="Phone No">
                                <textarea class="form-control" name="message" placeholder="Messages"></textarea>
                                <button type="submit" class="btn btn-orange">SEND NOW</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rights -->
    <div class="rights">
        <div class="container text-center">
            <p>Copyright © 2022 Origami paper. All Rights Reserved.</p>
        </div>
    </div>
</footer>
