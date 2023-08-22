<?php
$posts = $lessGAPApi->getPosts($block['post_tags'], 5);
?>
<div class="bg-white py-6">
    <div class="xl:container mx-auto px-3 sm:px-4 xl:px-2">
        <!-- big grid 1 -->
        <div class="flex flex-row flex-wrap">
            <!--Start left cover-->
            <div class="flex-shrink max-w-full w-full lg:w-1/2 pb-1 lg:pb-0 lg:pr-1">
                <?php $primaryPost = array_shift($posts) ;?>
                <div class="relative hover-img max-h-98 overflow-hidden">
                    <a href="<?= post_url($primaryPost['slug'])?>">
                        <img class="max-w-full w-full mx-auto h-auto" src="<?=$primaryPost['image_url']?>"
                             alt="Image description">
                    </a>
                    <div class="absolute px-5 pt-8 pb-5 bottom-0 w-full bg-gradient-cover">
                        <a href="<?= post_url($primaryPost['slug'])?>">
                            <h2 class="text-3xl font-bold capitalize text-white mb-3"><?= $primaryPost['title'] ?></h2>
                        </a>
                        <p class="text-gray-100 hidden sm:inline-block"><?= $primaryPost['excerpt'] ?></p>
                        <div class="pt-2">
                            <div class="text-gray-100">
                                <div class="inline-block h-3 border-l-2 border-red-600 mr-2"></div>
                                <?php $tags = $primaryPost['tags'];$class="hover:text-gray" ?>
                                <?php include __DIR__ . "/tags.php"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Start box news-->
            <div class="flex-shrink max-w-full w-full lg:w-1/2">
                <div class="box-one flex flex-row flex-wrap">
                    <?php foreach ($posts as $post){?>

                        <article class="flex-shrink max-w-full w-full sm:w-1/2">
                            <div class="relative hover-img max-h-48 overflow-hidden">
                                <a href="<?= post_url($post['slug']) ?>">
                                    <img class="max-w-full w-full mx-auto h-auto" src="<?= $post['image_url'] ?>"
                                         alt="Image description">
                                </a>
                                <div class="absolute px-4 pt-7 pb-4 bottom-0 w-full bg-gradient-cover">
                                    <a href="<?= post_url($post['slug']) ?>">
                                        <h2 class="text-lg font-bold capitalize leading-tight text-white mb-1"><?=$post['title']?></h2>
                                    </a>
                                    <div class="pt-1">
                                        <div class="text-gray-100">
                                            <div class="inline-block h-3 border-l-2 border-red-600 mr-2"></div>
                                            <?php $tags = $post['tags'];$class="hover:text-gray" ?>
                                            <?php include __DIR__ . "/tags.php"; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
