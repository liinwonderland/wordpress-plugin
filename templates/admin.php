<div class="container">
  <h1>This is an Admin page for Plugin 1</h1>
  <?php settings_errors(); ?>
  <form class="" action="options.php" method="post">
    <?php
      settings_fields( 'morrigan_options_group' );
      do_settings_sections( 'morrigan_plugin' );
      submit_button();
     ?>
  </form>
</div>
