--------------CREATE TABLE------------------>

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `prod_id` varchar(8) NOT NULL,
  `prod_name` varchar(100) DEFAULT NULL,
  `prod_image` text DEFAULT NULL,
  `prod_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `product` (
  `prod_id` varchar(8) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `prod_image` varchar(10000) NOT NULL,
  `prod_price` double NOT NULL,
  `prod_quantity` int(11) NOT NULL,
  `prod_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    gender VARCHAR(20),
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(100),
    photo VARCHAR(200),
    password VARCHAR(255) NOT NULL
);

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(100) NOT NULL,
    admin_gender VARCHAR(20),
    admin_position VARCHAR(30),
    admin_email VARCHAR(100) NOT NULL,
    admin_phone VARCHAR(20),
    admin_photo VARCHAR(200),
    admin_password VARCHAR(255) NOT NULL);




-------------Set PK AND FK----------------------->

ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`,`prod_id`),
  ADD KEY `prod_id` (`prod_id`);

ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`),
  ADD UNIQUE KEY `prod_id` (`prod_id`);

ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`);




---------------------Insert Data----------------------->

INSERT INTO `cart` (`cart_id`, `user_id`, `prod_id`, `prod_name`, `prod_image`, `prod_price`, `quantity`) VALUES
(3, 'q123', '25FLW001', 'Newcastle', 'prod1.jpg', 150.00, 1),
(3, 'q123', '25FLW012', 'Speacial Graduation Bear', 'prod12.jpg', 29.90, 2),
(5, 'a123', '25FLW003', 'Purdue', 'prod3.jpg', 210.00, 1);

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_image`, `prod_price`, `prod_quantity`, `prod_type`) VALUES
('25FLW001', 'Newcastle', 'prod1.jpg', 150, 100, 'Bouquet'),
('25FLW002', 'Bloomington', 'prod2.jpg', 200, 100, 'Bouquet'),
('25FLW003', 'Purdue', 'prod3.jpg', 210, 100, 'Bouquet'),
('25FLW004', 'Big Dream Graduation Bundle', 'prod4.jpg', 220, 100, 'Bouquet'),
('25FLW005', 'Proud of You Bundle', 'prod5.jpg', 219, 100, 'Bouquet'),
('25FLW006', 'Florida Bundle', 'prod6.jpg', 219, 100, 'Bouquet'),
('25FLW007', 'Pursuit Success Bundle', 'prod7.jpg', 199, 100, 'Bouquet'),
('25FLW008', 'Beyond Spotlight', 'prod8.jpg', 199, 100, 'Bouquet'),
('25FLW009', 'Little Joy Convo', 'prod9.jpg', 130, 100, 'Bouquet'),
('25FLW010', 'Full A Grads Bundle Balloon Bunch', 'prod10.jpg', 250, 100, 'Bouquet'),
('25FLW011', 'Graduation Bear', 'prod11.jpg', 29.9, 100, 'Bear'),
('25FLW012', 'Speacial Graduation Bear', 'prod12.jpg', 29.9, 100, 'Bear'),
('25FLW013', 'Graduation Kapibala', 'prod13.jpg', 31.9, 100, 'Bear'),
('25FLW014', 'Premium Kuromi', 'prod14.jpg', 200, 100, 'Bouquet'),
('25FLW015', 'Graduation Stitch', 'prod15.jpg', 29.9, 100, 'Bear'),
('25FLW016', 'Diploma/Degree Graduation Uniform (free size)', 'prod16.jpg', 200, 100, 'Uniform'),
('25FLW017', 'Diploma/Degree Graduation Uniform (free size)', 'prod17.jpg', 200, 100, 'Uniform'),
('25FLW018', 'Diploma/Degree Graduation Uniform (free size)', 'prod18.jpg', 200, 100, 'Uniform'),
('25FLW019', 'Graduation Hat', 'prod19.jpg', 24.9, 100, 'Uniform'),
('25FLW020', 'Graduation CipMunk', 'prod20.jpg', 19.9, 100, 'Bear');


INSERT INTO `users` (`username`, `email`, `password`, `created_at`) VALUES
('grad', 'graduate@gmail.com', 'password123', NOW()),
('alex123', 'alex@gmail.com', 'alexpass456', NOW()),
('siti_nur', 'siti@gmail.com', 'nurpass789', NOW()),
('john_doe', 'john@gmail.com', 'johndoepass', NOW()),
('amy89', 'amy@gmail.com', 'amysecurepwd', NOW());