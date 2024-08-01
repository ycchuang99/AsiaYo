# AsiaYo - Mid Backend Engineer

## 環境運行

```bash
docker compose up -d
```

## 資料庫測驗

1. 請寫出一條查詢語句 (SQL),列出在 2023 年 5 月下訂的訂單,使用台幣付款且5月總金額最
多的前 10 筆的旅宿 ID (bnb_id), 旅宿名稱 (bnb_name), 5 月總金額 (may_amount)

    ```sql
    SELECT
        b.id AS bnb_id,
        b.name AS bnb_name,
        SUM(o.amount) AS may_amount
    FROM bnbs AS b
    INNER JOIN orders AS o ON b.id = o.bnb_id
    WHERE o.created_at BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59'
    AND o.currency = 'TWD'
    GROUP BY o.bnb_id
    ORDER BY may_amount DESC
    LIMIT 10;
    ```

2. 在題目一的執行下,我們發現 SQL 執行速度很慢,您會怎麼去優化?請闡述您怎麼判斷與優
化的方式

- 使用 EXPLAIN 查看執行計畫
    
    在不做其他操作的情況 table `orders` 會進行 full table scan，且沒有使用 index，因此可以考慮新增 index 以提高查詢效率

- 新增 index
    
    根據搜索條件的順序建立 index 以提高查詢效率，建議越常使用的欄位越應該放在前面
    ```sql
    CREATE INDEX idx_orders_created_at_currency ON orders(created_at, currency, bnb_id);
    ```

## API 實作測驗

### SOLID 原則

1. Single Responsibility Principle (SRP)
    - 一個類別只負責一個功能，不要做太多事情
    - 例如：`OrderService` 負責處理訂單相關的業務邏輯，不應該處理其他業務邏輯
    - 例如：`CurrencyPriceTransformer` 負責轉換幣值，不應該處理其他業務邏輯
2. Open/Closed Principle (OCP)
    - 一個類別應該是開放擴展，但是封閉修改
3. Liskov Substitution Principle (LSP)
    - 子類別應該可以替換父類別
4. Interface Segregation Principle (ISP)
    - 一個類別不應該實作不需要的方法
5. Dependency Inversion Principle (DIP)
    - 高層模組不應該依賴低層模組，兩者都應該依賴抽象
