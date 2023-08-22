<?php
global $domain, $config, $lessGAPApi, $page;

if (isset($pageSlug) && isset(config('pages')[$pageSlug])) {
    $page = config('pages')[$pageSlug];
} else {
    $page = config('pages')['home'];
}
?>
<?php include __DIR__ . '/header.php'; ?>

<?php
foreach ($page['blocks'] as $block) { ?>
    <?php include __DIR__ . "/blocks/" . $block['block'] . ".php"; ?>
<?php } ?>

<?php include __DIR__ . '/footer.php'; ?>
