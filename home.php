<?php 
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

if (isset($_POST['insert'])) {
    $userIDentry = $fetch_info['id'];
    $reviewtype= $con->real_escape_string($_POST['reviewtype']);
    $title = $con->real_escape_string($_POST['title']);
    $link = $con->real_escape_string($_POST['link']);
    // $video = $con->real_escape_string($_POST['video']);
    $biog = $_FILES['biog']['name'];
    $targetb = "pdf/".basename($biog);

    $errors = array();  
  
    if(empty($title)) {
      $errors['t'] = "Title Required";
    }
  
    if (count($errors)==0) {
      $query = "INSERT INTO entry(userIDentry,reviewtype,title,link,biog,createdOn)
        VALUES ('$userIDentry','$reviewtype','$title','$link','pdf/$biog',NOW())";
      move_uploaded_file($_FILES['biog']['tmp_name'], $targetb);
      $result = mysqli_query($con,$query);
  
    if ($result) {
      header("Location: home.php");
  die();
    }
    else {
  
      $query = "SELECT * FROM entry WHERE reviewtype = '$reviewtype' AND title = '$title'";
  
  
      $result = mysqli_query($con, $query);
  
      $row = mysqli_fetch_assoc($result);
  
      header("Location: article.php?eid=".$row['eid']."&reviewtype=".$row['reviewtype']."&title=".$row['title']."");
     die();  }
    }
  
  }
  
  
   
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
    <img src="<?php echo isset($fetch_info['profilePic']) ? $fetch_info['profilePic']: './profilepics/beard.png';?>" alt=""> 
</div>
<div class="user">
    <h3>ΧΑΙΡΕ <?php echo $fetch_info['firstName']?></h3>
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
                    <option value="ΑΓΓΕΛΙΑ_ΕΡΓΑΣΙΑΣ">ΑΓΓΕΛΙΑ ΕΡΓΑΣΙΑΣ</option>
                    <option value="ΑΓΓΕΛΙΑ_ΑΚΡΟΑΣΕΩΝ/ΔΙΑΓΩΝΙΣΜΩΝ">ΑΓΓΕΛΙΑ ΑΚΡΟΑΣΕΩΝ/ΔΙΑΓΩΝΙΣΜΩΝ</option>
                    <?php if($fetch_info['usertype']=='admin'){
                    echo '<option value="ΨΗΦΟΦΟΡΙΑ">ΨΗΦΟΦΟΡΙΑ</option>';}?>
                    <option value="ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ">ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ</option>
                  </select> <br>
                  
                <div class="form-group">
                  
                  <label style="padding:2px; color:red;">&nbsp;&nbsp;ΤΙΤΛΟΣ ΑΝΑΡΤΗΣΗΣ (ΥΠΟΧΡΕΩΤΙΚΟ)</label>
                  <input type="text" name="title" placeholder="ΤΙΤΛΟΣ" class="form-control" style="text-transform:capitalize;">
                  <p class="text-danger"> <?php if(isset($errors['t'])) echo $errors['t']; ?> </p>
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
    <form action="search.php" method="POST"> 
      <select class="form-control" name="search">
          <option value="ΓΕΝΙΚΗ_ΣΥΖΗΤΗΣΗ">ΓΕΝΙΚΗ ΣΥΖΗΤΗΣΗ</option>
          <option value="ΑΓΓΕΛΙΑ_ΕΡΓΑΣΙΑΣ">ΑΓΓΕΛΙΑ ΕΡΓΑΣΙΑΣ</option>
          <option value="ΑΓΓΕΛΙΑ_ΑΚΡΟΑΣΕΩΝ/ΔΙΑΓΩΝΙΣΜΩΝ">ΑΓΓΕΛΙΑ ΑΚΡΟΑΣΕΩΝ/ΔΙΑΓΩΝΙΣΜΩΝ</option>
          <option value="ΨΗΦΟΦΟΡΙΑ">ΨΗΦΟΦΟΡΙΑ</option>
          <option value="ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ">ΚΑΤΑΓΓΕΛΙΑ/ΠΑΡΑΠΟΝΑ</option>
      </select>
        <!-- <input type="text" name="search" placeholder="Search"> -->
        <button type="submit" name="submit-search">&#8594;  ΥΠΟΒΟΛΗ ΚΑΤΗΓΟΡΙΑΣ  &#8593;</button>
    </form>
  </div>
<!-- Create a "by category" button that will connect with the search script. -->
  <div class="container">
  <div class="myButton">
  <?php if($fetch_info['usertype']=='admin'){
    echo '
  <a href="home.php" class="active" >ΧΩΡΟΣ ΜΕΛΩΝ</a>
  <a href="boardmembers.php" >ΧΩΡΟΣ ΔΣ</a>';}?>
  </div>

  <div id='img_div'>
<h3>ΑΝΑΡΤΗΣΕΙΣ ΜΕΛΩΝ</h3>
<ul>

  <?php
    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
      $page_no = $_GET['page_no'];
      } else {
        $page_no = 1;
            }
    
      $total_records_per_page = 10;
      $offset = ($page_no-1) * $total_records_per_page;
      $previous_page = $page_no - 1;
      $next_page = $page_no + 1;
      $adjacents = "2"; 
    
      $result_count = mysqli_query($con,"SELECT COUNT(*) As total_records FROM `entry`");
      $total_records = mysqli_fetch_array($result_count);
      $total_records = $total_records['total_records'];
      $total_no_of_pages = ceil($total_records / $total_records_per_page);
      $second_last = $total_no_of_pages - 1; // total page minus 1
      
    $sql = "SELECT * FROM entry ORDER BY eid DESC LIMIT $offset, $total_records_per_page";
    $result = mysqli_query($con, $sql);
    $queryResult = mysqli_num_rows($result);

  if($queryResult > 0) {
      while ($row = mysqli_fetch_assoc($result)){
        // https://www.delftstack.com/howto/php/how-to-convert-a-date-to-the-timestamp-in-php/
        $timestamp = $row['createdOn'];        
        $date = date("d-m-Y",strtotime($timestamp));
        
        // $dateunix = new DateTime($timestamp);
        // $timeunix = $dateunix->getTimestamp();
        // $fiveSeconds = 5;
        // $t3600secs = 3600;
        // $sum = $timeunix + $fiveSeconds;
        // $unixnow = time()+3600;
        // https://www.javatpoint.com/php-adding-two-numbers
        // if($unixnow<$sum)echo "<span>";

        echo "<a href='article.php?eid=".$row['eid']."&reviewtype=".$row['reviewtype']."&title=".$row['title']."'>

        <li><p>".$row['reviewtype']."<br> 
        <h5>".$row['title']."</h5><br>
        ΗΜΕΡΟΜΗΝΙΑ ΑΝΑΡΤΗΣΗΣ:&nbsp;".$date."</p><hr>";
        
        // if($unixnow<$sum)echo "</span>";
        // echo "<hr>";
        // echo "Timeunix: ".$timeunix;
        // echo "<br>";
        // echo "Sum: ".$sum;
        // echo "<br>";
        // echo "Current time: ".$unixnow;
        
        if ($row['link'] != null){
        echo  "<iframe src='".$row['link']."' style='width: 100%; height: 300px;'></iframe></li></a>
        <br>";
        }
        else {
        echo "</li></a>
        <br>";
    }


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
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>

<ul class="pagination">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
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
$(document).ready(function(){
  $('.menu-toggle').click(function(){
    $('.menu-toggle').toggleClass('active');
    $('nav').toggleClass('active');
  });
});
</script>
</html>