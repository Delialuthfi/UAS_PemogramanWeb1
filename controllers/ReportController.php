<?php
require_once __DIR__.'/../app/bootstrap.php';
require_once __DIR__.'/../assets/tcpdf/SimplePDFGenerator.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../views/auth/login.php');
    exit;
}

// Export PDF/Excel
if (isset($_GET['export'])) {
    $format = $_GET['export'];
    $type = $_GET['type'] ?? 'booking';
    
    if ($format == 'excel') {
        if ($type == 'booking') {
            $filename = 'Laporan_Booking_'.date('Y-m-d').'.csv';
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            
            // BOM untuk UTF-8 agar Excel membaca dengan benar
            echo "\xEF\xBB\xBF";
            
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['No', 'Nama Pasien', 'Email', 'Nama Dokter', 'Jadwal Konsultasi', 'Keluhan', 'Status Booking', 'Tanggal Dibuat'], ';');
            
            $result = $conn->query('SELECT b.*, u.nama as user_nama, u.email, d.nama as doctor_nama, s.hari, s.jam_mulai, s.jam_selesai FROM bookings b JOIN users u ON b.user_id = u.id JOIN doctors d ON b.doctor_id = d.id JOIN schedules s ON b.schedule_id = s.id ORDER BY b.created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                fputcsv($fp, [
                    $no++,
                    $row['user_nama'],
                    $row['email'],
                    $row['doctor_nama'],
                    $row['hari'].', '.$row['jam_mulai'].'-'.$row['jam_selesai'],
                    $row['keluhan'],
                    ucfirst($row['status']),
                    $row['created_at']
                ], ';');
            }
            fclose($fp);
        } elseif ($type == 'pasien') {
            $filename = 'Laporan_Pasien_'.date('Y-m-d').'.csv';
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            
            echo "\xEF\xBB\xBF";
            
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['No', 'Nama Lengkap', 'Alamat Email', 'Nomor HP', 'Tanggal Terdaftar'], ';');
            
            $result = $conn->query('SELECT id, nama, email, no_hp, created_at FROM users WHERE role="user" ORDER BY created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                fputcsv($fp, [
                    $no++,
                    $row['nama'],
                    $row['email'],
                    $row['no_hp'],
                    $row['created_at']
                ], ';');
            }
            fclose($fp);
        } elseif ($type == 'dokter') {
            $filename = 'Laporan_Dokter_'.date('Y-m-d').'.csv';
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            
            echo "\xEF\xBB\xBF";
            
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['No', 'Nama Dokter', 'Spesialis', 'Nomor Telepon', 'Tarif Konsultasi', 'Tanggal Bergabung'], ';');
            
            $result = $conn->query('SELECT id, nama, spesialis, tarif, telepon, created_at FROM doctors ORDER BY created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                fputcsv($fp, [
                    $no++,
                    $row['nama'],
                    $row['spesialis'],
                    $row['telepon'],
                    $row['tarif'],
                    $row['created_at']
                ], ';');
            }
            fclose($fp);
        }
        exit;
    } 
    elseif ($format == 'pdf') {
        $pdf = new SimplePDFGenerator();
        
        // Data Rumah Sakit Immanuel
        $pdf->addCompanyHeader('Rumah Sakit Immanuel Bandung', 'Jl. Raya Kopo No.161, Situsaeur, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40233');
        
        if ($type == 'booking') {
            $pdf->addTitle('LAPORAN DATA BOOKING');
            $pdf->addSubtitle('Periode Export: ' . date('d F Y H:i'));
            
            $headers = ['No', 'Pasien', 'Dokter', 'Jadwal', 'Status'];
            $colWidths = [10, 30, 30, 40, 20]; // Total 130 proportion
            $rows = [];
            
            $result = $conn->query('SELECT b.*, u.nama as user_nama, d.nama as doctor_nama, s.hari, s.jam_mulai FROM bookings b JOIN users u ON b.user_id = u.id JOIN doctors d ON b.doctor_id = d.id JOIN schedules s ON b.schedule_id = s.id ORDER BY b.created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                $rows[] = [
                    (string)$no++,
                    $row['user_nama'],
                    $row['doctor_nama'],
                    $row['hari'] . ', ' . $row['jam_mulai'],
                    ucfirst($row['status'])
                ];
            }
            $pdf->addTable($headers, $rows, $colWidths);
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Laporan_Booking_'.date('Y-m-d').'.pdf"');
        }
        elseif ($type == 'pasien') {
            $pdf->addTitle('LAPORAN DATA PASIEN');
            $pdf->addSubtitle('Periode Export: ' . date('d F Y H:i'));
            
            $headers = ['No', 'Nama', 'Email', 'No HP', 'Terdaftar'];
            $colWidths = [8, 30, 45, 25, 20]; // Email needs more space
            $rows = [];
            
            $result = $conn->query('SELECT id, nama, email, no_hp, created_at FROM users WHERE role="user" ORDER BY created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                $rows[] = [
                    (string)$no++,
                    $row['nama'],
                    $row['email'],
                    $row['no_hp'],
                    date('d/m/Y', strtotime($row['created_at']))
                ];
            }
            $pdf->addTable($headers, $rows, $colWidths);
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Laporan_Pasien_'.date('Y-m-d').'.pdf"');
        }
        elseif ($type == 'dokter') {
            $pdf->addTitle('LAPORAN DATA DOKTER');
            $pdf->addSubtitle('Periode Export: ' . date('d F Y H:i'));
            
            $headers = ['No', 'Nama', 'Spesialis', 'Telepon', 'Tarif'];
            $colWidths = [8, 35, 30, 25, 25];
            $rows = [];
            
            $result = $conn->query('SELECT id, nama, spesialis, tarif, telepon FROM doctors ORDER BY created_at DESC');
            $no = 1;
            while($row = $result->fetch_assoc()) {
                $rows[] = [
                    (string)$no++,
                    $row['nama'],
                    $row['spesialis'],
                    $row['telepon'],
                    'Rp ' . number_format($row['tarif'], 0, ',', '.')
                ];
            }
            $pdf->addTable($headers, $rows, $colWidths);
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Laporan_Dokter_'.date('Y-m-d').'.pdf"');
        }
        
        echo $pdf->generatePDF();
        exit;
    }
}
?>
