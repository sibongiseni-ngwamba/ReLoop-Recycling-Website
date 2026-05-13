CREATE DATABASE IF NOT EXISTS reloop_db;
USE reloop_db;

DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS waste_log;
DROP TABLE IF EXISTS guidance_content;
DROP TABLE IF EXISTS redemption_log;
DROP TABLE IF EXISTS reward_items;
DROP TABLE IF EXISTS rewards;
DROP TABLE IF EXISTS pickups;
DROP TABLE IF EXISTS agents;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS waste_categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwordHash VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(20),
    address VARCHAR(150),
    role ENUM('user','agent','admin') DEFAULT 'user',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    isActive BOOLEAN DEFAULT TRUE
);

CREATE TABLE agents (
    agentID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    name VARCHAR(100) NOT NULL,
    contactNumber VARCHAR(20),
    assignedZone VARCHAR(100),
    isAvailable BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE SET NULL
);

CREATE TABLE pickups (
    pickupID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    agentID INT NULL,
    scheduledDate DATE NOT NULL,
    scheduledTime TIME NOT NULL,
    wasteType VARCHAR(100) NOT NULL,
    address VARCHAR(150) NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
    FOREIGN KEY (agentID) REFERENCES agents(agentID) ON DELETE SET NULL
);

CREATE TABLE rewards (
    rewardID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT UNIQUE NOT NULL,
    pointsBalance INT DEFAULT 0,
    totalEarned INT DEFAULT 0,
    totalRedeemed INT DEFAULT 0,
    lastUpdated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

CREATE TABLE reward_items (
    rewardItemID INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    pointsCost INT NOT NULL,
    isActive BOOLEAN DEFAULT TRUE
);

CREATE TABLE redemption_log (
    redemptionID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    rewardItemID INT NOT NULL,
    pointsUsed INT NOT NULL,
    voucherCode VARCHAR(50) NOT NULL,
    redeemedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
    FOREIGN KEY (rewardItemID) REFERENCES reward_items(rewardItemID)
);

CREATE TABLE waste_categories (
    wasteCategoryID INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    modelLabel VARCHAR(100)
);

CREATE TABLE waste_log (
    wasteLogID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    pickupID INT NOT NULL,
    wasteCategoryID INT NOT NULL,
    weightKg DECIMAL(6,2),
    loggedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
    FOREIGN KEY (pickupID) REFERENCES pickups(pickupID) ON DELETE CASCADE,
    FOREIGN KEY (wasteCategoryID) REFERENCES waste_categories(wasteCategoryID)
);

CREATE TABLE guidance_content (
    guidanceID INT AUTO_INCREMENT PRIMARY KEY,
    wasteCategoryID INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    imagePath VARCHAR(255),
    FOREIGN KEY (wasteCategoryID) REFERENCES waste_categories(wasteCategoryID) ON DELETE CASCADE
);

CREATE TABLE notifications (
    notificationID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    message VARCHAR(500) NOT NULL,
    type ENUM('confirmation','reminder','system') NOT NULL,
    sentAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    isRead BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    messageID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (firstName, lastName, email, passwordHash, phoneNumber, address, role) VALUES
('Admin', 'User', 'admin@reloop.co.za', 'NEEDS_HASH:Admin@123', '+27 11 555 0100', 'ReLoop Head Office, Johannesburg', 'admin'),
('Demo', 'Recycler', 'user@reloop.co.za', 'NEEDS_HASH:User@123', '+27 82 555 0147', '24 Green Street, Johannesburg', 'user'),
('Thabo', 'Collector', 'agent1@reloop.co.za', 'NEEDS_HASH:Agent@123', '+27 73 555 0198', 'Soweto Zone Depot', 'agent');

INSERT INTO rewards (userID, pointsBalance, totalEarned) VALUES
(2, 120, 120);

INSERT INTO agents (userID, name, contactNumber, assignedZone, isAvailable) VALUES
(3, 'Thabo Collector', '+27 73 555 0198', 'Johannesburg South', TRUE),
(NULL, 'Naledi Mokoena', '+27 72 555 0182', 'Johannesburg North', TRUE);

INSERT INTO waste_categories (categoryName, description, modelLabel) VALUES
('Paper and Cardboard', 'Newspapers, office paper, boxes, and clean cardboard packaging.', 'paper_cardboard'),
('Plastic', 'Clean bottles, containers, tubs, and recyclable plastic packaging.', 'plastic'),
('Glass', 'Bottles and jars that are empty, rinsed, and sorted safely.', 'glass'),
('Metal', 'Food cans, drink cans, foil trays, and light scrap metal.', 'metal'),
('E-waste', 'Small electronics, cables, batteries, and device accessories.', 'ewaste');

INSERT INTO guidance_content (wasteCategoryID, title, content, imagePath) VALUES
(1, 'Paper and Cardboard Preparation', 'Keep paper dry. Flatten cardboard boxes and remove food residue before collection.', 'assets/images/paper.jpg'),
(2, 'Plastic Recycling Tips', 'Rinse plastic containers, remove excess liquid, and separate soft plastics where possible.', 'assets/images/plastic.jpg'),
(3, 'Glass Recycling Safety', 'Rinse glass bottles and jars. Do not include broken glass unless safely wrapped and labelled.', 'assets/images/glass.jpg'),
(4, 'Metal Recycling Tips', 'Rinse cans, squash them if possible, and keep sharp edges covered for safe handling.', 'assets/images/metal.jpg'),
(5, 'E-waste Handling', 'Keep electronics dry. Remove personal data from devices before handing them in.', 'assets/images/ewaste.jpg');

INSERT INTO reward_items (itemName, description, pointsCost, isActive) VALUES
('R25 Grocery Voucher', 'Redeem a small grocery voucher from participating partners.', 100, TRUE),
('Reusable Shopping Bag', 'Claim a durable ReLoop-branded reusable bag.', 80, TRUE),
('R50 Airtime Voucher', 'Redeem airtime after earning enough recycling points.', 180, TRUE);

INSERT INTO pickups (userID, agentID, scheduledDate, scheduledTime, wasteType, address, status) VALUES
(2, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '10:00:00', 'Plastic', '24 Green Street, Johannesburg', 'confirmed'),
(2, 1, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '09:30:00', 'Paper and Cardboard', '24 Green Street, Johannesburg', 'completed');

INSERT INTO waste_log (userID, pickupID, wasteCategoryID, weightKg) VALUES
(2, 2, 1, 7.50);
