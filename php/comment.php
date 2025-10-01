<?php

    $eid = mysqli_real_escape_string($con, $_GET['eid']);

    $sql = "SELECT * FROM comments INNER JOIN usertable ON comments.userID = usertable.id INNER JOIN entry ON comments.entryID = entry.eid WHERE entryID = '$eid' ORDER BY comments.cid";

    $result       = mysqli_query($con, $sql);
    $queryResults = mysqli_num_rows($result);

    if ($queryResults > 0) {
    while ($row = mysqli_fetch_assoc($result)) {?>
            <div class='reviewinfo'>
            <img src='<?php echo isset($row['profilePic']) ? $row['profilePic'] : './profilepics/beard.png'; ?>'>
            <h5>
                <?php echo htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8'); ?>&nbsp;
                <?php echo htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8'); ?>&nbsp;
                on&nbsp;<?php echo htmlspecialchars($row['commentCreatedOn'], ENT_QUOTES, 'UTF-8'); ?>&nbsp;
                wrote
            </h5>
            </div>
            <div class='comment'>
            <?php
            if (preg_match('/\.(?:webm|mp3|wav)$/i', $row['comment'])) {?>
                        <audio controls src='<?php echo htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8') ?>'></audio>
                    <?php
                    } else {?>
                                <h4><?php echo nl2br(htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8'), false) ?></h4>
                    <?php
                        }
                                // Edit Button
                            if ($fetch_info['id'] == $row['userID'] && time() - strtotime($row['commentCreatedOn']) < 1800) {?>
              <p>Για 30 λεπτά μπορείτε να σβήσετε ή να επεξεργαστείτε αυτό το σχόλιο</p>
            <div class="buttons">
              <form class='delete' method='POST' action='article.php?eid=<?php echo $eid; ?>&reviewtype=<?php echo $reviewtype; ?>&title=<?php echo $title; ?>#comment'>
              <input type='hidden' name='cid' value='<?php echo $row['cid']; ?>'>
              <input type='hidden' name='comment' value='<?php echo $row['comment']; ?>'>
              <button type='submit' name='edit' value='edit' style='padding:5px;'><span>&nbsp;&nbsp;Edit&nbsp;&nbsp;</span></button>
              </form><br><br><br>
              <div class='delete'>
              <a href='deletecomment.php?cid=<?php echo $row['cid']; ?>&eid=<?php echo $eid; ?>&reviewtype=<?php echo $reviewtype; ?>&title=<?php echo $title; ?>' class='confirmation' onclick="return confirm('Are you sure?');">Delete Comment</a>
              </div><br>
            </div><?php } else {echo '';}?>
            <hr>
             </div>
             <?php }
             }?>
        </div>

   <?php
       if (isset($_POST['edit'])) {
           $cid     = $_POST['cid'];
       $comment = $_POST['comment']; ?>
         <div class='commentsin'>
    <form method="post" action="edit.php?eid=<?php echo urlencode($eid) ?>&reviewtype=<?php echo urlencode($reviewtype) ?>&title=<?php echo urlencode($title) ?>&cid=<?php echo urlencode($cid) ?>&comment=<?php echo urlencode($comment) ?>">
            <input type="hidden" name="cid" value="<?php echo htmlspecialchars($cid, ENT_QUOTES, 'UTF-8'); ?>">
            <textarea placeholder="Write a comment." name="comment" id="comment"><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <br><br>
            <button type="button" id="startDictation">🎤 Dictate</button>
            <br><br>
            <button type="submit" name="editcomment" value="editcomment" style="padding:5px;">
                <span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span>
            </button>
        </form>
       <?php } else {?>
        <div class='commentsin'>
            <form method='post'>
                <textarea placeholder='Write a comment.' type='text' name='comment' id='comment'></textarea>
                <button type='button' id='startDictation'>🎤 Dictate</button>

                <br> <br>
                <button type='submit' name='insert' value='insert' style='padding:5px;'><span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span></button>

            </form>
       <?php }
       ?>
<button id="recordBtn">🎙 Hold to Record</button>
<audio id="audioPreview" controls style="display:none;"></audio>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="eid" value="<?php echo $eid; ?>">
    <input type="hidden" name="reviewtype" value="<?php echo $reviewtype; ?>">
    <input type="hidden" name="title" value="<?php echo $title; ?>">
    <input type="file" name="audioComment" id="audioFile" hidden>
    <button type="submit" id="uploadBtn" name="uploadAudio" style="display:none;">
        Upload Audio Comment
    </button>
</form>
<br>
       <p class="text-danger">
     <?php if (isset($errors['r'])) {
             echo $errors['r'];
     }?>
    </p>