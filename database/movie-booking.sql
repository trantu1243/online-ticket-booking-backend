CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;

CREATE TABLE IF NOT EXISTS Users (
  userId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(500) NOT NULL,
  email VARCHAR(100) NOT NULL,
  userType INT(1) NOT NULL
);

ALTER TABLE Users AUTO_INCREMENT = 100000000;

--  admin | password@123
--  threater1 | password@123
--  user1234 | 123456

INSERT INTO Users (username, password, email, userType) VALUES
("admin", "$2y$10$F6knkmFQ5QtxIlL/LHRCselsiq2JfTrag5llnMebxz7Xn968KPTvS", "admin@gmail.com", 0),
("threater1", "$2y$10$F6knkmFQ5QtxIlL/LHRCselsiq2JfTrag5llnMebxz7Xn968KPTvS", "threater1@gmail.com", 1),
("user1234", "$2y$10$4NUdOlq/gpkBAP0Ld3UoNeKCArlARvqWRFOjApVPt9/bhdAlLp1pi", "user1@gmail.com", 2);

CREATE TABLE IF NOT EXISTS Movies (
    movieID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    releaseDate DATE,
    duration INT,
    genre VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    poster VARCHAR(200),
    image VARCHAR(200),
    videoUrl VARCHAR(200),
    tomatoPoint INT,
    photo VARCHAR(500),
    status TINYINT(1) COMMENT '1 for available, 0 for unavailable'
);

ALTER TABLE Movies AUTO_INCREMENT = 100000000;

INSERT INTO Movies (name, description, releaseDate, duration, genre, poster, image, videoUrl, tomatoPoint, status) VALUES
("Mắt biếc", "Đi qua những đau khổ và phản bội, mối tình đơn phương của Ngạn dành cho cô bạn thân thời thơ ấu Hà Lan kéo dài cả một thế hệ trong bộ phim siêu lãng mạn này.", "2019-12-20", 117, "Lãng mạn", "/assets/images/movie/mat_biec_poster.jpg", "/assets/images/movie/mat_biec.jpg", "https://youtu.be/ITlQ0oU7tDA?si=fcAdCZhY7QD72gOO", 69, 1),
("Đào, phở và piano", "Lấy bối cảnh trận chiến đông xuân kéo dài 60 ngày đêm từ cuối năm 1946 đến đầu năm 1947 ở Hà Nội, câu chuyện theo chân chàng dân quân Văn Dân và chuyện tình với nàng tiểu thư đam mê dương cầm Thục Hương. Khi những người khác đã di tản lên chiến khu, họ quyết định cố thủ lại mảnh đất thủ đô đã tan hoang vì bom đạn, mặc cho những hiểm nguy đang chờ đợi trước mắt.", "2024-2-22", 100, "Chiến tranh, Chính kịch", "/assets/images/movie/dao_pho_piano_poster.jpg", "/assets/images/movie/dao_pho_piano.jpg", "https://youtu.be/qn1t_biQigc?si=GelGe2spBx50NmcU", 78, 1),
("Bố già", "Trong phim, Trấn Thành vào vai ông Tư, một tài xế xe ôm quần quật làm việc qua ngày để chăm lo cho gia đình của mình. Mặc dù khá bảo thủ, nóng nảy, thường xuyên quát tháo nhưng thực chất ông Tư lại là một người rất giàu lòng yêu thương, không chỉ với người thân mà còn có hàng xóm, bạn bè xung quanh và thậm chí là cả những người xa lạ.", "2021-3-5", 128, "Hài, Gia đình", "/assets/images/movie/bo_gia_poster.jpg", "/assets/images/movie/Bo-Gia.jpg", "https://youtu.be/g8_DQqqTabk?si=CFL6kAANmNPUFacu", 70, 1);