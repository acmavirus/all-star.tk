<?php
$logo = (!empty($this->_settings->logo)) ? $this->_settings->logo : '';
$email = (!empty($this->_settings->email)) ? $this->_settings->email : '';
$phone = $this->_settings->phone;
$title = $this->_settings->title;
$facebook = $this->_settings_social->facebook;
$twitter = $this->_settings_social->twitter;
$youtube = $this->_settings_social->youtube;
$instagram = $this->_settings_social->instagram;
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="<?php echo $this->_settings->domain ?>">
<meta name="theme-color" content="#081A48">
<?php if (!empty($SEO)): ?>
<title><?php
        if(!empty($SEO['meta_title'])){
            if($this->_controller === 'post'){
                echo getStringCut($SEO['meta_title'],0,55);
            }else{
                echo $SEO['meta_title'];
            }
        }else{
            echo '';
        }
        ?></title>
<meta name="description" content="<?php
    if(!empty($SEO['meta_description'])){
        if($this->_controller === 'post'){
            echo  strip_tags(getStringCut($SEO['meta_description'], 0, 120));
        }else{
            echo strip_tags($SEO['meta_description']);
        }
    }else{
        echo strip_tags($SEO['meta_title']);
    }
    ?>" />
<meta name="keywords" content="<?php echo !empty($SEO['meta_keyword']) ? $SEO['meta_keyword'] : ''; ?>" />
<!--Meta Facebook Page Other-->
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo getStringCut($SEO['meta_title'],0,55); ?>" />
<meta property="og:description"
    content="<?php echo !empty($SEO['meta_description']) ? strip_tags(getStringCut($SEO['meta_description'],0,120)) : $SEO['meta_title']; ?>" />
<meta property="og:image"
    content="<?php echo !empty($SEO['image']) ? $SEO['image'] : base_url('picture/thumb/logo/XOSOVTV-BANNER-1200x650.png'); ?>" />
<meta property="og:url" content="<?php echo !empty($SEO['url']) ? $SEO['url'] : base_url(); ?>" />

<!--Facebook OG-->
<link rel="canonical" href="<?php echo !empty($SEO['url']) ? $SEO['url'] : base_url(); ?>" />
<meta name="robots" content="index,follow" />
<meta name="Googlebot-News" content="index,follow" />

<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('public/images/apple-touch-icon.png') ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('public/images/favicon-32x32.png') ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('public/images/favicon-16x16.png') ?>">
<!--<link rel="manifest" href="--><?php //echo base_url('public/images/site.webmanifest') ?>
<!--">-->
<link rel="mask-icon" href="<?php echo base_url('public/images/safari-pinned-tab.svg') ?>" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<?php endif; ?>
<meta property="fb:app_id" content="268051391138932" />

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "<?php echo base_url() ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo base_url() ?>tim-kiem?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
</script>
<?php
if (!empty($oneItem) && $this->_controller === 'post' && $this->_method === 'detail') {
    $updated_time   = strtotime($oneItem->updated_time);
    $displayed_time = !empty($oneItem->displayed_time) ? strtotime($oneItem->displayed_time) : strtotime($oneItem->created_time);
    echo $this->schema::init('NewsArticle')
        ->headline($oneItem->title)
        ->description($oneItem->description)
        ->stack('image')
        ->type('ImageObject')
        ->url($SEO['image'])
        ->width(500)
        ->height(250)
        ->_stack()
        ->datePublished(date('c', $displayed_time))
        ->dateModified(date('c', $updated_time))
        ->stack('author')
        ->type('Person')
        ->name(!empty($oneItem->author_name) ? $oneItem->author_name : '11m')
        ->_stack()
        ->stack('publisher')
        ->type('Organization')
        ->name($this->_settings->title)
        ->add_section(
            'logo',
            Schema::init(false)
                ->type('ImageObject')
                ->url(getImageThumb($this->_settings->logo,160,60))
                ->width(160)
                ->height(60)
                ->render_data()
        )
        ->_stack()
        ->stack('mainEntityOfPage')
        ->type('WebPage')
        ->id($SEO['url'], '@')
        ->_stack()
        ->render();
}
?>