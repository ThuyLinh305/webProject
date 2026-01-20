<?php
require 'vendor/autoload.php';
require_once 'db_connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// --- 1. Tiêu đề cột ---
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Mã Sinh Viên');
$sheet->setCellValue('C1', 'Họ Tên');
$sheet->setCellValue('D1', 'Ngày Sinh');
$sheet->setCellValue('E1', 'Lớp');
$sheet->setCellValue('F1', 'Khoa');

// Style cho tiêu đề (in đậm)
$sheet->getStyle('A1:F1')->getFont()->setBold(true);

// --- 2. Lấy dữ liệu từ Database ---
try {
    $sql = "SELECT sv.ma_sv, sv.ho_ten, sv.ngay_sinh, l.ten_lop, k.ten_khoa 
            FROM sinh_vien sv
            LEFT JOIN lop l ON sv.ma_lop = l.ma_lop
            LEFT JOIN khoa k ON l.ma_khoa = k.ma_khoa";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 3. Ghi dữ liệu vào file ---
    $rowCount = 2; // Bắt đầu từ dòng 2
    $stt = 1;
    foreach ($result as $row) {
        $sheet->setCellValue('A' . $rowCount, $stt++);
        $sheet->setCellValue('B' . $rowCount, $row['ma_sv']);
        $sheet->setCellValue('C' . $rowCount, $row['ho_ten']);
        $sheet->setCellValue('D' . $rowCount, date("d/m/Y", strtotime($row['ngay_sinh'])));
        $sheet->setCellValue('E' . $rowCount, $row['ten_lop']);
        $sheet->setCellValue('F' . $rowCount, $row['ten_khoa']);
        $rowCount++;
    }

    // Tự động chỉnh độ rộng cột
    foreach (range('A', 'F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // --- 4. Xuất file ra trình duyệt ---
    $filename = "danh_sach_sinh_vien_" . date('Y-m-d_H-i-s') . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    echo "Lỗi Export: " . $e->getMessage();
}
?>
