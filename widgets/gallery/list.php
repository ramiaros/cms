<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Gallery
 */

$slug = in('slug');
$posts =  lib()->postSearch(['slug' => $slug, 'numberposts' => 10]);
//dog($posts);
//if ( isBackendError($posts) ) {
//    return include page('error.wrong-input', $posts);
//}
?>
gallery list
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
<div class="container pb-3">
    <?php
    foreach($posts as $post){
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="circle overflow-hidden wh50x50">
                        <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                    </div>
                    <div class="col">
                        <div>
                            <?php if (!empty($post['files'])) echo "<i class='fa fa-images'></i>" ?>

                            <span><?=$post['author_name']?></span>
                            <span>Date: <?=$post['short_date_time']?></span>
                            <span>View: <?=$post['view_count']??''?></span>
                        </div>
                        <a class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>