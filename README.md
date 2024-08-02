# AsiaYo - Mid Backend Engineer

## 環境運行

```bash
docker compose up -d
```

執行後預設路徑為 `http://localhost/api/orders`

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

- 確保 JOIN 的關聯是從小表關聯到大表可降低 I/O 次數加快查詢速度

- 使用 EXPLAIN 查看執行計畫
    
    在不做其他操作的情況 table `orders` 會進行 full table scan，且沒有使用 index，因此可以考慮新增 index 以提高查詢效率

- 新增 index
    
    根據搜索條件的順序建立 index 以提高查詢效率，建議越常使用的欄位越應該放在前面
    ```sql
    CREATE INDEX idx_orders_created_at_currency ON orders(created_at, currency, bnb_id);
    ```

- 因結果資料類似報表，可以考慮以排程方式將結果資料存入另一張表並建立 index，將耗時的查詢先執行完成並存入表中，後續查詢直接查詢結果即可
    - 缺點：
        - 資料會有延遲，不適合即時性要求高的查詢
        - 需要額外的空間存放結果資料
    

## API 實作測驗

### SOLID 原則

1. Single Responsibility Principle (SRP)
    - 一個類別只負責一個功能，不要做太多事情
    - `OrderService` 負責處理訂單相關的業務邏輯，不應該處理其他業務邏輯
    - `CurrencyPriceTransformer` 負責轉換幣值，不應該處理其他業務邏輯
2. Open/Closed Principle (OCP)
    - 一個類別應該是開放擴展各種新的需求，但是封閉修改原有的邏輯，以避免影響原有程式碼
    - `CurrencyPriceTransformer` 可透過增加新的轉換率設定來增加轉換幣值的功能，而不需要修改原有的程式碼
        ```php
        const EXCHANGE_RATE = [
            'USD' => [
                'TWD' => 31,
            ],
        ];
        ```
3. Liskov Substitution Principle (LSP)
    - 子類別應該可以替換父類別並且不會影響程式的功能
    - `ValidateFormatRequest` 透過繼承 `FormRequest` 依然能實現他原有的功能的功能
4. Interface Segregation Principle (ISP)
    - `CurrencyPriceTransformer` 的 `currency只單純實現轉換幣值的功能，不會實作過多的參數或多餘功能

### 設計模式

1. Dependency Injection
    - 透過 Laravel 本身的 DI 容器管理物件的生命週期，並解決物件之間的依賴關係，降低耦合性，提高可測試性
    - `OrderService` 依賴 `CurrencyPriceTransformer`，透過 DI 容器注入 `OrderService`
