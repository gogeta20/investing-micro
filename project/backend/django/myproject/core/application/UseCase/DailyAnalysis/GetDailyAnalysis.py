from myproject.core.application.UseCase.DailyAnalysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery

class GetDailyAnalysis:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service

    def execute(self, query):
        sql = """
        WITH
            valid_days AS (
                SELECT DATE(recorded_at) AS d
                FROM stock_prices
                GROUP BY DATE(recorded_at)
                HAVING COUNT(*) > 0
                ORDER BY d DESC
                LIMIT 2
            ),
            days AS (
                SELECT
                    MAX(d) AS today_day,
                    MIN(d) AS yesterday_day
                FROM valid_days
            ),
            today_snap AS (
                SELECT
                    sp.stock_id,
                    sp.price,
                    sp.recorded_at,
                    ROW_NUMBER() OVER (PARTITION BY sp.stock_id ORDER BY sp.recorded_at DESC) AS rn
                FROM stock_prices sp
                WHERE DATE(sp.recorded_at) = (SELECT today_day FROM days)
            ),
            yesterday_snap AS (
                SELECT
                    sp.stock_id,
                    sp.price,
                    sp.recorded_at,
                    ROW_NUMBER() OVER (PARTITION BY sp.stock_id ORDER BY sp.recorded_at DESC) AS rn
                FROM stock_prices sp
                WHERE DATE(sp.recorded_at) = (SELECT yesterday_day FROM days)
            )
        SELECT
            t.stock_id,
            s.symbol,
            s.name,
            t.price AS price_today,
            y.price AS price_yesterday,
            ROUND(((t.price - y.price) / y.price) * 100, 2) AS change_percent,
            CASE
                WHEN t.price > y.price THEN 'up'
                WHEN t.price < y.price THEN 'down'
                ELSE 'neutral'
            END AS trend,
            (SELECT today_day FROM days) AS today_date,
            (SELECT yesterday_day FROM days) AS yesterday_date
        FROM today_snap t
        LEFT JOIN yesterday_snap y
            ON y.stock_id = t.stock_id AND y.rn = 1
        LEFT JOIN stocks s
            ON s.id = t.stock_id
        WHERE t.rn = 1;
        """

        rows = self.mysql_service.execute_query(sql)

        # Guardar en daily_summary
        upsert_sql = """
        INSERT INTO daily_summary (
            stock_id,
            price_open,
            price_close,
            change_percent,
            trend,
            recorded_day
        ) VALUES (
            %s, %s, %s, %s, %s, %s
        )
        ON DUPLICATE KEY UPDATE
            price_open = VALUES(price_open),
            price_close = VALUES(price_close),
            change_percent = VALUES(change_percent),
            trend = VALUES(trend),
            created_at = NOW();
        """

        for row in rows:
            self.mysql_service.execute_query_params(
                upsert_sql,
                (
                    row["stock_id"],
                    row["price_yesterday"],
                    row["price_today"],
                    row["change_percent"],
                    row["trend"],
                    row["today_date"],
                ),
            )

        return rows
