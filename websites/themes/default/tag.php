<?php
global $domain, $config, $lessGAPApi, $page;
?>
<?php include __DIR__ . '/header.php'; ?>

<?php $block = [
    'post_tags' => [$tagSlug],
    'title' => 'Tag '.$tagSlug
] ;?>
<?php include __DIR__ . "/blocks/news.php"; ?>


<?php include __DIR__ . '/footer.php'; ?>
