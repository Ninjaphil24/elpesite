<?php
if (isset($_POST['insert'])) {
    $userIDentry = $fetch_info['id'];
    $reviewtype  = $con->real_escape_string($_POST['reviewtype']);
    $title       = $con->real_escape_string($_POST['title']);
    $link        = $con->real_escape_string($_POST['link']);
    // $video = $con->real_escape_string($_POST['video']);
    $biog    = $_FILES['biog']['name'];
    $targetb = "pdf/" . basename($biog);

    $errors = [];

    if (empty($title)) {
        $errors['t'] = "Title Required";
    }

    if (count($errors) == 0) {
        $query = "INSERT INTO entry(userIDentry,reviewtype,title,link,biog,createdOn)
        VALUES ('$userIDentry','$reviewtype','$title','$link','pdf/$biog',NOW())";
        move_uploaded_file($_FILES['biog']['tmp_name'], $targetb);
        $result = mysqli_query($con, $query);

        if ($result) {
            header("Location: home.php");
            die();
        } else {

            $query = "SELECT * FROM entry WHERE reviewtype = '$reviewtype' AND title = '$title'";

            $result = mysqli_query($con, $query);

            $row = mysqli_fetch_assoc($result);

            header("Location: article.php?eid=" . $row['eid'] . "&reviewtype=" . $row['reviewtype'] . "&title=" . $row['title'] . "");
            die();
        }
    }

}
