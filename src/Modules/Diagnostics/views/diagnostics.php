<?php
declare(strict_types=1);

/** @var SPT\Core\Application $app */
?>

<div class="wrap">

    <h1><?php echo esc_html($app->name()); ?></h1>

    <table class="widefat striped">

        <tbody>

            <tr>
                <th>Version</th>
                <td><?php echo esc_html($app->version()); ?></td>
            </tr>

            <tr>
                <th>Plugin Path</th>
                <td><?php echo esc_html($app->pluginPath()); ?></td>
            </tr>

            <tr>
                <th>Plugin URL</th>
                <td><?php echo esc_html($app->pluginUrl()); ?></td>
            </tr>

            <tr>
                <th>Text Domain</th>
                <td><?php echo esc_html($app->textDomain()); ?></td>
            </tr>

        </tbody>

    </table>

</div>
