# order-management-api
Testovací PHP projekt demonstrující architekturu MVC s repozitářovým vzorem. Aplikace obsahuje OrderController, repozitáře (včetně in-memory repository) a modely pro práci s objednávkami. Používá čisté PHP bez frameworku.

## Struktura projektu
- `src/Controller` – kontrolery (OrderController)
- `src/Repository` – repozitáře a rozhraní pro datové zdroje
- `src/Model` – modely objednávek a položek objednávek
- `src/Database` – správa připojení k databázi a databázových služeb
- `public/` – vstupní bod aplikace (index.php)
- `bootstrap.php` – inicializační soubor aplikace

## Požadavky  

- PHP >= 8.1 (s rozšířeními `ext-json`, `ext-pdo`)  
- MySQL (pokud používáte `DATA_SOURCE=db`)  
- Composer  

## Endpoints
- `GET /orders` – seznam všech objednávek
- `GET /orders/{id}` – detail objednávky

## Návratové kódy
- `200 OK` – úspěch
- `404 Not Found` – objednávka nenalezena
- `500 Internal Server Error` – chyba při načítání dat

## Instalace  

### 1. Naklonujte repozitář:  

   bash
   git clone https://github.com/uzivatel/order-management-api.git  
   cd order-management-api  

### 2.Nainstalujte závislosti:  
   
   bash  
   composer install  

### 3.Zkopírujte konfigurační soubor a doplňte údaje:  

  bash
  cp .env.sample .env  

  Upravte .env podle svého prostředí:    
  DATA_SOURCE=db  
  DB_HOST=localhost  
  DB_NAME=order-management  
  DB_USER=root  
  DB_PASS=heslo  
  
### 4.(Volitelné) Vytvořte databázové tabulky (např. pomocí těchto SQL příkazů):  

sql
CREATE TABLE orders (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    customer_name VARCHAR(255) NOT NULL,  
    total DECIMAL(10, 2) NOT NULL  
);  

CREATE TABLE order_items (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    order_id INT NOT NULL,  
    product_name VARCHAR(255) NOT NULL,  
    quantity INT NOT NULL,  
    price DECIMAL(10, 2) NOT NULL,  
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE  
);  

## Spuštění
Projekt lze spustit na lokálním serveru PHP:

```bash
php -S localhost:8000 -t public
```

## Testování

Projekt obsahuje základní jednotkové testy (PHPUnit) pro repozitáře a kontroler OrderController.

## Poznámka
Soubor .env obsahuje citlivé údaje (např. hesla k databázi) a není verzován (je uveden v .gitignore).  

Repozitář obsahuje soubor .env.sample jako vzor. Před spuštěním projektu si vytvořte vlastní .env:

Aplikace podporuje více datových zdrojů:
- InMemory (default)
- MySQL (pokud je nakonfigurováno)
- API mock (pro ukázku)
