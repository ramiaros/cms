<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Default post edit
 */

$ID = in('ID');
$slug = in('slug');

$post = [
    'ID' => '',
    'guid' => '',
    'post_title' => '',
    'post_content' => '',
    'files' => []
];
if ($ID) {
    $post =  post()->postGet(['ID' => $ID, 'post_count' => false]);
}
?>


<div class="post-edit container py-3">

    <form class="post-form" onsubmit="return onPostEditFormSubmit(this);">
        <input type="hidden" name="route" value="post.edit">
        <input type="hidden" name="slug" value="<?=$slug?>">

        <input type="hidden" name="ID" value="<?=$post['ID']?>">

        <div class="form-group">
            <label for="post-create-title">Title</label>
            <input type="text" class="form-control" name="post_title" id="post-create-title" aria-describedby="Title" placeholder="Enter title" value="<?=$post['post_title']?>">
        </div>

        <div class="form-group">
            <label for="post-create-content">Content</label>
            <textarea class="form-control" name="post_content" id="post-create-content" aria-describedby="Content" placeholder="Enter content"><?=$post['post_content']?></textarea>
        </div>
        <div class="upload-photo-box">
            <input type="file" name="file"
                   onchange="onChangeFile(this, {append: $('.files'), deleteButton: true, extraClasses: 'col-4 col-sm-3', progress: $(this).parents('.post-edit').find('.progress')})">
            <i role="button" class="fa fa-camera"></i>
        </div>
        <div class="progress mb-3" style="display: none">
            <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="files edit">
            <script>
                var files = <?=json_encode($post['files']);?>;
                $$(function() {
                    for ( var file of files ) {
                        $('.edit form .files').append(getUploadedFileHtml(file, {deleteButton: true, extraClasses: 'col-4 col-sm-3'}));
                    }
                });
            </script>
            <!-- uploaded files -->

        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
