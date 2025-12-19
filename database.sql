CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    company_name ENUM('VISA', 'Mastercard', 'Wafacash', 'CIB') NOT NULL,
    balance DECIMAL(10,2) NOT NULL,
    created_date DATE NOT NULL DEFAULT CURRENT_DATE,
    is_primary BOOLEAN NOT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    card_id INT,
    mode ENUM('income', 'expense', 'transfer') NOT NULL,
    category VARCHAR(255),
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    from_entity VARCHAR(50) NULL,
    to_entity VARCHAR(50) NULL,
    recipient_id INT NULL,
    recipient_email VARCHAR(255) NULL,
    date DATE,
    FOREIGN KEY (card_id) REFERENCES cards(id)
);

CREATE TABLE recc_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    card_id INT,
    mode ENUM('income', 'expense', 'transfer') NOT NULL,
    category VARCHAR(255),
    amount DECIMAL(10,2) NOT NULL,
    from_entity VARCHAR(50) NULL,
    start_date DATE,
    ADD recurrence VARCHAR(20) DEFAULT 'none',
    FOREIGN KEY (card_id) REFERENCES cards(id)
);
