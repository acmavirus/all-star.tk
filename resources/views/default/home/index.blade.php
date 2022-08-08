<div class="container padding-top-100 padding-bottom-100">
    <div class="row">
        <div class="col-md-3 col-12 order-2 order-md-1">@include('default/block/sidebar_left');</div>
        <div class="col-md-6 col-12 order-1 order-md-2">
            <!-- Tittle -->
            <div class="heading-block text-center margin-bottom-80">
                <h2>Origami pepar newest </h2>
                <!-- Cases -->
                <div class="case">
                    <ul class="row">
                        <?php foreach ($newest as $key => $item): ?>
                        <li class="col-md-6 col-12">
                            <article> <a href="<?= post_url($item); ?>" title=""> <img class="img-responsive" src="<?= $item->thumbnail; ?>"
                                        alt=""> </a>
                                <div class="case-detail">
                                    <p><?= $item->title; ?></p>
                                </div>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Button -->
                    <div class="text-center margin-top-50"> <a href="#." class="btn btn-orange">View More</a> </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12 order-3">@include('default/block/sidebar_right');</div>
    </div>
</div>