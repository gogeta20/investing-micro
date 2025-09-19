use investing_micro_db;
-- Crear un portfolio
INSERT INTO portfolios (uid, name, description)
VALUES (UUID(), 'Estables', 'Acciones de crecimiento estable');

-- Obtener el ID del portfolio recién creado
SELECT id FROM portfolios WHERE name = 'Estables';

-- Supongamos que devuelve id = 1
-- Relacionar acciones (stock_id) con el portfolio
INSERT INTO portfolio_stocks (portfolio_id, stock_id)
SELECT 1, id FROM stocks WHERE symbol IN ('AAPL', 'MSFT', 'GOOGL', 'AMZN', 'META');

-- Crear snapshots de prueba para esas acciones
INSERT INTO stock_prices (uid, stock_id, price, recorded_at)
SELECT UUID(), id, 100 + FLOOR(RAND()*50), NOW()
FROM stocks WHERE symbol IN ('AAPL', 'MSFT', 'GOOGL', 'AMZN', 'META');
