<?php if (!empty($listweekday)) : if(empty($oneParent)) $oneParent = $oneItem; ?>
    <div class="row">
        <div class="w-100">
            <div class="submenu2">
                <ul class="text-center text-capitalize rounded lh25 submenu2-bg colum-4 pl-0 pl-md-3">
                    <li class="active">
                        <a href="<?php echo getUrlCategoryRS($oneParent) ?>" title="<?php echo $oneParent->title ?>"><?php echo $oneParent->code ?></a>
                    </li>
                    <?php foreach ($listweekday as $item) : ?>
                        <li class="mx-md-4">
                            <a href="<?php echo getUrlCategory($item) ?>" title="<?php echo $item->title ?>"><?php echo $item->title ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>