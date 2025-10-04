<?php
    require "php/homevalidation.php";
    require "php/homenewentry.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ΕΛΠΕ</title>
    <!-- Modal Start -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Modal End -->

    <!-- navbar toggle start         -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <!-- navbar toggle end -->
    <link rel="shortcut icon" type="image/png" href="img/callas.jpg">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="watch.css">
  </head>
<body>
<section>
  <div class="crop">
  <a href="userprofile.php">
    <img src="<?php echo isset($fetch_info['profilePic']) ? $fetch_info['profilePic'] : './profilepics/beard.png'; ?>" alt="">
</div>
<div class="user">
    <h3>ΧΑΙΡΕ                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $fetch_info['firstName'] ?></h3>
  </a>
  </div>
</section>

    <header>

      <div class="menu-toggle"></div>
      <nav>
        <ul>
          <li><a href="userprofile.php">ΠΡΟΦΙΛ</a></li>
          <!-- <li><a href="entry.php">Review</a></li> -->
          <li><a href="#modal" data-toggle="modal" data-target="#aboutModal">ΟΔΗΓΙΕΣ</a></li>
          <li><a href="#modal" data-toggle="modal" data-target="#entry">ΕΙΣΑΓΩΓΗ ΑΝΑΡΤΗΣΗΣ</a></li>
          <li> <a href="data.html" target="_blank">ΙΔΙΩΤΙΚΟΤΗΤΑ</a></li>
          <li><a href="logout-user.php">ΑΠΟΣΥΝΔΕΣΗ</a></li>
        </ul>
      </nav>
      <div class="clearfix"></div>
    </header>
    <div class="modal" id="aboutModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Οδηγίες Χρήσης</h5>
                </div>
                <div class="modal-body">
                    <h2 style="text-align:center;">Καλωσήρθατε στην Πλατφόρμα Μελών του ΕΛΠΕ</h2> <br> <br>
                    <h4>Κάντε κλικ σε ένα από τα παρακάτω θέματα και σχολιάστε. <br> <br>
                    Για να εισάγετε μία ανάρτηση κάντε κλικ στο κουμπί "ΕΙΣΑΓΩΓΗ ΑΝΑΡΤΗΣΗΣ".  Αφού ολοκληρώσετε, η ανάρτηση σας θα εμφανίζεται πρώτη στον πίνακα "ΑΝΑΡΤΗΣΕΙΣ".  Κάντε κλικ και σχολιάστε.</h4>
                  </div>
                  <div class="modal-footer">
                    <button data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
    <div class="modal" id="entry">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Instructions</h5>
                </div>
                <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                <label style="margin:10px;">ΘΕΜΑ ΑΝΑΡΤΗΣΗΣ</label>
                <select class="form-control" name="reviewtype">
                  <option value="ΓΕΝΙΚΗ_ΣΥΖΗΤΗΣΗ">ΓΕΝΙΚΗ ΣΥΖΗΤΗΣΗ</option>
                    <?php if ($fetch_info['lastName'] == 'Μοδινός') {
                            echo '<option value="ΨΗΦΟΦΟΡΙΑ ΠΡΟΕΔΡΟΥ">ΨΗΦΟΦΟΡΙΑ ΠΡΟΕΔΡΟΥ</option>';
                    }?>
                    <option value="ΨΗΦΟΦΟΡΙΑ (16.3)">ΨΗΦΟΦΟΡΙΑ (16.3)</option>
                    <option value="ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ">ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ</option>
                    <option value="ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ">ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ</option>
                  </select> <br>

                <div class="form-group">

                  <label style="padding:2px; color:red;">&nbsp;&nbsp;ΤΙΤΛΟΣ ΑΝΑΡΤΗΣΗΣ (ΥΠΟΧΡΕΩΤΙΚΟ)</label>
                  <input type="text" name="title" placeholder="ΤΙΤΛΟΣ" class="form-control" style="text-transform:capitalize;">
                  <p class="text-danger"><?php if (isset($errors['t'])) {echo $errors['t'];}?> </p>
                  <label>ΥΠΑΡΧΕΙ ΣΧΕΤΙΚΟ ΛΙΝΚ ΠΟΥ ΘΕΛΕΤΕ ΝΑ ΕΠΙΣΥΝΑΨΕΤΕ;</label>
                  <input type="text" name="link" placeholder="Link" class="form-control"> <br>
                  <input type="hidden" name="biog" value="1000000">
                  <label>ΥΠΑΡΧΕΙ ΣΧΕΤΙΚΟ ΑΡΧΕΙΟ ΠΟΥ ΘΕΛΕΤΕ ΝΑ ΕΠΙΣΥΝΑΨΕΤΕ;</label> <br>
                  <input type="file" name="biog"> <br> <br>

                  <!-- <label>ΥΠΑΡΧΕΙ ΣΧΕΤΙΚΟ ΒΙΝΤΕΟ ΠΟΥ ΘΕΛΕΤΕ ΝΑ ΕΠΙΣΥΝΑΨΕΤΕ;</label>
                  <input type="text" name="video" placeholder="Video" class="form-control"> <br> <br> -->

                  <button type="submit" name="insert" value="insert" style="padding:5px;">&nbsp;&nbsp;Insert&nbsp;&nbsp;</button>
                  <!-- <input type="submit" name="insert" value="Insert" class="btn btn-success" style="margin:10px;"> -->

                </div>
              </form>
                  </div>
                  <div class="modal-footer">
                    <button data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>

  <div class="search-user">

      <select class="category-select" name="search">
          <option value="ALL">ΌΛΕΣ ΟΙ ΚΑΤΗΓΟΡΙΕΣ</option>
          <option value="ΓΕΝΙΚΗ_ΣΥΖΗΤΗΣΗ">ΓΕΝΙΚΗ ΣΥΖΗΤΗΣΗ</option>
          <option value="ΨΗΦΟΦΟΡΙΑ">ΨΗΦΟΦΟΡΙΑ</option>
          <option value="ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ">ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ</option>
          <option value="ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ">ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ</option>
      </select>
        <!-- <input type="text" name="search" placeholder="Search"> -->
        <!-- <button type="submit" name="submit-search">&#8594;  ΥΠΟΒΟΛΗ ΚΑΤΗΓΟΡΙΑΣ  &#8593;</button> -->

  </div>
<!-- Create a "by category" button that will connect with the search script. -->
  <div class="container">
  <div class="myButton">
  <?php if ($fetch_info['usertype'] == 'admin') {
          echo '
  <a href="home.php" class="active" >ΧΩΡΟΣ ΜΕΛΩΝ</a>
  <a href="boardmembers.php" >ΧΩΡΟΣ ΔΣ</a>';
  }?>
  </div>

  <div id='img_div'>
<h3>ΑΝΑΡΤΗΣΕΙΣ ΜΕΛΩΝ</h3>
<ul>
<?php
    include 'php/homepagination.php';
?>
</ul>
<div class="distance" style="height: 100px;"></div>
</body>
<!-- Modal Start -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>

<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

<!-- Your custom script -->
<script type="text/javascript">
$(document).ready(function() {
  // nav toggle
  $(".menu-toggle").click(function(){
    $(this).toggleClass("active");
    $("nav").toggleClass("active");
  });

  // ajax search
  $(".search-user select").on("change", function() {
    let search = $(this).val();
    $.ajax({
      url: "search.php",
      type: "POST",
      data: { search: search },
      success: function(data) {
        $("#img_div ul").html(data);
        $(".search-user select").val(search);
      }
    });
  });
});


</script>
</html>