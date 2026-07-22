<?php
use SPT\Settings\SettingKeys;
if (! defined('ABSPATH')) {
    exit;
}

settings_errors('spt');
?>

<div class="wrap">

  <div class="spt-header">
      <img src="<?= esc_url($logo_url); ?>" alt="Seven Places Toolkit">

      <div class="spt-header-content">
          <h1>Seven Places Toolkit</h1>

          <p>Modular utilities for WordPress developers.</p>

          <span class="spt-version">
              Version <?= esc_html($version); ?>
          </span>
      </div>
  </div>

    <form method="post">

        <?php wp_nonce_field('spt_settings'); ?>

        <table class="form-table">

            <tbody>

                <tr>

                    <th scope="row">

                        Full Width Gutenberg Editor

                    </th>

                    <td>

                        <label>

                            <input
                                type="checkbox"
                                name="<?= esc_attr(SettingKeys::EDITOR_WIDTHS); ?>"
                                value="1"
                                <?php checked(
                                  $settings->enabled(
                                    SettingKeys::EDITOR_WIDTHS
                                  )
                                ); ?>

                            >

                            Remove WordPress' default content width restrictions inside the block editor.

                        </label>

                    </td>

                </tr>

            </tbody>

        </table>

        <?php submit_button(); ?>

    </form>

</div>
