<?php
$domain = $this->_settings->domain ?? '';
$logo_footer = $this->_settings->logo_footer ?? '';
$meta_desc = $this->_settings->meta_description ?? '';
$des_footer = $this->_settings->des_footer ?? '';
$content_footer = $this->_settings->content_footer ?? '';
$email = $this->_settings->email ?? '';
$phone = $this->_settings->phone ?? '';
?>
<footer>
	<div class="footer-menu">
		<div class="container">
			<div style="justify-content: space-evenly;" class="row d-flex align-items-center foot-menu pt-4 pb-4">
				<div class="col-12 col-lg-2 p-sm-1">
					<div class="logo">
						<a href="<?php echo base_url() ?>" title="<?php echo $this->_settings->meta_title ?>">
							<?php echo getThumbnailStatic((!empty($logo_footer)) ? getImageThumb($logo_footer) : TEMPLATES_ASSETS . 'no-logo.png', 152, 78, 'logo', "img-fluid logo-top") ?>
						</a>
					</div>
				</div>
				<div class="col-12 col-lg-7 p-sm-1">
					<?= $content_footer ?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-end">
		<div class="container">
			<div style="text-align:center; justify-content:center" class="row d-flex align-items-center copyright">
				<div class="col-xl-5 col-lg-6 col-md-7 p-sm-1">
					<span class="d-none d-md-block">
						<?php echo $des_footer ?>
					</span>
					<span class="d-block d-md-none">
						<?php echo $des_footer ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</footer>
<div id="menu-mobile" class="mm-menu d-md-none mm--vertical mm--main mm--offcanvas" data-mm-title="">
	<div class="menu-home-menu"><a href="">Trang chủ</a></div>
	<?php echo navMenuMain("mm--open", "", "sub-menu") ?>
</div>
<div class="back-top d-none">
	<img width="42" height="42" src="public/images/backtotop.png" alt="Backtotop" />
</div>