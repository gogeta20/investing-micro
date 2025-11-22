USE investing_db;

-- 1. Ampliar stock_prices con OHLCV (datos RAW)
ALTER TABLE stock_prices
ADD COLUMN open DECIMAL(10,2) NULL AFTER price,
ADD COLUMN high DECIMAL(10,2) NULL AFTER open,
ADD COLUMN low DECIMAL(10,2) NULL AFTER high,
ADD COLUMN close DECIMAL(10,2) NULL AFTER low,
ADD COLUMN volume BIGINT NULL AFTER close;

-- Migrar datos existentes: price actual → close
UPDATE stock_prices SET close = price WHERE close IS NULL;

-- 2. Mejorar valuations con tracking temporal
ALTER TABLE valuations
ADD COLUMN calculated_at DATE NOT NULL DEFAULT (CURRENT_DATE) AFTER stock_id,
ADD COLUMN is_current BOOLEAN DEFAULT TRUE AFTER discount_percent;

-- 3. Mejorar risk_scores con tracking temporal
ALTER TABLE risk_scores
ADD COLUMN calculated_at DATE NOT NULL DEFAULT (CURRENT_DATE) AFTER stock_id,
ADD COLUMN period_days INT DEFAULT 365 COMMENT 'Período analizado en días' AFTER risk_level;

-- 4. Índice útil para news_cache
ALTER TABLE news_cache
ADD INDEX idx_stock_published (stock_id, published_at DESC);
