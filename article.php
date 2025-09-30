<?php

// The session start works in conjuction with the logged in issue.
session_start();
require "connection.php";
$email = "";
$name = "";
$errors = array();
//require_once "controllerUserData.php";
?>
<?php 
if(!empty($_COOKIE["email"])){
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
    
}
else{
$email = $_SESSION['email'];
$password = $_SESSION['password'];
}
if($email != false && $password != false){
  $sql = "SELECT * FROM usertable WHERE email = '$email'";
  $run_Sql = mysqli_query($con, $sql);
  if($run_Sql){
      $fetch_info = mysqli_fetch_assoc($run_Sql);
      $status = $fetch_info['status'];
      $code = $fetch_info['code'];
      if($status == "verified"){
          if($code != 0){
              header('Location: reset-code.php');
          }
      }else{
          header('Location: user-otp.php');
      }
  }
}else{
  header('Location: index.php');
}
    $eid= mysqli_real_escape_string($con, $_GET['eid']);
    $reviewtype= mysqli_real_escape_string($con, $_GET['reviewtype']);
    $title = mysqli_real_escape_string($con, $_GET['title']);
    

   if (isset($_POST['insert'])) {

    $comment= $con->real_escape_string(nl2br($_POST['comment'],false));


     $errors = array();

     if(empty($comment)) {
       $errors['r'] = "Review Required";
     }

     if (count($errors)==0) {

        $query = "INSERT INTO comments (userID, entryID, comment, commentCreatedOn) VALUES ('".$fetch_info['id']."','$eid','$comment',NOW())";


       $result = mysqli_query($con,$query);


     if ($result) {
       header("Location: article.php?eid=$eid&reviewtype=$reviewtype&title=$title#comment");
   die();

       // echo "<script>alert('done')</script>"
     }
     else{

       echo "<script>alert('failed')</script>";
     }
     }
   }
// Echoing the number of entries, in this case comments

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
    <img src="<?php echo isset($fetch_info['profilePic']) ? $fetch_info['profilePic']: './profilepics/beard.png';?>" alt=""> 
</div>
<div class="user">
    <h3>ΧΑΙΡΕ <?php echo $fetch_info['firstName']?></h3>
</div>
</section>



<header>
<h5>Hosted by:</h5>
    <a href="https://spoiledeggs.eu5.org/" target="_blank" class="logo"> <img src="img/egglogo.png" alt=""> </a>


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

      <div class="modal" id="contactModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact</h5>
                </div>
                <div class="modal-body">
                    <h3>For any problems please contact the administrator of this site on spoiledeggs.eu5@gmail.com</h3>
                  </div>
                  <div class="modal-footer">
                    <button data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>

    <div class="modal" id="logInModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log In Form</h5>
                </div>
                <div class="modal-body">
                    <input type="email" id="userLEmail" class="form-control" placeholder="Your Email">
                    <input type="password" id="userLPassword" class="form-control" placeholder="Password">
                </div>
                <div class="modal-footer">
              <ul><li><a href="register.php">Register</a></li></ul>
                    <button id="loginBtn">Log In</button>
                    <button data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     <br>
     <?php
   
     $eid= mysqli_real_escape_string($con, $_GET['eid']);
     $reviewtype= mysqli_real_escape_string($con, $_GET['reviewtype']);
     $title = mysqli_real_escape_string($con, $_GET['title']);
   
       $sql = "SELECT * FROM entry WHERE eid='$eid' AND title='$title'";

       $result = mysqli_query($con, $sql);
       $queryResults = mysqli_num_rows($result);
      
       if($queryResults > 0) {
         while ($row = mysqli_fetch_assoc($result)) {
          $timestamp = $row['createdOn'];
          $date = date("d-m-Y",strtotime($timestamp));
          if ($row['link'] != null){          
          echo "<div class='link'>
           <iframe src='".$row['link']."' width='100%' height='250'></iframe></div>
           <a href='".$row['link']."' class='myButton' target='_blank'>Visit Website<a>
           </div> <br> <br>";
          } else {
             echo "";
           }
           if ($row['biog'] != 'pdf/'){
          echo '
          <div class="pdf">       
            <button onclick="myFunction()">ΕΠΙΣΥΝΑΠΤΟΜΕΝΟ (ΑΝΟΙΓΜΑ/ΚΛΕΙΣΜΙΟ)</button>
            <div id="pdf" style="display: none;">
            <p><iframe src='.$row['biog'].' width="1000px" height="1000px"></iframe></p>
            </div>
          </div>';
          }else {
             echo "";
           }
           echo "<div class='box'>
           <h3>".$row['reviewtype']."</h3>
           <h3>".$row['title']."</h3>
           
           <p>Ημερομηνία ανάρτησης: ".$date."</p><br>
           </div>";
           if($fetch_info['id'] == $row['userIDentry']){?>
           <br> <br>
           
           <div class='delete deleteentry'>
              <a href='deleteentry.php?eid=<?php echo $eid?>' class='confirmation' onclick="return confirm('Are you sure?');">Delete Entry</a>
            </div>
   </div>   
   <?php }}} ?>
  </div>
  </div> <br> <br>


  <!-- Voting Start -->
<?php
$articlesQuery = $con->query("SELECT 
entry.eid, 
COUNT(entries_likes.id) AS likes,
GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS liked_by

FROM entry

LEFT JOIN entries_likes
ON entry.eid = entries_likes.article

LEFT JOIN usertable
ON entries_likes.user = usertable.id

AND entry.eid=$eid

");

while($row = $articlesQuery->fetch_object()) {
$row->liked_by = $row->liked_by ? explode('|', $row->liked_by) : [];
$articles[] = $row;
}
$articlesQuery2 = $con->query("SELECT 
entry.eid, 
COUNT(entries_dislikes.id) AS dislikes,
GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS disliked_by

FROM entry

LEFT JOIN entries_dislikes
ON entry.eid = entries_dislikes.article

LEFT JOIN usertable
ON entries_dislikes.user = usertable.id

AND entry.eid=$eid

");

while($row2 = $articlesQuery2->fetch_object()) {
$row2->disliked_by = $row2->disliked_by ? explode('|', $row2->disliked_by) : [];
$articles2[] = $row2;
}
    
?>
  <div class="voteButtons" <?php if($reviewtype != "ΨΗΦΟΦΟΡΙΑ")echo "style= display:none;}";?>>
  <?php foreach($articles as $article): ?> 
    <div class="yesVote">
      <a href="like.php?type=article&id=<?php echo $eid;?>&userid=<?php echo $fetch_info['id'];?>&reviewtype=<?php echo $reviewtype;?>&title=<?php echo $title;?>" 

      onclick="return confirm('Πρόκειται να ψηφίσετε ΝΑΙ.  Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">NAI</a> <br>
      <!-- <p><?php echo $article->likes;?> ψήφοι</p> -->
      <br>
      <button onclick="myFunction2()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
      <ol id="votedYes">
          <?php foreach($article->liked_by as $user): ?>
              <?php echo "<li>".$user."</li>"; ?>
              <?php endforeach; ?>
            </ol>
    </div>
    <?php endforeach; ?>
    <div class="space">
      <section class="innerSpace"></section>
    </div>
    <?php foreach($articles2 as $article): ?> 
    <div class="noVote">
      <a href="dislike.php?type=article&id=<?php echo $eid;?>&userid=<?php echo $fetch_info['id'];?>&reviewtype=<?php echo $reviewtype;?>&title=<?php echo $title;?>" onclick="return confirm('Πρόκειται να ψηφίσετε ΟΧΙ.  Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">OXI</a> <br>
      <!-- <p><?php echo $article->dislikes;?> ψήφοι</p> -->
      <br>
      <button onclick="myFunction3()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
      <ol id="votedNo">
          <?php foreach($article->disliked_by as $user): ?>
              <?php echo "<li>".$user."</li>"; ?>
          <?php endforeach; ?>
      </ol>
    </div>
  </div>
  <?php endforeach; ?>
  <!-- Voting End -->

<a name="comment"></a>
<div class="commentsout">
       <?php

       $eid= mysqli_real_escape_string($con, $_GET['eid']);
       
       $sql = "SELECT * FROM comments INNER JOIN usertable ON comments.userID = usertable.id INNER JOIN entry ON comments.entryID = entry.eid WHERE entryID = '$eid' ORDER BY comments.cid";
      
       $result = mysqli_query($con, $sql);
       $queryResults = mysqli_num_rows($result);

         if($queryResults > 0) {
           while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class='reviewinfo'>
            <img src='<?php echo isset($row['profilePic']) ? $row['profilePic']: './profilepics/beard.png';?>'><?php echo "
             <h5>".$row['firstName']."&nbsp;".$row['lastName']."&nbsp;on&nbsp;".$row['commentCreatedOn']."&nbsp;wrote</h5></div>
            <div class='comment'><h4>".$row['comment']."</h4>";
            //Edit Button
            if($fetch_info['id'] == $row['userID']){?>
            <div class="buttons">
              <form class='delete' method='POST' action='article.php?eid=<?php echo $eid?>&reviewtype=<?php echo $reviewtype?>&title=<?php echo $title?>#comment'>
              <input type='hidden' name='cid' value='<?php echo $row['cid']?>'>
              <input type='hidden' name='comment' value='<?php echo $row['comment']?>'>
              <button type='submit' name='edit' value='edit' style='padding:5px;'><span>&nbsp;&nbsp;Edit&nbsp;&nbsp;</span></button>
              </form><br><br><br>
              <div class='delete'>
              <a href='deletecomment.php?cid=<?php echo $row['cid']?>&eid=<?php echo $eid?>&reviewtype=<?php echo $reviewtype?>&title=<?php echo $title?>' class='confirmation' onclick="return confirm('Are you sure?');">Delete Comment</a>
              </div><br>
            </div><?php ;
              }
              else {
                echo "";
              }
            
            echo "<hr>
             </div><br><br>";}}?>
        </div>

   <?php
  if(isset($_POST['edit'])){
    $cid = $_POST['cid'];
    $comment = $_POST['comment'];
    echo  "<div class='commentsin'>
    <form method='post' action='edit.php?eid=$eid&reviewtype=$reviewtype&title=$title&cid=$cid&comment=$comment'>
    <input type='hidden' name='cid' value='".$cid."'>
          <textarea placeholder='Write a comment.' type='text' name='comment' id='comment'>".$comment."</textarea><br> <br>
        <button type='submit' name='editcomment' value='editcomment' style='padding:5px;'><span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span></button></div>
    </form>";
}else echo "


   <div class='commentsin'>
    <form method='post'>
          <textarea placeholder='Write a comment.' type='text' name='comment' id='comment'></textarea><br> <br>
        <button type='submit' name='insert' value='insert' style='padding:5px;'><span>&nbsp;&nbsp;Insert&nbsp;&nbsp;</span></button></div>
    </form>";?>
<br>
       <p class="text-danger"> <?php if(isset($errors['r'])) echo $errors['r']; ?> </p>
       <br><br><br>
       </div>     

 
  <!-- Modal Start -->
  <script src="js/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>  
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

      </body>
    </html>
