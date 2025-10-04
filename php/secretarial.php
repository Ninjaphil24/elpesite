<?php
    if ($reviewtype == 'ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ') {

        $eid    = (int) $_GET['eid'];
        $userid = (int) $fetch_info['id'];

                                                          // Check if user is admin
        $isAdmin = ($fetch_info['usertype'] === 'admin'); // adjust to your actual column name

        // Handle admin signing
        if ($isAdmin && isset($_POST['secretary_sign'])) {
            $con->query("
            INSERT INTO entries_signatures (user, article)
            SELECT {$userid}, {$eid}
            WHERE NOT EXISTS (
                SELECT id FROM entries_signatures
                WHERE user = {$userid} AND article = {$eid}
            )
            LIMIT 1
        ");
        }

        // Fetch signature data
        $result = $con->query("
        SELECT
        COUNT(*) AS total_signatures,
        GROUP_CONCAT(usertable.lastName SEPARATOR '|') AS signatories
        FROM entries_signatures
        LEFT JOIN usertable ON entries_signatures.user = usertable.id
        WHERE entries_signatures.article = {$eid}
        ");
        if (! $result) {
            die("SQL Error: " . $con->error);
        }

        $data    = $result->fetch_object();
        $total   = $data->total_signatures ?? 0;
        $signers = $data->signatories ? explode('|', $data->signatories) : [];
    ?>
<div class="secretarialBox">
    <h4>ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ</h4>

    <?php if ($isAdmin && $total < 7): ?>
    <form method="post">
        <button type="submit" name="secretary_sign"
            onclick="return confirm('Επιβεβαιώστε την υπογραφή σας.');">
            Υπογραφή Γραμματείας
        </button>
    </form>
<?php elseif ($total == 7): ?>
    <p>✅ Έχουν συλλεχθεί<?php echo $total; ?>/7 υπογραφές.</p>
    <form method="post" action="/createpdf.php" target="_blank">
        <input type="hidden" name="eid" value="<?php echo $eid; ?>">
        <button type="submit" name="generate_pdf">Δημιουργία PDF</button>
    </form>
<?php else: ?>
    <p>Μόνο τα μέλη του Διοικητικού Συμβουλίου μπορούν να υπογράψουν.</p>
<?php endif; ?>

    <p>Υπογραφές:
        <?php echo $total; ?>/7</p>
    <ol>
        <?php foreach ($signers as $s): ?>
            <li><?php echo htmlspecialchars($s); ?></li>
        <?php endforeach; ?>
    </ol>
</div>
<?php }?>
