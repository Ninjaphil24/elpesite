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
    <?php include 'php/comment.php'; ?>
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
