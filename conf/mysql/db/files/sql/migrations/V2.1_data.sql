USE investing_db;
INSERT INTO stocks (uid, symbol, name, sector, currency)
VALUES (UUID(), 'ORCL', 'Oracle Corporation', 'Technology', 'USD');

INSERT INTO stock_prices (stock_id, price, recorded_at)
VALUES
(1, 222.85, '2025-11-14 21:00:00'),
(1, 226.99, '2025-11-13 21:00:00');

INSERT INTO company_fundamentals (stock_id, year, revenue, net_income, fcf, operating_margin, total_debt)
VALUES
(1, 2024, 52000000000, 8400000000, 10200000000, 32.5, 78000000000);

INSERT INTO valuations (stock_id, method, intrinsic_value, price_at_valuation, discount_percent, inputs_json)
VALUES
(
  1,
  'dcf',
  285.00,
  222.85,
  21.80,
  '{"fcf":10200000000,"growth":0.06,"discount_rate":0.09,"terminal_rate":0.03}'
);

INSERT INTO risk_scores (stock_id, risk_score, risk_level, volatility, max_drawdown, beta)
VALUES
(1, 0.35, 'medium', 0.22, 0.18, 1.10);

INSERT INTO news_cache (stock_id, source, title, url, summary, published_at)
VALUES
(1, 'Reuters', 'Oracle anuncia expansión cloud', 'https://example.com', 'Oracle expande su infraestructura cloud en Europa.', '2025-11-14 10:00:00');

INSERT INTO daily_summary (stock_id, price_open, price_close, change_percent, trend, recorded_day)
VALUES
(1, 226.99, 222.85, -1.82, 'down', '2025-11-14');
