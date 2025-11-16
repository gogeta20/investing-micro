use investing_micro_db;

CREATE TABLE daily_summary (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stock_id INT NOT NULL,
    price_open DECIMAL(10,2) NOT NULL,
    price_close DECIMAL(10,2) NOT NULL,
    change_percent DECIMAL(6,2) NOT NULL,
    trend ENUM('up', 'down', 'neutral') NOT NULL,
    recorded_day DATE NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_stock_day (stock_id, recorded_day),
    INDEX idx_recorded_day (recorded_day),
    CONSTRAINT fk_daily_summary_stock FOREIGN KEY (stock_id) REFERENCES stocks(id)
        ON DELETE CASCADE
);
