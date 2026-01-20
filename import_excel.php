<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel - Quản Lý Sinh Viên</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .import-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .import-area {
            border: 2px dashed #3498db;
            padding: 40px;
            margin: 20px 0;
            border-radius: 5px;
            background-color: #f8fbff;
        }
        .btn-upload {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-back {
            background-color: #95a5a6; 
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="import-container">
    <h2>Import Sinh viên từ Excel</h2>
    <p>Vui lòng chọn file Excel (.xlsx) có đúng định dạng: <br>
    (Cột A: STT, B: Mã SV, C: Họ Tên, D: Ngày Sinh)</p>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="import-area">
            <input type="file" name="fileExcel" accept=".xlsx, .xls" required>
        </div>
        <button type="submit" name="importBtn" class="btn btn-upload"><i class="fa-solid fa-cloud-arrow-up"></i> Upload & Import</button>
        <button type="button" class="btn btn-upload btn-back" onclick="location.href='sinhvien.php'">Quay lại</button>
    </form>

    <?php
    require 'vendor/autoload.php';
    require_once 'db_connect.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;

    if (isset($_POST['importBtn']) && isset($_FILES['fileExcel'])) {
        $file = $_FILES['fileExcel']['tmp_name'];

        if ($file) {
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                $count = 0;
                // Bỏ qua dòng tiêu đề (dòng đầu tiên key = 0)
                foreach ($rows as $key => $row) {
                    if ($key == 0) continue; 

                    // Map dữ liệu theo cột excel bạn export lúc nãy
                    // B: Mã SV (index 1), C: Họ Tên (index 2), D: Ngày Sinh (index 3)
                    // Lớp và Khoa ở đây xử lý đơn giản: nếu chưa có thì để null hoặc gán mặc định
                    
                    $maSV = $row[1] ?? null;
                    $hoTen = $row[2] ?? null;
                    $ngaySinhRaw = $row[3] ?? null;

                    if ($maSV && $hoTen) {
                        // Xử lý ngày sinh (Excel có thể trả về số hoặc chuỗi)
                        $ngaySinh = null;
                        if ($ngaySinhRaw) {
                             if (is_numeric($ngaySinhRaw)) {
                                 // Excel date serial number
                                 $ngaySinh = date("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ngaySinhRaw));
                             } else {
                                 // Cố gắng parse string format d/m/Y
                                 $dateObj = DateTime::createFromFormat('d/m/Y', $ngaySinhRaw);
                                 if ($dateObj) {
                                     $ngaySinh = $dateObj->format('Y-m-d');
                                 } else {
                                     // Fallback format Y-m-d
                                     $ngaySinh = $ngaySinhRaw;
                                 }
                             }
                        }

                        // SQL Insert
                        $sql = "INSERT IGNORE INTO sinh_vien (ma_sv, ho_ten, ngay_sinh) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$maSV, $hoTen, $ngaySinh]);
                        
                        // Kiểm tra xem có row nào được insert không
                        if ($stmt->rowCount() > 0) {
                            $count++;
                        }
                    }
                }

                echo "<p style='color: green; margin-top: 20px; font-weight: bold;'>Thành công! Đã thêm $count sinh viên mới.</p>";

            } catch (Exception $e) {
                echo "<p style='color: red; margin-top: 20px;'>Lỗi: " . $e->getMessage() . "</p>";
            }
        }
    }
    ?>
</div>

</body>
</html>
