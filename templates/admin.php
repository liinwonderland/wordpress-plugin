<div class="container">
  <h1>This is a Admin page for Plugin</h1>
  <?php settings_errors(); ?>
  <form class="" action="options.php" method="post">
    <?php
      settings_fields( 'morrigan_options_group' );
      do_settings_sections( 'morrigan_plugin' );
      submit_button();
     ?>
  </form>
</div>
