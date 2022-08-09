<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 14/12/2017
 * Time: 12:06 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow"> Bạn không có quyền truy cập chức năng này !</h2>

        <div class="error-content">

            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="<?php echo site_url('admin/dashboard') ?>">return to dashboard</a>.
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<?php exit; ?>