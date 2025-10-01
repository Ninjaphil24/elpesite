<?php

    require_once __DIR__ . '/php/sessionbasics.php';
    require_once __DIR__ . '/php/commentaudio.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ΕΛΠΕ</title>

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Modal Start -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Modal End -->


    <link rel="shortcut icon" type="image/png" href="img/callas.jpg">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="article.css">
  </head>
  <body>

  <section>
<div class="crop">
    <img src="<?php echo isset($fetch_info['profilePic']) ? $fetch_info['profilePic'] : './profilepics/beard.png'; ?>" alt="">
</div>
<div class="user">
    <h3>ΧΑΙΡΕ
      <?php echo $fetch_info['firstName'] ?></h3>
</div>
</section>
<header>
      <div class="menu-toggle"></div>
      <nav>
        <ul>
          <li><a href="home.php">ΑΡΧΙΚΗ</a></li>
          <li><a href="#modal" data-toggle="modal" data-target="#InstructionsModal">ΟΔΗΓΙΕΣ</a></li>
          <li><a href="logout-user.php">ΑΠΟΣΥΝΔΕΣΗ</a></li>
        </ul>
      </nav>
      <div class="clearfix"></div>
    </header>


    <div class="modal" id="InstructionsModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ΟΔΗΓΙΕΣ</h5>
                </div>
                <div class="modal-body">
                    <h4>Σχολιάστε πάνω στην ανάρτηση.</h4>
                  </div>
                  <div class="modal-footer">
                    <button data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>


     <?php include 'php/entry.php'; ?>
  <br> <br>


  <!-- Voting Start -->
<?php include 'php/voting.php'; ?>
  <!-- Voting End -->

<a name="comment"></a>
<div class="commentsout">
       <?php

           $eid = mysqli_real_escape_string($con, $_GET['eid']);

           $sql = "SELECT * FROM comments INNER JOIN usertable ON comments.userID = usertable.id INNER JOIN entry ON comments.entryID = entry.eid WHERE entryID = '$eid' ORDER BY comments.cid";

           $result       = mysqli_query($con, $sql);
           $queryResults = mysqli_num_rows($result);

           if ($queryResults > 0) {
           while ($row = mysqli_fetch_assoc($result)) {?>
            <div class='reviewinfo'>
            <img src='<?php echo isset($row['profilePic']) ? $row['profilePic'] : './profilepics/beard.png'; ?>'><?php echo "
            <h5>{$row['firstName']}&nbsp;{$row['lastName']}&nbsp;on&nbsp;{$row['commentCreatedOn']}&nbsp;wrote</h5>
            </div>
            <div class='comment'>
            ";

            if (preg_match('/\.(?:webm|mp3|wav)$/i', $row['comment'])) {
                echo "<audio controls src='" . htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8') . "'></audio>";
            } else {
                echo "<h4>" . nl2br(htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8'), false) . "</h4>";
            }

            //Edit Button
        if ($fetch_info['id'] == $row['userID'] && time() - strtotime($row['commentCreatedOn']) < 1800) { ?>
              <p>Για 30 λεπτά μπορείτε να σβήσετε ή να επεξεργαστείτε αυτό το σχόλιο</p>
            <div class="buttons">
              <form class='delete' method='POST' action='article.php?eid=<?php echo $eid ?>&reviewtype=<?php echo $reviewtype ?>&title=<?php echo $title ?>#comment'>
              <input type='hidden' name='cid' value='<?php echo $row['cid'] ?>'>
              <input type='hidden' name='comment' value='<?php echo $row['comment'] ?>'>
              <button type='submit' name='edit' value='edit' style='padding:5px;'><span>&nbsp;&nbsp;Edit&nbsp;&nbsp;</span></button>
              </form><br><br><br>
              <div class='delete'>
              <a href='deletecomment.php?cid=<?php echo $row['cid'] ?>&eid=<?php echo $eid ?>&reviewtype=<?php echo $reviewtype ?>&title=<?php echo $title ?>' class='confirmation' onclick="return confirm('Are you sure?');">Delete Comment</a>
              </div><br>
            </div><?php ;} else {
                                  echo "";
                              }

                              echo "<hr>
             </div><br><br>";}
                  }?>
        </div>

   <?php
       if (isset($_POST['edit'])) {
           $cid     = $_POST['cid'];
           $comment = $_POST['comment'];
           echo "<div class='commentsin'>
    <form method='post' action='edit.php?eid=$eid&reviewtype=$reviewtype&title=$title&cid=$cid&comment=$comment'>
    <input type='hidden' name='cid' value='" . $cid . "'>
          <textarea placeholder='Write a comment.' type='text' name='comment' id='comment'>" . $comment . "</textarea><br> <br>
          <button type='button' id='startDictation'>🎤 Dictate</button>
          <br> <br>
        <button type='submit' name='editcomment' value='editcomment' style='padding:5px;'><span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span></button></div>
    </form>";
       } else {
           echo "<div class='commentsin'>
    <form method='post'>
          <textarea placeholder='Write a comment.' type='text' name='comment' id='comment'></textarea>
          <button type='button' id='startDictation'>🎤 Dictate</button>

          <br> <br>
        <button type='submit' name='insert' value='insert' style='padding:5px;'><span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span></button>

    </form>";
       }
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
     <?php if (isset($errors['r'])) {echo $errors['r'];}?>
    </p>
       <br><br><br>
</div>
<div class="distance" style="height: 100px;"></div>


  <!-- Modal Start -->
  <!-- jQuery (latest stable) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (compatible with Bootstrap 4.3.1) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <!-- Modal End -->

  <script type="text/javascript">

// Navbar Start
      $(document).ready(function(){
        $('.menu-toggle').click(function(){
          $('.menu-toggle').toggleClass('active')
          $('nav').toggleClass('active')
        });
      });
// Navbar End
function myFunction() {
  var x = document.getElementById("pdf");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
function myFunction2() {
  var x = document.getElementById("votedYes");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

function myFunction3() {
  var x = document.getElementById("votedNo");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
        </script>

<script src="js/dictation.js"></script>
<script src="js/record.js"></script>


</body>
</html>
