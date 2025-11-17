CREATE DATABASE investing_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE investing_db;

CREATE TABLE stocks (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL,
    symbol VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    sector VARCHAR(255),
    currency CHAR(3) DEFAULT 'USD',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stock_prices (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    recorded_at DATETIME NOT NULL,
    INDEX idx_stock_date (stock_id, recorded_at),
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);


CREATE TABLE portfolios (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE portfolio_stocks (
    portfolio_id BIGINT NOT NULL,
    stock_id BIGINT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (portfolio_id, stock_id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);

CREATE TABLE notes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL,
    stock_id BIGINT,
    portfolio_id BIGINT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE SET NULL,
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE SET NULL
);

CREATE TABLE daily_summary (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    price_open DECIMAL(10,2) NOT NULL,
    price_close DECIMAL(10,2) NOT NULL,
    change_percent DECIMAL(6,2) NOT NULL,
    trend ENUM('up','down','neutral') NOT NULL,
    recorded_day DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_daily (stock_id, recorded_day),
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);

CREATE TABLE company_fundamentals (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    year INT NOT NULL,
    revenue BIGINT,
    net_income BIGINT,
    fcf BIGINT,
    operating_margin DECIMAL(6,2),
    total_debt BIGINT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fundamental (stock_id, year),
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);

CREATE TABLE valuations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    method ENUM('dcf','graham') NOT NULL,
    intrinsic_value DECIMAL(10,2) NOT NULL,
    price_at_valuation DECIMAL(10,2) NOT NULL,
    discount_percent DECIMAL(6,2) NOT NULL,
    inputs_json JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);

CREATE TABLE risk_scores (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    risk_score DECIMAL(4,3),
    risk_level ENUM('low','medium','high'),
    volatility DECIMAL(6,3),
    max_drawdown DECIMAL(6,3),
    beta DECIMAL(6,3),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);

CREATE TABLE news_cache (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id BIGINT NOT NULL,
    source VARCHAR(255),
    title VARCHAR(500),
    url VARCHAR(500),
    summary TEXT,
    published_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stock_id) REFERENCES stocks(id) ON DELETE CASCADE
);
