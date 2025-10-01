<?php
    // Likes
    $articles      = [];
    $articlesQuery = $con->query("
    SELECT
        bentry.eid,
        COUNT(bentries_likes.id) AS likes,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS liked_by
    FROM bentry
    LEFT JOIN bentries_likes ON bentry.eid = bentries_likes.article
    LEFT JOIN usertable ON bentries_likes.user = usertable.id
    AND bentry.eid = $eid
");

    while ($row = $articlesQuery->fetch_object()) {
        $row->liked_by = $row->liked_by ? explode('|', $row->liked_by) : [];
        $articles[]    = $row;
    }

    // Dislikes
    $articles2      = [];
    $articlesQuery2 = $con->query("
    SELECT
        bentry.eid,
        COUNT(bentries_dislikes.id) AS dislikes,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS disliked_by
    FROM bentry
    LEFT JOIN bentries_dislikes ON bentry.eid = bentries_dislikes.article
    LEFT JOIN usertable ON bentries_dislikes.user = usertable.id
    AND bentry.eid = $eid
");

    while ($row2 = $articlesQuery2->fetch_object()) {
        $row2->disliked_by = $row2->disliked_by ? explode('|', $row2->disliked_by) : [];
        $articles2[]       = $row2;
    }
?>

<div class="voteButtons"
    <!-- Yes Votes -->
    <?php foreach ($articles as $article): ?>
        <div class="yesVote">
            <a href="blike.php?type=article&id=<?php echo $eid ?>&userid=<?php echo $fetch_info['id'] ?>&reviewtype=<?php echo urlencode($reviewtype) ?>&title=<?php echo urlencode($title) ?>"
               onclick="return confirm('Πρόκειται να ψηφίσετε ΝΑΙ. Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">
                ΝΑΙ
            </a>
            <br><br>
            <button onclick="myFunction2()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
            <ol id="votedYes">
                <?php foreach ($article->liked_by as $user): ?>
                    <li><?php echo htmlspecialchars($user) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endforeach; ?>

    <div class="space">
        <section class="innerSpace"></section>
    </div>

    <!-- No Votes -->
    <?php foreach ($articles2 as $article): ?>
        <div class="noVote">
            <a href="bdislike.php?type=article&id=<?php echo $eid ?>&userid=<?php echo $fetch_info['id'] ?>&reviewtype=<?php echo urlencode($reviewtype) ?>&title=<?php echo urlencode($title) ?>"
               onclick="return confirm('Πρόκειται να ψηφίσετε ΟΧΙ. Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">
                ΟΧΙ
            </a>
            <br><br>
            <button onclick="myFunction3()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
            <ol id="votedNo">
                <?php foreach ($article->disliked_by as $user): ?>
                    <li><?php echo htmlspecialchars($user) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endforeach; ?>

</div>
