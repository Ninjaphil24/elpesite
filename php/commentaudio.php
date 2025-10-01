<?php
if (isset($_POST['insert'])) {

    $comment = $con->real_escape_string($_POST['comment']);

    $errors = [];

    if (empty($comment)) {
        $errors['r'] = "Review Required";
    }

    if (count($errors) == 0) {

        $query = "INSERT INTO comments (userID, entryID, comment, commentCreatedOn) VALUES ('" . $fetch_info['id'] . "','$eid','$comment',NOW())";

        $result = mysqli_query($con, $query);

        if ($result) {
            header("Location: article.php?eid=$eid&reviewtype=$reviewtype&title=$title#comment");
            die();

            // echo "<script>alert('done')</script>"
        } else {

            echo "<script>alert('failed')</script>";
        }
    }
}
// Echoing the number of entries, in this case comments
if (isset($_POST['uploadAudio']) && isset($_FILES['audioComment'])) {
    $userID     = $fetch_info['id'];
    $eid        = $_POST['eid'];
    $reviewtype = $_POST['reviewtype'];
    $title      = $_POST['title'];

    $targetDir = "audio/";
    if (! is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $filename   = uniqid() . "_" . basename($_FILES['audioComment']['name']);
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($_FILES['audioComment']['tmp_name'], $targetFile)) {
        $query = "INSERT INTO comments (userID, entryID, comment, commentCreatedOn)
                  VALUES ('$userID', '$eid', '$targetFile', NOW())";
        mysqli_query($con, $query);
        header("Location: article.php?eid=$eid&reviewtype=$reviewtype&title=$title#comment");
        exit();
    } else {
        echo "Error uploading audio file.";
    }
}
