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
            $con->query("INSERT INTO bentries_dislikes (user, article) SELECT {$userid}, {$id}
            FROM entry
            WHERE EXISTS (
                SELECT eid
                FROM bentry
                WHERE eid = {$id})
            AND NOT EXISTS (
                SELECT id
                FROM bentries_dislikes
                WHERE user = {$userid}
                AND article = {$id})
            LIMIT 1
            ");
        break;
    }
}

header("Location: barticle.php?eid=$id&reviewtype=$reviewtype&title=$title");