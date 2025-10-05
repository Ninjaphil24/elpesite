<?php
    // Likes
    $articles      = [];
    $articlesQuery = $con->query("
    SELECT
        entry.eid,
        COUNT(entries_likes.id) AS likes,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS liked_by
    FROM entry
    LEFT JOIN entries_likes ON entry.eid = entries_likes.article
    LEFT JOIN usertable ON entries_likes.user = usertable.id
    WHERE entry.eid = $eid
");

    while ($row = $articlesQuery->fetch_object()) {
        $row->liked_by = $row->liked_by ? explode('|', $row->liked_by) : [];
        $articles[]    = $row;
    }

    // Dislikes
    $articles2      = [];
    $articlesQuery2 = $con->query("
    SELECT
        entry.eid,
        COUNT(entries_dislikes.id) AS dislikes,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS disliked_by
    FROM entry
    LEFT JOIN entries_dislikes ON entry.eid = entries_dislikes.article
    LEFT JOIN usertable ON entries_dislikes.user = usertable.id
    WHERE entry.eid = $eid
");

    while ($row2 = $articlesQuery2->fetch_object()) {
        $row2->disliked_by = $row2->disliked_by ? explode('|', $row2->disliked_by) : [];
        $articles2[]       = $row2;
    }

    // Check if current user has already voted
    $userid = $fetch_info['id'];

    $checkYes = $con->query("SELECT 1 FROM entries_likes WHERE user = $userid AND article = $eid LIMIT 1");
    $checkNo  = $con->query("SELECT 1 FROM entries_dislikes WHERE user = $userid AND article = $eid LIMIT 1");

    $alreadyVoted = ($checkYes->num_rows > 0 || $checkNo->num_rows > 0);
?>

<?php if (($reviewtype == 'ΨΗΦΟΦΟΡΙΑ (16.3)' && $signatures->total_signatures >= 15) || $reviewtype == 'ΨΗΦΟΦΟΡΙΑ ΠΡΟΕΔΡΟΥ' || $reviewtype == 'ΨΗΦΟΦΟΡΙΑ') {?>
<div class="voteButtons">
    <?php if (! $alreadyVoted): ?>
        <!-- Voting links -->
        <div class="yesVote">
            <a href="like.php?type=article&id=<?php echo $eid ?>&userid=<?php echo $fetch_info['id'] ?>&reviewtype=<?php echo urlencode($reviewtype) ?>&title=<?php echo urlencode($title) ?>"
               onclick="return confirm('Πρόκειται να ψηφίσετε ΝΑΙ. Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">
                ΝΑΙ
            </a>
        </div>

        <div class="noVote">
            <a href="dislike.php?type=article&id=<?php echo $eid ?>&userid=<?php echo $fetch_info['id'] ?>&reviewtype=<?php echo urlencode($reviewtype) ?>&title=<?php echo urlencode($title) ?>"
               onclick="return confirm('Πρόκειται να ψηφίσετε ΟΧΙ. Αν πατήσετε ΟΚ η ψήφος θα καταχωρηθεί και δεν θα μπορείτε να αλλάξετε την ψήφο σας.');">
                ΟΧΙ
            </a>
        </div>
    <?php else: ?>
        <p>✅ Έχετε ήδη ψηφίσει. Δεν μπορείτε να αλλάξετε την ψήφο σας.</p>
    <?php endif; ?>
    </div>
<!--
    <div class="space">
        <section class="innerSpace"></section>
    </div> -->
<div class="voteNames">
    <!-- Yes Votes -->
    <?php foreach ($articles as $article): ?>
        <div class="yesVote">
            <button onclick="myFunction2()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
            <ol id="votedYes">
                <?php foreach ($article->liked_by as $user): ?>
                    <li><?php echo htmlspecialchars($user) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endforeach; ?>

    <!-- No Votes -->
    <?php foreach ($articles2 as $article): ?>
        <div class="noVote">
            <button onclick="myFunction3()">ΕΜΦΑΝΙΣΗ ΨΗΦΩΝ</button>
            <ol id="votedNo">
                <?php foreach ($article->disliked_by as $user): ?>
                    <li><?php echo htmlspecialchars($user) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endforeach; ?>

</div>
<?php }?>