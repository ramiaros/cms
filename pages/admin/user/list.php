<?php
$no_of_records_per_page = 20;
$page_no = in('page_no', 1);
if ( in('page_no', 1) < 1 ) $page_no = 1;

$users = get_users( [ 'fields' => 'all_with_meta', 'number' => $no_of_records_per_page, 'paged' => $page_no] );
?>
    <div class="row font-weight-bold" onclick="move('$ln')">
        <div class="col-1">ID</div>
        <div class="col">Nickname</div>
        <div class="col">Email</div>
        <div class="col">Action</div>
    </div>
<?php
foreach($users as $user){
    $ln = "?page=admin.user.edit&ID=" . $user->ID;
    echo <<<EOH
<div class="row">
    <div class="col-1">{$user->ID}</div>
    <div class="col">{$user->nickname}</div> 
    <div class="col">{$user->user_email}</div>
    <div class="col-1  pointer" onclick="move('$ln')">Edit</div>
</div>
EOH;

//    dog($user);
}
?>
<?php

include widget('pagination', [
    'total_rows' => count_users()['total_users'],
    'no_of_records_per_page' => $no_of_records_per_page,
    'url' => '/?page=admin.user.list&page_no={page_no}',
    'page_no' => $page_no,
]);



