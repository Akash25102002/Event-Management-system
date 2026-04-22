CREATE DATABASE IF NOT EXISTS event_management;
USE event_management;

CREATE TABLE memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_name VARCHAR(50) NOT NULL,
    duration_months INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO memberships (plan_name, duration_months, price) VALUES
('6 Months', 6, 999.00),
('1 Year', 12, 1799.00),
('2 Years', 24, 2999.00);

CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    category ENUM('Catering','Florist','Decoration','Lighting') NOT NULL,
    membership_id INT,
    membership_start DATE,
    membership_end DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (membership_id) REFERENCES memberships(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- password is: Admin
INSERT INTO admins (username, password) VALUES
('Admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_image VARCHAR(255),
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    payment_method ENUM('Cash','UPI') NOT NULL,
    grand_total DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending','Received','Ready for Shipping','Out For Delivery','Delivered') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    vendor_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE item_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    user_id INT NOT NULL,
    product_id INT,
    request_note TEXT,
    status ENUM('Pending','Accepted','Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE guest_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    guest_name VARCHAR(100) NOT NULL,
    guest_email VARCHAR(150),
    guest_phone VARCHAR(20),
    status ENUM('Invited','Confirmed','Declined') DEFAULT 'Invited',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
