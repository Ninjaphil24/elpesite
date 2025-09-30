<!-- https://www.mysqltutorial.org/mysql-delete-join/ -->
<?php

require "connection.php";

$eid = $_GET['eid']; // get appID through query string

$del = mysqli_query($con,"DELETE FROM bentry WHERE eid = '$eid'"); // delete query
// AND DELETE FROM comments WHERE entryID = '$eid'
if($del)
{
    mysqli_close($con); // Close connection
    header("location:boardmembers.php"); // redirects to all records page
    exit;	
}
else
{
    echo "Error deleting record"; // display error message if not delete
}
?>