<?php
$id_score = $_GET['id'] ?? null;

if ($id_score && isset($this->pdo)) {
    try {
        $stmt = $this->pdo->prepare("
            SELECT 
                k.nama_karyawan, 
                t.nama_training,
                t.instructor_name,
                ts.date_start
            FROM score s
            JOIN karyawan k ON s.id_karyawan = k.id_karyawan
            JOIN training_session ts ON s.id_session = ts.id_session
            JOIN training t ON ts.id_training = t.id_training
            WHERE s.id_score = ?
        ");
        $stmt->execute([$id_score]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $nama_user = $data['nama_karyawan'];
            $nama_training = $data['nama_training'];
            $instructor_name = $data['instructor_name'] ?? 'Instructor';
            $tanggal_training = $data['date_start'];
        }
    } catch (PDOException $e) {
        $nama_user = "NAMA KARYAWAN";
        $nama_training = "Pelatihan";
        $instructor_name = "Trainer";
        $tanggal_training = date('Y-m-d');
    }
}

$logo_path = 'app/public/icons/GGF white.png'; 
$logo_base64 = '';

if (file_exists($logo_path)) {
    $type = pathinfo($logo_path, PATHINFO_EXTENSION);
    $data_img = file_get_contents($logo_path);
    $logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data_img);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Certificate - <?= htmlspecialchars($nama_user ?? 'Download') ?></title>
    <style>
        @page { 
            margin: 0; 
        } 
        
        body { 
            margin: 0; 
            padding: 0;
            font-family: 'Helvetica', sans-serif; 
            background-color: #ffffff;
        }

        .certificate-wrapper { 
            width: 1120px; 
            height: 790px; 
            position: relative;
            box-sizing: border-box;
            background-image: url('https://www.transparenttextures.com/patterns/hexellence.png');
            background-color: #ffffff;
            overflow: hidden;
        }

        .outer-border {
            position: absolute;
            top: 20px; left: 20px; right: 20px; bottom: 20px;
            border: 2px solid #b38b3d;
            z-index: 10;
        }

        /* Bingkai Emas Dalam */
        .inner-border {
            position: absolute;
            top: 35px; left: 35px; right: 35px; bottom: 35px;
            border: 1px solid #b38b3d;
            z-index: 11;
        }

        .content {
            position: relative;
            z-index: 20;
            text-align: center;
            padding: 60px 80px;
        }

        .logo-ggf {
            margin-bottom: 10px;
        }

        .title-box h1 {
            font-size: 65px;
            color: #0a2a55;
            margin: 10px 0 0 0;
            letter-spacing: 5px;
        }

        .title-box h2 {
            font-size: 24px;
            color: #b38b3d;
            margin: 0;
            letter-spacing: 8px;
            font-weight: bold;
        }

        .presented {
            margin-top: 40px;
            font-size: 20px;
            color: #9d7a32;
            font-weight: bold;
        }

        .name {
            font-size: 50px;
            color: #0a2a55;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #b38b3d;
            display: inline-block;
            padding: 0 50px 5px 50px;
            white-space: nowrap; 
        }

        .description {
            font-size: 18px;
            width: 85%;
            margin: 25px auto;
            color: #444;
            line-height: 1.5;
            font-style: italic;
        }

        .signature-container {
            margin-top: 50px;
            width: 100%;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sig-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
        }

        .sig-font {
            font-family: 'Times-Italic', serif; 
            font-size: 30px;
            color: #222;
            margin-bottom: 5px;
        }

        .sig-line {
            border-top: 2px solid #b38b3d;
            width: 220px;
            margin: 0 auto;
            padding-top: 8px;
        }

        .sig-name {
            font-weight: bold;
            font-size: 16px;
            color: #0a2a55;
        }

        .sig-title {
            font-size: 14px;
            color: #666;
        }

        .date-text {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="outer-border"></div>
        <div class="inner-border"></div>

        <div class="content">
            <div class="logo-ggf">
              <?php if (!empty($logo_base64)): ?>
                  <img src="<?= $logo_base64 ?>" alt="Logo GGF" style="width: 200px;">
              <?php else: ?>
                  <h2 style="color: #2e7d32;">GGF GREAT GIANT FOODS</h2>
              <?php endif; ?>
          </div>
            <div class="title-box">
                <h1>CERTIFICATE</h1>
                <h2>OF ACHIEVEMENT</h2>
            </div>

            <div class="presented">This certificate is proudly presented to</div>

            <div class="name"><?= htmlspecialchars($nama_user ?? 'NAMA KARYAWAN') ?></div>

            <div class="description">
                This certificate is presented as a token of appreciation and
                recognition for your participation and dedication in completing
                Training <strong>"<?= htmlspecialchars($nama_training ?? 'Nama Pelatihan') ?>"</strong>. <br>
                May the knowledge gained serve as an inspiration for broader creativity.
            </div>

            <div class="signature-container">
                <table class="sig-table">
                    <tr>
                        <td>
                            <div class="sig-font">muhammadhabibi</div>
                            <div class="sig-line">
                                <div class="sig-name">M. HABIBI</div>
                                <div class="sig-title">Director</div>
                            </div>
                        </td>

                        <td>
                            <div class="sig-font">khairi</div>
                            <div class="sig-line">
                                <div class="sig-name">MUHAMMAD KHAIRI</div>
                                <div class="sig-title">Manager</div>
                            </div>
                        </td>

                        <td>
                            <div class="date-text">
                                Lampung, <?= isset($tanggal_training) ? date('d F Y', strtotime($tanggal_training)) : date('d F Y') ?>
                            </div>
                            
                            <div class="sig-font">
                                <?= htmlspecialchars($instructor_name ?? 'Trainer') ?>
                            </div>
                            
                            <div class="sig-line">
                                <div class="sig-name">
                                    <?= strtoupper(htmlspecialchars($instructor_name ?? 'TRAINER')) ?>
                                </div>
                                <div class="sig-title">Trainers</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>