# order-management-api
Testovací PHP projekt demonstrující architekturu MVC s repozitářovým vzorem. Aplikace obsahuje OrderController, repozitáře (včetně in-memory repository) a modely pro práci s objednávkami. Používá čisté PHP bez frameworku.

## Požadavky  

- PHP >= 8.1 (s rozšířeními `ext-json`, `ext-pdo`)  
- MySQL (pokud používáte `DATA_SOURCE=db`)  
- Composer  

## Instalace  

1. Naklonujte repozitář:  

   bash
   git clone https://github.com/uzivatel/order-management-api.git  
   cd order-management-api  

2.Nainstalujte závislosti:  
   
   bash  
   composer install  

3.Zkopírujte konfigurační soubor a doplňte údaje:  

  bash
  cp .env.sample .env  

  Upravte .env podle svého prostředí:    
  DATA_SOURCE=db  
  DB_HOST=localhost  
  DB_NAME=order-management  
  DB_USER=root  
  DB_PASS=heslo  
  
4.(Volitelné) Vytvořte databázové tabulky (např. pomocí těchto SQL příkazů):  

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

##Poznámka
Soubor .env obsahuje citlivé údaje (např. hesla k databázi) a není verzován (je uveden v .gitignore).  

Repozitář obsahuje soubor .env.sample jako vzor. Před spuštěním projektu si vytvořte vlastní .env:
