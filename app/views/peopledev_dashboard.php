<div class="main-wrapper">
    <div class="header-stats">
        <h2 style="color: #117054; margin-bottom: 20px;">People Dev Monitoring Dashboard</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <div class="stat-card">
                <p>Total Training Sessions</p>
                <h3><?= $stats['total_sessions'] ?></h3>
            </div>
            <div class="stat-card">
                <p>Total Participants</p>
                <h3><?= $stats['total_participants'] ?></h3>
            </div>
            <div class="stat-card">
                <p>Avg. Post-Test Score</p>
                <h3><?= number_format($stats['avg_post_test'], 1) ?></h3>
            </div>
        </div>
    </div>
    <div class="stat-card">
    <p>Total Training Sessions</p>
    <h3><?= $stats['total_sessions'] ?? 0 ?></h3>
</div>
<div class="stat-card">
    <p>Total Participants</p>
    <h3><?= $stats['total_participants'] ?? 0 ?></h3>
</div>
<div class="stat-card">
    <p>Avg. Post-Test Score</p>
    <h3><?= number_format((float)($stats['avg_post_test'] ?? 0), 1) ?></h3>
</div>
    <div class="table-container" style="margin-top: 30px;">
        <h3>Executed Training Programs</h3>
        <table>
            <thead>
                <tr>
                    <th>Training Name</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($trainings as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_training']) ?></td>
                    <td><?= date('d M Y', strtotime($row['date_start'])) ?></td>
                    <td><span class="badge"><?= $row['method'] ?></span></td>
                    <td>
                        <a href="index.php?action=details&id=<?= $row['id_session'] ?>" class="btn-view">View Scores</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>