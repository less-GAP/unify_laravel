<?php
$posts = $lessGAPApi->getPosts($block['post_tags'], 24);
?>
<!-- block news -->
<div class="bg-white">
    <div class="xl:container mx-auto px-3 sm:px-4 xl:px-2">
        <div class="flex flex-row flex-wrap">
            <!-- Left -->
            <div class="flex-shrink max-w-full w-full  overflow-hidden">
                <div class="w-full py-3">
                    <h2 class="text-gray-800 text-2xl font-bold">
                        <span class="inline-block h-5 border-l-3 border-red-600 mr-2"></span><?= $block['title'] ?>
                    </h2>
                </div>
                <div class="flex flex-row flex-wrap -mx-3">
                    <?php foreach ($posts as $post) { ?>

                        <div
                            class="flex-shrink max-w-full w-full sm:w-1/3 px-3 pb-3 pt-3 sm:pt-0 border-b-2 sm:border-b-0 border-dotted border-gray-100">
                            <div class="flex flex-row sm:block hover-img">
                                <a href="<?= post_url($post['slug']) ?>">
                                    <img class="max-w-full w-full mx-auto" src="<?= $post['image_url'] ?>"
                                         alt="alt title">
                                </a>
                                <div class="py-0 sm:py-3 pl-3 sm:pl-0">
                                    <h3 class="text-lg font-bold leading-tight mb-2">
                                        <a href="<?= post_url($post['slug']) ?>"><?= $post['title'] ?></a>
                                    </h3>
                                    <p class="hidden md:block text-gray-600 leading-tight mb-1"><?= $post['excerpt'] ?></p>
                                    <span class="text-gray-500" href="#"><span
                                            class="inline-block h-3 border-l-2 border-red-600 mr-2"></span>
                                    <?php $tags = $post['tags'];$class="hover:text-black" ?>
                                        <?php $tags = $post['tags']; ?>
                                        <?php include __DIR__ . "/tags.php"; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>

        </div>
    </div>
</div>
