
  <?php
      if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
          $page_no = $_GET['page_no'];
      } else {
          $page_no = 1;
      }

      $total_records_per_page = 10;
      $offset                 = ($page_no - 1) * $total_records_per_page;
      $previous_page          = $page_no - 1;
      $next_page              = $page_no + 1;
      $adjacents              = "2";

      $result_count      = mysqli_query($con, "SELECT COUNT(*) As total_records FROM `entry`");
      $total_records     = mysqli_fetch_array($result_count);
      $total_records     = $total_records['total_records'];
      $total_no_of_pages = ceil($total_records / $total_records_per_page);
      $second_last       = $total_no_of_pages - 1; // total page minus 1

      $sql         = "SELECT * FROM entry ORDER BY eid DESC LIMIT $offset, $total_records_per_page";
      $result      = mysqli_query($con, $sql);
      $queryResult = mysqli_num_rows($result);

      if ($queryResult > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              // https://www.delftstack.com/howto/php/how-to-convert-a-date-to-the-timestamp-in-php/
              $timestamp = $row['createdOn'];
              $date      = date("d-m-Y", strtotime($timestamp));

              // $dateunix = new DateTime($timestamp);
              // $timeunix = $dateunix->getTimestamp();
              // $fiveSeconds = 5;
              // $t3600secs = 3600;
              // $sum = $timeunix + $fiveSeconds;
              // $unixnow = time()+3600;
              // https://www.javatpoint.com/php-adding-two-numbers
              // if($unixnow<$sum)echo "<span>";

              echo "<a href='article.php?eid=" . $row['eid'] . "&reviewtype=" . $row['reviewtype'] . "&title=" . $row['title'] . "'>

        <li>
    <p>" . htmlspecialchars($row['reviewtype']) . "<br>
    <h5>" . htmlspecialchars($row['title']) . "</h5><br>
    ΗΜΕΡΟΜΗΝΙΑ ΑΝΑΡΤΗΣΗΣ:&nbsp;" . htmlspecialchars($date) . "</p>
    <hr>";

              if (! empty($row['link'])) {
                  $host = parse_url($row['link'], PHP_URL_HOST);
                  echo "
    <div class='link-preview'>
        <a href='" . htmlspecialchars($row['link'], ENT_QUOTES) . "' target='_blank'>
            <img src='https://www.google.com/s2/favicons?sz=64&domain=" . htmlspecialchars($host) . "' alt='favicon'>
            <div class='link-preview-content'>
                <strong>" . htmlspecialchars($host) . "</strong>
                <em>Click to open external site</em>
            </div>
        </a>
    </div>
    <br>";
              }

              echo "</li><br>
        <br>";
          }

      }
      mysqli_close($con);
  ?>
</ul>
  <br>
  <div class="myButton">
  <a href="#top">Back to top</a>
  </div>
  <br>
</div>
</div>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC; width: 200px; background:white;'>
<strong>Page                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
</div>

<ul class="pagination">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

	<li	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	    <?php if ($page_no <= 1) {echo "class='disabled'";}?>>
	<a	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	   <?php if ($page_no > 1) {echo "href='?page_no=$previous_page'";}?>>Previous</a>
	</li>

    <?php
        if ($total_no_of_pages <= 10) {
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                if ($counter == $page_no) {
                    echo "<li class='active'><a>$counter</a></li>";
                } else {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
            }
        } elseif ($total_no_of_pages > 10) {

            if ($page_no <= 4) {
                for ($counter = 1; $counter < 8; $counter++) {
                    if ($counter == $page_no) {
                        echo "<li class='active'><a>$counter</a></li>";
                    } else {
                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                }
                echo "<li><a>...</a></li>";
                echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                echo "<li><a href='?page_no=1'>1</a></li>";
                echo "<li><a href='?page_no=2'>2</a></li>";
                echo "<li><a>...</a></li>";
                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                    if ($counter == $page_no) {
                        echo "<li class='active'><a>$counter</a></li>";
                    } else {
                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                }
                echo "<li><a>...</a></li>";
                echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
            } else {
                echo "<li><a href='?page_no=1'>1</a></li>";
                echo "<li><a href='?page_no=2'>2</a></li>";
                echo "<li><a>...</a></li>";

                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                    if ($counter == $page_no) {
                        echo "<li class='active'><a>$counter</a></li>";
                    } else {
                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                }
            }
        }
    ?>

	<li	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	   	    <?php if ($page_no >= $total_no_of_pages) {echo "class='disabled'";}?>>
	<a	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	   <?php if ($page_no < $total_no_of_pages) {echo "href='?page_no=$next_page'";}?>>Next</a>
	</li>
    <?php if ($page_no < $total_no_of_pages) {
        echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
    }?>