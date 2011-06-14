<?php
  wp_nonce_field('picasso-general');
  wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
  wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );

?>

<div id="picasso-general" class="wrap">
<?php screen_icon('options-general'); ?>
<h2>Picasso</h2>		
  <div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
    <div id="side-info-column" class="inner-sidebar">
      <?php do_meta_boxes($pagehook, 'side', $data); ?>
    </div>
    <div id="post-body" class="has-sidebar">
      <div id="post-body-content" class="has-sidebar-content">
        <?php do_meta_boxes($pagehook, 'normal', $data); ?>
      
        <?php do_meta_boxes($pagehook, 'additional', $data); ?>
      </div>
    </div>
    <br class="clear"/>
            
  </div>	
</div>
  
<script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready( function($) {
    $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
    postboxes.add_postbox_toggles('<?php echo $pagehook; ?>');
  });
  //]]>
</script>