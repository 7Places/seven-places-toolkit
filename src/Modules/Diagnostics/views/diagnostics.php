<?php
declare(strict_types=1);

/** @var array<string, mixed> $diagnostics */
?>

<div class="wrap">

<?php require $app->path('src/Views/partials/admin-header.php'); ?>

<h2 class="title">Framework</h2>

<table class="widefat striped">
  <tbody>
  <?php foreach ($diagnostics['framework'] as $label => $value) : ?>
      <tr>
          <th><?php echo esc_html($label); ?></th>
          <td><code><?php echo esc_html((string) $value); ?></code></td>
      </tr>
  <?php endforeach; ?>
  </tbody>
</table>


<h2 class="title">Environment</h2>

<table class="widefat striped">
    <tbody>
    <?php foreach ($diagnostics['environment'] as $label => $value) : ?>
        <tr>
            <th><?php echo esc_html($label); ?></th>
            <td><?php echo esc_html((string) $value); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<h2 class="title">Settings</h2>

<?php if ([] === $diagnostics['settings']) : ?>

    <p>No settings stored.</p>

<?php else : ?>

    <table class="widefat striped">
        <tbody>

        <?php foreach ($diagnostics['settings'] as $key => $value) : ?>

            <tr>
                <th><?php echo esc_html($key); ?></th>
                <td>
                    <code>
                        <?php
                        echo esc_html(
                            is_scalar($value)
                                ? (string) $value
                                : wp_json_encode($value, JSON_PRETTY_PRINT)
                        );
                        ?>
                    </code>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>

</div>
