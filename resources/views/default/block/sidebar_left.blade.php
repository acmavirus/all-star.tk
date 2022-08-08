<div class="side-bar">
    <!-- Popular Post -->
    <h5 class="font-alegreya">Popular Post</h5>
    <div class="papu-post margin-t-40">
        <ul class="bg-defult">
          <?php foreach ($popular as $key => $item): ?>
            <li class="media">
                <div class="media-left"> <a href="<?= post_url($item); ?>"> <img class="media-object" src="<?= $item->thumbnail; ?>" alt=""></a>
                </div>
                <div class="media-body"> <a class="media-heading" href="<?= post_url($item); ?>"><?= $item->title; ?></a> <span><?= date("Y-m-d", strtotime($item->updated_time)); ?></span> </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>