<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên - HNMU</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    
        /* Content Area */
        .content { flex: 1; padding: 20px; background-color: #fff; margin: 15px; border-radius: 4px; overflow-y: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .content h2 { margin-bottom: 20px; font-size: 20px; color: #333; }

        /* Bộ lọc & Nút bấm (Filter Row) */
        .filter-section { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; align-items: center; }
        .filter-section input, .filter-section select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; outline: none; flex: 1; min-width: 150px; }
        
        .btn-group-top { display: flex; gap: 10px; margin-left: auto; }
        .btn { border: none; padding: 8px 15px; border-radius: 4px; color: white; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 5px; font-size: 13px; }
        
        .btn-add { background-color: #2ecc71; }
        .btn-excel-export { background-color: #27ae60; }
        .btn-excel-import { background-color: #2980b9; }
        .btn-search { background-color: #3498db; }
        .btn-reset { background-color: #e67e22; }

        /* Table Area */
        table { width: 100%; border-collapse: collapse; text-align: center; margin-top: 10px; font-size: 14px; }
        thead { background-color: #f8f9fa; }
        th, td { padding: 12px 8px; border: 1px solid #dee2e6; }
        th { font-weight: bold; color: #555; }
        
        .actions i { cursor: pointer; margin: 0 5px; font-size: 16px; padding: 5px; border-radius: 3px; color: white; }
        .fa-eye { background-color: #17a2b8; }
        .fa-pen { background-color: #ffc107; }
        .fa-trash { background-color: #dc3545; }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-left">
            <img src="public/logo.jpg" alt="Logo" class="header-logo">
            <h2>Quản Lý Sinh Viên</h2>
        </div>
        <div class="header-right">
            <a href="#" id="userEmailDisplay">guest@example.com</a>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="main_menu.html"><i class="fa-solid fa-house"></i> Trang Chủ</a></li>
                <li><a href="khoa.html"><i class="fa-solid fa-layer-group"></i> Khoa</a></li>
                <li><a href="nganh.html"><i class="fa-solid fa-code-branch"></i> Ngành</a></li>
                <li><a href="chuongTrinhDaoTao.html"><i class="fa-solid fa-graduation-cap"></i> CT Đào Tạo</a></li>
                <li><a href="lop.html"><i class="fa-solid fa-table-cells"></i> Lớp</a></li>
                <li class="active"><a href="sinhvien.php"><i class="fa-solid fa-user-graduate"></i> Sinh Viên</a></li>
                <li><a href="giangvien.html"><i class="fa-solid fa-chalkboard-user"></i> Giảng Viên</a></li>
                <li><a href="monhoc.html"><i class="fa-solid fa-book"></i> Môn Học</a></li>
                <li><a href="diem.html"><i class="fa-solid fa-star"></i> Điểm</a></li>
                <li><a href="diemdanh.html"><i class="fa-solid fa-calendar-check"></i> Điểm Danh</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Danh sách Sinh viên</h2>

            <div class="filter-section">
                <input type="date">
                <select><option>Chọn lớp</option></select>
                <select><option>Chọn khoa</option></select>
                
                <div class="btn-group-top">
                    <button class="btn btn-add" onclick="location.href='themsinhvien.php'"> Thêm mới<i class="fa-solid fa-circle-plus"></i></button>
                    <button class="btn btn-excel-export" onclick="location.href='export_excel.php'"><i class="fa-solid fa-file-excel"></i> Export Excel</button>
                    <button class="btn btn-excel-import" onclick="location.href='import_excel.php'"><i class="fa-solid fa-file-import"></i> Import Excel</button>
                </div>
            </div>

            <div class="filter-section">
                <input type="text" placeholder="Tìm mã sinh viên...">
                <input type="text" placeholder="Tìm tên sinh viên...">
                <input type="date">
                <select><option>Chọn lớp</option></select>
                
                <button class="btn btn-search"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
                <button class="btn btn-reset"><i class="fa-solid fa-rotate-left"></i> Reset</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th>Ngày sinh</th>
                        <th>Lớp</th>
                        <th>Khoa</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once 'db_connect.php';

                    try {
                        // Query dữ liệu sinh viên kèm tên lớp và tên khoa
                        $sql = "SELECT sv.*, l.ten_lop, k.ten_khoa 
                                FROM sinh_vien sv
                                LEFT JOIN lop l ON sv.ma_lop = l.ma_lop
                                LEFT JOIN khoa k ON l.ma_khoa = k.ma_khoa";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $stt = 1;
                        foreach ($result as $row) {
                            $formattedDate = date("d-m-Y", strtotime($row['ngay_sinh']));
                            echo "<tr>";
                            echo "<td>" . $stt++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['ma_sv']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ho_ten']) . "</td>";
                            echo "<td>" . $formattedDate . "</td>";
                            echo "<td>" . htmlspecialchars($row['ten_lop']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ten_khoa']) . "</td>";
                            echo '<td class="actions">
                                    <i class="fa-solid fa-eye"></i>
                                    <i class="fa-solid fa-pen" onclick="location.href=\'capnhatsinhvien.php?id=' . $row['ma_sv'] . '\'"></i>
                                    <i class="fa-solid fa-trash" onclick="return confirm(\'Bạn có chắc muốn xóa sinh viên này?\')"></i>
                                  </td>';
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='7'>Lỗi truy vấn: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>