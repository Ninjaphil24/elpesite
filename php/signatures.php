<?php
    require_once 'connection.php';

    $eid    = (int) $_GET['eid'];
    $userid = (int) $fetch_info['id']; // user from session

    // Handle signing
    if (isset($_POST['sign'])) {
        $con->query("
        INSERT INTO entries_signatures (user, article)
        SELECT {$userid}, {$eid}
        FROM entry
        WHERE EXISTS (SELECT eid FROM entry WHERE eid = {$eid})
        AND NOT EXISTS (
            SELECT id FROM entries_signatures WHERE user = {$userid} AND article = {$eid}
        )
        LIMIT 1
    ");
    }

    // Fetch signatures
    $result = $con->query("
    SELECT
        entry.eid,
        COUNT(entries_signatures.id) AS total_signatures,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS signatories
    FROM entry
    LEFT JOIN entries_signatures ON entry.eid = entries_signatures.article
    LEFT JOIN usertable ON entries_signatures.user = usertable.id
    WHERE entry.eid = $eid
");

    $signatures  = $result->fetch_object();
    $signatories = $signatures->signatories ? explode('|', $signatures->signatories) : [];
?>

<div class="signaturesBox"
<?php if ($reviewtype != 'ΨΗΦΟΦΟΡΙΑ (16.3)') {echo 'style="display:none;"';}?>>

    <?php if ($signatures->total_signatures < 15): ?>
        <form method="post">
            <button type="submit" name="sign"
                onclick="return confirm('Θέλετε να υπογράψετε; Μετά δεν θα μπορείτε να ακυρώσετε την υπογραφή σας.');">
                ΥΠΟΓΡΑΦΗ
            </button>
        </form>
    <?php else: ?>
        <p>✅ Έχουν ήδη συλλεχθεί 15 υπογραφές.</p>
    <?php endif; ?>

    <p>Συνολικές υπογραφές:                                                                                                                                                                                                                                                                                                                            <?php echo $signatures->total_signatures; ?>/15</p>

    <ol>
        <?php foreach ($signatories as $user): ?>
            <li><?php echo htmlspecialchars($user); ?></li>
        <?php endforeach; ?>
    </ol>
</div>
