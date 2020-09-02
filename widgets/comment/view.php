<?php


?>

<div id="comment<?=$comment['comment_ID']?>">

    <div class="display" data-comment-post-id="<?=$comment['comment_post_ID']?>" data-comment-id="<?=$comment['comment_ID']?>" data-comment-parent="<?=$comment['comment_parent']?>" data-depth="<?=$comment['depth']??1?>">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <div>
                        <span><?=$comment['comment_author']?></span>
                        <span>Date: <?=$comment['short_date_time']?></span>
                        <span>View: <?=$comment['view'] ?? ''?></span>
                    </div>
                    <div class="content">
                        <?=$comment['comment_content']?>
                    </div>
                </div>
                <div class="files mb-3"></div>
                <script>

                    $$(function() {
                        attachUploadedFilesTo($('#comment<?=$comment['comment_ID']?> .files'), <?=json_encode($comment['files']);?>, {extraClasses: 'col-4 col-sm-3'});
                    });
                </script>
                <div>
                    <button class="btn btn-primary mr-3" onclick="addCommentEditForm(0, <?=$comment['comment_ID']?>)">Reply</button>
                    <?php
                    if($comment['user_id'] == userId()) { ?>
                        <button class="btn btn-primary mr-3" onclick="addCommentEditForm(<?=$comment['comment_ID']?>, 0)">Edit</button>
                        <button class="btn btn-primary mr-3" onclick="onCommentDelete(<?=$comment['comment_ID']?>)">Delete</button>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>

</div>
