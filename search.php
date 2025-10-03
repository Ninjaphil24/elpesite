<?php
require "connection.php";

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($con, $_POST['search']);
    if ($search === "ALL") {
        $sql = "SELECT * FROM entry ORDER BY eid DESC LIMIT 20"; // adjust limit if needed
    } else {
        $sql = "SELECT * FROM entry WHERE reviewtype LIKE '%$search%' ORDER BY eid DESC LIMIT 20";
    }
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $timestamp = $row['createdOn'];
            $date      = date("d-m-Y", strtotime($timestamp));
            echo "<a href='article.php?eid=" . $row['eid'] . "&reviewtype=" . $row['reviewtype'] . "&title=" . $row['title'] . "'>
                <li>
                    <p><span>" . $row['reviewtype'] . "<br>
                    <h5>" . $row['title'] . "</h5><br>
                    ΗΜΕΡΟΜΗΝΙΑ ΑΝΑΡΤΗΣΗΣ:&nbsp;" . $date . "</p><hr>";
            if ($row['link'] != null) {
                echo "<iframe src='" . $row['link'] . "' style='width: 100%; height: 300px;'></iframe>";
            }
            echo "</li></a><br>";
        }
    } else {
        echo "<li>Δεν βρέθηκαν αποτελέσματα</li>";
    }
}
mysqli_close($con);
