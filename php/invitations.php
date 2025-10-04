<?php
    if ($reviewtype == 'ΠΡΟΣΚΛΗΣΕΙΣ') {

        $eid    = (int) $_GET['eid'];
        $userid = (int) $fetch_info['id'];

        // When user clicks "Sign up"
        if (isset($_POST['opera_signup'])) {
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

        // Fetch participants
        $result = $con->query("
        SELECT usertable.firstName, usertable.lastName
        FROM entries_signatures
        JOIN usertable ON entries_signatures.user = usertable.id
        WHERE entries_signatures.article = {$eid}
        ORDER BY entries_signatures.id
    ");
        if (! $result) {
            die('SQL Error: ' . $con->error);
        }

        $participants = [];
        while ($row = $result->fetch_assoc()) {
            $participants[] = "{$row['firstName']} {$row['lastName']}";
        }
        $total = count($participants);
    ?>
<div class="operaBox">
    <h4>🎭 ΠΡΟΣΚΛΗΣΕΙΣ ΟΠΕΡΑΣ</h4>

    <form method="post">
        <button type="submit" name="opera_signup"
            onclick="return confirm('Θέλετε να δηλώσετε συμμετοχή;');">
            Δήλωση Συμμετοχής
        </button>
    </form>

    <p>Συνολικές συμμετοχές:                                                                                                                                                                                             <?php echo $total; ?></p>
    <ol>
        <?php foreach ($participants as $p): ?>
            <li><?php echo htmlspecialchars($p); ?></li>
        <?php endforeach; ?>
    </ol>
</div>
<?php }?>
