-- cst_schema.sql
DROP DATABASE IF EXISTS cst_db;
CREATE DATABASE cst_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cst_db;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE chamas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  frequency ENUM('weekly','monthly','custom') DEFAULT 'monthly',
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  chama_id INT NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(150),
  joined_date DATE,
  user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (chama_id) REFERENCES chamas(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE contributions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  chama_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  contribution_date DATE NOT NULL,
  recorded_by INT,
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
  FOREIGN KEY (chama_id) REFERENCES chamas(id) ON DELETE CASCADE,
  FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT,
  chama_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  type ENUM('loan','withdrawal','fine','adjustment') NOT NULL,
  transaction_date DATE NOT NULL,
  recorded_by INT,
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
  FOREIGN KEY (chama_id) REFERENCES chamas(id) ON DELETE CASCADE,
  FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert roles
INSERT INTO roles (name) VALUES ('admin'), ('member');
