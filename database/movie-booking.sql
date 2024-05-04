CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;


CREATE TABLE IF NOT EXISTS `Users` (
  `userId` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(500) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phoneNumber` VARCHAR(20),
  `cccd` VARCHAR(20),
  `birthday` DATE,
  `sex` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `userType` INT(1) NOT NULL
);

ALTER TABLE Users AUTO_INCREMENT = 100000000;

--  admin | password@123
--  threater1 | password@123
--  user1234 | 123456

INSERT INTO Users (username, password, email, userType) VALUES
("admin", "$2y$10$4NUdOlq/gpkBAP0Ld3UoNeKCArlARvqWRFOjApVPt9/bhdAlLp1pi", "admin@gmail.com", 0),
("threater1", "$2y$10$4NUdOlq/gpkBAP0Ld3UoNeKCArlARvqWRFOjApVPt9/bhdAlLp1pi", "threater1@gmail.com", 1),
("threater2", "$2y$10$4NUdOlq/gpkBAP0Ld3UoNeKCArlARvqWRFOjApVPt9/bhdAlLp1pi", "threater1@gmail.com", 1),
("user1234", "$2y$10$4NUdOlq/gpkBAP0Ld3UoNeKCArlARvqWRFOjApVPt9/bhdAlLp1pi", "user1@gmail.com", 2);

CREATE TABLE IF NOT EXISTS `Movies` (
  `movieID` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `releaseDate` DATE,
  `duration` INT,
  `genre` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster` VARCHAR(200),
  `image` VARCHAR(200),
  `videoUrl` VARCHAR(200),
  `tomatoPoint` INT,
  `status` TINYINT(1) COMMENT '1 for available, 0 for unavailable'
);

ALTER TABLE Movies AUTO_INCREMENT = 100000000;

INSERT INTO Movies (name, description, releaseDate, duration, genre, poster, image, videoUrl, tomatoPoint, status) VALUES
("Mắt biếc", "Đi qua những đau khổ và phản bội, mối tình đơn phương của Ngạn dành cho cô bạn thân thời thơ ấu Hà Lan kéo dài cả một thế hệ trong bộ phim siêu lãng mạn này.", "2019-12-20", 117, "Lãng mạn", "/assets/images/movie/mat_biec_poster.jpg", "/assets/images/movie/mat_biec.jpg", "https://youtu.be/ITlQ0oU7tDA?si=fcAdCZhY7QD72gOO", 69, 1),
("Đào, phở và piano", "Lấy bối cảnh trận chiến đông xuân kéo dài 60 ngày đêm từ cuối năm 1946 đến đầu năm 1947 ở Hà Nội, câu chuyện theo chân chàng dân quân Văn Dân và chuyện tình với nàng tiểu thư đam mê dương cầm Thục Hương. Khi những người khác đã di tản lên chiến khu, họ quyết định cố thủ lại mảnh đất thủ đô đã tan hoang vì bom đạn, mặc cho những hiểm nguy đang chờ đợi trước mắt.", "2024-2-22", 100, "Chiến tranh, Chính kịch", "/assets/images/movie/dao_pho_piano_poster.jpg", "/assets/images/movie/dao_pho_piano.jpg", "https://youtu.be/qn1t_biQigc?si=GelGe2spBx50NmcU", 78, 1),
("Bố già", "Trong phim, Trấn Thành vào vai ông Tư, một tài xế xe ôm quần quật làm việc qua ngày để chăm lo cho gia đình của mình. Mặc dù khá bảo thủ, nóng nảy, thường xuyên quát tháo nhưng thực chất ông Tư lại là một người rất giàu lòng yêu thương, không chỉ với người thân mà còn có hàng xóm, bạn bè xung quanh và thậm chí là cả những người xa lạ.", "2021-3-5", 128, "Hài, Gia đình", "/assets/images/movie/bo_gia_poster.jpg", "/assets/images/movie/Bo-Gia.jpg", "https://youtu.be/g8_DQqqTabk?si=CFL6kAANmNPUFacu", 70, 1),
("Bố già", "Trong phim, Trấn Thành vào vai ông Tư, một tài xế xe ôm quần quật làm việc qua ngày để chăm lo cho gia đình của mình. Mặc dù khá bảo thủ, nóng nảy, thường xuyên quát tháo nhưng thực chất ông Tư lại là một người rất giàu lòng yêu thương, không chỉ với người thân mà còn có hàng xóm, bạn bè xung quanh và thậm chí là cả những người xa lạ.", "2021-3-5", 128, "Hài, Gia đình", "/assets/images/movie/bo_gia_poster.jpg", "/assets/images/movie/Bo-Gia.jpg", "https://youtu.be/g8_DQqqTabk?si=CFL6kAANmNPUFacu", 70, 1);

CREATE TABLE IF NOT EXISTS `Theaters` (
  `theaterID` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `state` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipCode` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` INT
);

ALTER TABLE Theaters AUTO_INCREMENT = 100000000;


INSERT INTO Theaters (name, state, address, zipCode, userId) VALUES
("Cinema 01", "Hà Nội", "	Ba Đình", 118000, 100000001),
("Cinema IMAX", "Hà Nội", "Hai Bà Trưng", 113000, 100000002);

CREATE TABLE IF NOT EXISTS `Screens` (
  `screenId` INT PRIMARY KEY AUTO_INCREMENT,
  `screenName` VARCHAR(100),
  `theaterID` INT,
  `charge` INT
);

ALTER TABLE Screens AUTO_INCREMENT = 100000000;

CREATE TABLE IF NOT EXISTS `Shows` (
  `showId` INT PRIMARY KEY AUTO_INCREMENT,
  `screenId` INT,
  `movieID` INT,
  `showDate` DATE,
  `showTime` INT(1)
);

ALTER TABLE Shows AUTO_INCREMENT = 100000000;

CREATE TABLE IF NOT EXISTS `Seats` (
  `seatId` INT PRIMARY KEY AUTO_INCREMENT,
  `showId` INT,
  `seatName` VARCHAR(5),
  `avaible` INT(1)
);

ALTER TABLE Seats AUTO_INCREMENT = 100000000;

CREATE TABLE IF NOT EXISTS `Tickets` (
  `ticketId` INT PRIMARY KEY AUTO_INCREMENT,
  `showId` INT,
  `userId` INT,
  `seats` VARCHAR(255),
  `charge` INT
);

ALTER TABLE Tickets AUTO_INCREMENT = 100000000;

ALTER TABLE `Screens` ADD FOREIGN KEY (`theaterID`) REFERENCES `Theaters` (`theaterID`);

ALTER TABLE `Shows` ADD FOREIGN KEY (`screenId`) REFERENCES `Screens` (`screenId`);

ALTER TABLE `Shows` ADD FOREIGN KEY (`movieID`) REFERENCES `Movies` (`movieID`);

ALTER TABLE `Seats` ADD FOREIGN KEY (`showId`) REFERENCES `Shows` (`showId`);

ALTER TABLE `Tickets` ADD FOREIGN KEY (`showId`) REFERENCES `Shows` (`showId`);

ALTER TABLE `Tickets` ADD FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);
