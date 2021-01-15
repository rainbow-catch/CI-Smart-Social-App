<?php
  $ad = $this->home_model->get_random_ad();
  if($ad->num_rows() > 0) {
    $ad = $ad->row();
    // Reduce pageviews
    $this->home_model->decrease_ad_pageviews($ad->ID);
?>
<div class="page-block half-separator">
<div class="page-block-page clearfix">
<?php echo $ad->advert ?> 
</div>
</div>
<?php
}
?>