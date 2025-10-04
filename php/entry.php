<?php
    $eid        = mysqli_real_escape_string($con, $_GET['eid']);
    $reviewtype = mysqli_real_escape_string($con, $_GET['reviewtype']);
    $title      = mysqli_real_escape_string($con, $_GET['title']);

    $sql    = "SELECT * FROM entry WHERE eid='$eid' AND title='$title'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
            $timestamp = $row['createdOn'];
            $date      = date("d-m-Y", strtotime($timestamp));
        if (! empty($row['link'])): ?>
																        <div class="link-preview">
				    <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">
				        <img
				            src="https://www.google.com/s2/favicons?sz=64&domain=<?php echo htmlspecialchars(parse_url($row['link'], PHP_URL_HOST)); ?>"
				            alt="favicon"
				        >
				        <div class="link-preview-content">
				            <strong><?php echo htmlspecialchars(parse_url($row['link'], PHP_URL_HOST)); ?></strong><br>
				            <em>Click to open external site</em>
				        </div>
				    </a>
				</div>

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
            <p>Ημερομηνία ανάρτησης:
                <?php echo $date ?></p><br>
        </div>

        <!-- Delete option (only for owner within 30 minutes) -->
        <?php if ($fetch_info['id'] == $row['userIDentry'] && time() - strtotime($row['createdOn']) < 1800): ?>
            <br><br>
            <div class="delete deleteentry">
                <p>Για
                    <?php echo round(30 - (time() - strtotime($row['createdOn'])) / 60); ?>
 λεπτά μπορείτε να σβήσετε αυτή την ανάρτηση</p>
                <a href="deleteentry.php?eid=<?php echo $eid ?>"
                   class="confirmation"
                   onclick="return confirm('Are you sure?');">Delete Entry</a>
            </div>
        <?php endif; ?>

<?php
    endwhile;
    endif;
?>
