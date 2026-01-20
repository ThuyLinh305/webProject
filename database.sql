-- Tạo Cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS quanlysinhvien CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quanlysinhvien;

-- Tạo bảng Khoa
CREATE TABLE IF NOT EXISTS khoa (
    ma_khoa VARCHAR(20) PRIMARY KEY,
    ten_khoa VARCHAR(100) NOT NULL
);

-- Tạo bảng Lớp
CREATE TABLE IF NOT EXISTS lop (
    ma_lop VARCHAR(20) PRIMARY KEY,
    ten_lop VARCHAR(100) NOT NULL,
    ma_khoa VARCHAR(20),
    FOREIGN KEY (ma_khoa) REFERENCES khoa(ma_khoa)
);

-- Tạo bảng Sinh Viên
CREATE TABLE IF NOT EXISTS sinh_vien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_sv VARCHAR(20) UNIQUE NOT NULL,
    ho_ten VARCHAR(100) NOT NULL,
    ngay_sinh DATE,
    ma_lop VARCHAR(20),
    FOREIGN KEY (ma_lop) REFERENCES lop(ma_lop)
);

-- Thêm dữ liệu mẫu (Khoa)
INSERT INTO khoa (ma_khoa, ten_khoa) VALUES 
('CNTT', 'Khoa Công nghệ Thông tin'),
('KT', 'Khoa Kinh tế');

-- Thêm dữ liệu mẫu (Lớp)
INSERT INTO lop (ma_lop, ten_lop, ma_khoa) VALUES 
('K69CNTT', 'Công nghệ thông tin K69', 'CNTT'),
('K69KT', 'Kinh tế tài chính K69', 'KT');

-- Thêm dữ liệu mẫu (Sinh Viên)
INSERT INTO sinh_vien (ma_sv, ho_ten, ngay_sinh, ma_lop) VALUES 
('1520194031', 'Nguyễn Văn Dược', '1999-07-11', 'K69KT'),
('1520194032', 'Nguyễn Văn Dương', '2020-10-27', 'K69KT'),
('1520194033', 'Trần Thị Mai', '2001-05-15', 'K69CNTT');
