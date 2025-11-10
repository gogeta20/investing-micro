-- CREATE DATABASE ${MYSQL_DATABASE} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE investing_micro_db;

CREATE TABLE stocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    symbol VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    sector VARCHAR(100),
    currency VARCHAR(10) DEFAULT 'USD',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stock_prices (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    stock_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    recorded_at DATETIME NOT NULL,
    FOREIGN KEY (stock_id) REFERENCES stocks(id)
);

CREATE TABLE stock_sessions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    stock_id INT NOT NULL,
    date DATE NOT NULL,
    open_price DECIMAL(10,2),
    close_price DECIMAL(10,2),
    high_price DECIMAL(10,2),
    low_price DECIMAL(10,2),
    volume BIGINT,
    FOREIGN KEY (stock_id) REFERENCES stocks(id),
    UNIQUE (stock_id, date)
);

CREATE TABLE notes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    stock_id INT NOT NULL,
    title VARCHAR(255),
    content TEXT NOT NULL,
    tags VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stock_id) REFERENCES stocks(id)
);

CREATE TABLE portfolios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE portfolio_stocks (
    portfolio_id INT NOT NULL,
    stock_id INT NOT NULL,
    PRIMARY KEY (portfolio_id, stock_id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id),
    FOREIGN KEY (stock_id) REFERENCES stocks(id)
);

CREATE TABLE portfolio_results (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    portfolio_id INT NOT NULL,
    date DATE NOT NULL,
    total_value DECIMAL(15,2) NOT NULL,
    variation_pct DECIMAL(6,2),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id),
    UNIQUE (portfolio_id, date)
);
