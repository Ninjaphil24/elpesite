<?php

require_once 'connection.php';
$reviewtype= mysqli_real_escape_string($con, $_GET['reviewtype']);
$title = mysqli_real_escape_string($con, $_GET['title']);
if(isset($_GET['type'], $_GET['id'])){
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    $userid = $_GET['userid'];

    switch($type) {
        case 'article':
            $con->query("INSERT INTO entries_likes (user, article) SELECT {$userid}, {$id}
            FROM entry
            WHERE EXISTS (
                SELECT eid
                FROM entry
                WHERE eid = {$id})
            AND NOT EXISTS (
                SELECT id
                FROM entries_likes
                WHERE user = {$userid}
                AND article = {$id})
            LIMIT 1
            ");
        break;
    }
}

header("Location: article.php?eid=$id&reviewtype=$reviewtype&title=$title");