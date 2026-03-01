<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: Arial, sans-serif; font-size: 8.5pt; color: #000; line-height: 1.2; }
        
        /* Header Section */
        .header-container { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .company-info { width: 60%; font-weight: bold; }
        .doc-info { width: 40%; text-align: right; font-size: 8pt; }
        .doc-info td { padding: 1px 0; }

        .title { 
            text-align: center; 
            font-weight: bold; 
            font-size: 12pt; 
            text-decoration: underline; 
            margin: 20px 0;
        }

        /* Identity Section */
        .identity-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .identity-table td { padding: 3px 0; vertical-align: top; }
        .label { width: 110px; font-weight: bold; }
        .colon { width: 15px; }

        /* Main Table Style (Excel Look) */
        .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .main-table th { 
            border: 1px solid #000; 
            background-color: #e2e2e2; 
            padding: 6px 2px; 
            font-size: 8pt; 
            font-weight: bold;
            text-align: center;
        }
        .main-table td { 
            border: 1px solid #000; 
            padding: 5px 4px; 
            word-wrap: break-word; 
            vertical-align: middle; 
            font-size: 8pt;
        }

        /* Column Widths (Total 100%) */
        .col-no { width: 25px; text-align: center; }
        .col-name { width: 160px; }
        .col-date { width: 70px; text-align: center; }
        .col-hour { width: 35px; text-align: center; }
        .col-trainer { width: 85px; }
        .col-vendor { width: 75px; }
        .col-type { width: 55px; text-align: center; }
        .col-method { width: 55px; text-align: center; }
        .col-score { width: 35px; text-align: center; }

        .section-title { font-weight: bold; margin-bottom: 5px; display: block; }
    </style>
</head>
<body>

    <table class="header-container">
        <tr>
            <td class="company-info">
                PT GREAT GIANT PINEAPPLE<br>
                People Development
            </td>
            <td class="doc-info">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr><td style="text-align: left;">Doc. No.</td><td>: F/H/T. 22</td></tr>
                    <tr><td style="text-align: left;">Rev. No.</td><td>: 3</td></tr>
                    <tr><td style="text-align: left;">Page</td><td>: 1 of 1</td></tr>
                    <tr><td style="text-align: left;">Date of Issue</td><td>: 31 Mei 2021</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="title">EMPLOYEE TRAINING REPORTS</div>

    <table class="identity-table">
        <tr>
            <td class="label">Indeks</td>
            <td class="colon">:</td>
            <td><?= htmlspecialchars($user['index_karyawan'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td><?= htmlspecialchars($user['nama_karyawan'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Bu</td>
            <td class="colon">:</td>
            <td><?= htmlspecialchars($user['bu'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Function N-1</td>
            <td class="colon">:</td>
            <td><?= htmlspecialchars($user['func'] ?? ($user['latest_func_n1'] ?? '-')) ?></td>
        </tr>
        <tr>
            <td class="label">Function N-2</td>
            <td class="colon">:</td>
            <td><?= htmlspecialchars($user['func2'] ?? ($user['latest_func_n2'] ?? '-')) ?></td>
        </tr>
    </table>

    <span class="section-title">TRAINING JOINED</span>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-name">Training Name</th>
                <th class="col-date">Tanggal</th>
                <th class="col-hour">Kredit Hours</th>
                <th class="col-trainer">Trainers</th>
                <th class="col-vendor">Lembaga</th>
                <th class="col-type">Type</th>
                <th class="col-method">Method</th>
                <th class="col-score">Pre-Test</th>
                <th class="col-score">Post-Test</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($history['data'])): ?>
                <?php $no = 1; foreach ($history['data'] as $h): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($h['nama_training']) ?></td>
                    <td style="text-align: center;"><?= date('d-M-Y', strtotime($h['date_start'])) ?></td>
                    <td style="text-align: center;"><?= $h['credit_hour'] ?></td>
                    <td><?= htmlspecialchars($h['instructor_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($h['lembaga'] ?? '-') ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($h['training_type'] ?? '-') ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($h['method'] ?? '-') ?></td>
                    <td style="text-align: center;"><?= $h['pre'] ?></td>
                    <td style="text-align: center;"><?= $h['post'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align: center;">No data found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>