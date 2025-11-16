--
SELECT sp1.stock_id,
       sp1.price AS today_price,
       sp2.price AS yesterday_price
FROM stock_prices sp1
LEFT JOIN stock_prices sp2
       ON sp2.stock_id = sp1.stock_id
      AND DATE(sp2.recorded_at) = DATE(sp1.recorded_at) - INTERVAL 1 DAY
WHERE DATE(sp1.recorded_at) = CURDATE();


SELECT
    today.stock_id,
    today.price AS price_today,
    yesterday.price AS price_yesterday,
    ((today.price - yesterday.price) / yesterday.price) * 100 AS change_percent,
    CASE
        WHEN today.price > yesterday.price THEN 'up'
        WHEN today.price < yesterday.price THEN 'down'
        ELSE 'neutral'
    END AS trend
FROM stock_prices today
LEFT JOIN stock_prices yesterday
    ON yesterday.stock_id = today.stock_id
   AND DATE(yesterday.recorded_at) = DATE(today.recorded_at) - INTERVAL 1 DAY
WHERE DATE(today.recorded_at) = CURDATE();


DELETE FROM stock_prices
WHERE DATE(recorded_at) = '2025-11-15';

DELETE FROM daily_summary
WHERE recorded_day = '2025-11-15';
