<?php
    if (!empty($user)) :
?>
<div class="author">
    <div class="avatar">
        <img src="<?php echo getImageThumb($user->avatar) ?>" alt="<?php echo $user->fullname ?>">
    </div>
    <div class="bio">
        <ul>
            <li><b>Tác giả: </b><?php echo $user->allias_name ?></li>
            <li><b>Tham gia keonao: </b><?php echo date('d/m/Y', strtotime($user->created_time)) ?></li>
            <li><b>Bút danh: </b><?php echo $user->description ?></li>
        </ul>
    </div>
</div>

<?php endif;