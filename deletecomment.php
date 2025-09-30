<?php

require "connection.php";

$cid = $_GET['cid']; // get appID through query string
$eid= mysqli_real_escape_string($con, $_GET['eid']);
$reviewtype= mysqli_real_escape_string($con, $_GET['reviewtype']);
$title = mysqli_real_escape_string($con, $_GET['title']);

$del = mysqli_query($con,"DELETE FROM comments WHERE cid = '$cid'"); // delete query

if($del)
{
    mysqli_close($con); // Close connection
    header("location:article.php?eid=$eid&reviewtype=$reviewtype&title=$title"); // redirects to all records page
    exit;	
}
else
{
    echo "Error deleting record"; // display error message if not delete
}
?>