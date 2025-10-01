<?php
    $eid        = mysqli_real_escape_string($con, $_GET['eid']);
    $reviewtype = mysqli_real_escape_string($con, $_GET['reviewtype']);
    $title      = mysqli_real_escape_string($con, $_GET['title']);

    $sql    = "SELECT * FROM bentry WHERE eid='$eid' AND title='$title'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
            $timestamp = $row['createdOn'];
            $date      = date("d-m-Y", strtotime($timestamp));
        ?>
						        <!-- Link preview -->
						        <?php if (! empty($row['link'])): ?>
						            <div class="link">
						                <iframe src="<?php echo htmlspecialchars($row['link']) ?>" width="100%" height="250"></iframe>
						            </div>
						            <a href="<?php echo htmlspecialchars($row['link']) ?>" class="myButton" target="_blank">Visit Website</a>
						            <br><br>
						        <?php endif; ?>

			        <!-- PDF preview -->
			        <?php if ($row['biog'] !== 'pdf/'): ?>
			            <div class="pdf">
			                <button onclick="myFunction()">ΕΠΙΣΥΝΑΠΤΟΜΕΝΟ (ΑΝΟΙΓΜΑ/ΚΛΕΙΣΜΙΟ)</button>
			                <div id="pdf" style="display: none;">
			                    <p>
			                        <iframe src="<?php echo htmlspecialchars($row['biog']) ?>" width="1000px" height="1000px"></iframe>
			                    </p>
			                </div>
			            </div>
			        <?php endif; ?>

        <!-- Entry details -->
        <div class="box">
            <h3><?php echo htmlspecialchars($row['reviewtype']) ?></h3>
            <h3><?php echo htmlspecialchars($row['title']) ?></h3>
            <p>Ημερομηνία ανάρτησης:                                                                                                               <?php echo $date ?></p><br>
        </div>

        <!-- Delete option (only for owner within 30 minutes) -->
        <?php if ($fetch_info['id'] == $row['userIDentry'] && time() - strtotime($row['createdOn']) < 1800): ?>
            <br><br>
            <div class="delete deleteentry">
                <p>Για 30 λεπτά μπορείτε να σβήσετε αυτή την ανάρτηση</p>
                <a href="bdeleteentry.php?eid=<?php echo $eid ?>"
                   class="confirmation"
                   onclick="return confirm('Are you sure?');">Delete Entry</a>
            </div>
        <?php endif; ?>

<?php
    endwhile;
    endif;
?>
