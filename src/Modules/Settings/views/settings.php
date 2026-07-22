<?php
use SPT\Settings\SettingKeys;
if (! defined('ABSPATH')) {
    exit;
}

settings_errors('spt');
?>

<div class="wrap">

  <?php require $app->path('src/Views/partials/admin-header.php'); ?>

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
