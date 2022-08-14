<main>
    <div class="row" id="newcontent">
        <div class="col-md-12" style="margin-bottom: 7px;"><?= $breadcrumb; ?></div>
        <div class="col-md-9 page-tool" id="content">
            <div class="container p-0 m-0">
                <div class="row m-0 rootitem">
                    <?php foreach ($data as $key => $oneItem) : ?>
                    <div class="col-md-6 listitem">
                        <img src="<?= $oneItem->thumbnail; ?>" alt="<?= $oneItem->title; ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</main>