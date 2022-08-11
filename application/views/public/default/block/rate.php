<?php $this->load->helper('regex'); ?>
<?php if (!empty($reviews)) : ?>
<div class="allRate">
    <input type="range" value="<?php echo !empty($reviews->avg) ? $reviews->avg : 5 ?>" step="0.25" id="backing5">
    <div class="rateit" data-rateit-backingfld="#backing5" data-rateit-resetable="false" data-rateit-ispreset="true"
         data-rateit-min="0" data-rateit-max="5" data-rateit-mode="font" data-rateit-icon=""
         style="font-family:fontawesome">
    </div>
    <span class="danhgia">
        <span class="avg-rate"><?php echo empty($reviews->avg) ? 5 : $reviews->avg ?></span> /<span>5</span> của
        <span class="count-rate"><?php echo !empty($reviews->count_vote) ? $reviews->count_vote : 1 ?></span> đánh giá</span>
    </span>
</div>

<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "AggregateRating",
        "ratingValue": "<?php echo empty($reviews->avg) ? 5 : $reviews->avg ?>",
        "bestRating": "5",
        "ratingCount": "<?php echo !empty($reviews->count_vote) ? $reviews->count_vote : 1 ?>",
        "itemReviewed": {
            "@type": "CreativeWorkSeries",
            "name": "<?php echo toNormalTitle($oneItem->title) ?>" }
    }
</script>

<?php endif; ?>