CREATE TABLE income (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(100),
    amount DECIMAL(10,2) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT
);

CREATE TABLE expense (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(100),
    amount DECIMAL(10,2) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT
);

INSERT INTO income (type, amount, date, description)
                    VALUES ('$type', '$amount', '$date', '$desc')

INSERT INTO expense (type, amount, date, description)
                    VALUES ('$type', '$amount', '$date', '$desc')

SELECT 'income' AS mode, id,type, amount, date, description
                        FROM income
                        UNION ALL
                        SELECT 'expense' AS mode, id,type, amount, date, description
                        FROM expense
                        ORDER BY id;

DELETE FROM $mode WHERE id = $id

UPDATE $mode SET type = ?, amount = ?, description = ?, date = ? WHERE id = ?

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, email, password)
                        VALUES (?, ?, ?)