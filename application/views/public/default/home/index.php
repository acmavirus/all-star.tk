<main>
    <div class="row p-0">
        <div class="col-md-12 p-0 m-0"><?= $breadcrumb; ?></div>
    </div>
    <div class="row" id="newcontent">
        <div class="col-md-3">
        </div>
        <div class="col-md-6 p-0 page-home" id="content">
            <div class="container p-0 m-0">
                <div class="row m-0 rootitem">
                    <?php foreach ($data as $key => $oneItem) : ?>
                    <div class="col-md-6 listitem">
                        <a href="<?= base_url($oneItem->slug."-post.html"); ?>" title="<?= $oneItem->title; ?>"><img src="<?= $oneItem->thumbnail; ?>" alt="<?= $oneItem->title; ?>"></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</main>