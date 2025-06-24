<?php

namespace OrderManagementApi\Database;

class DatabaseConfig
{
    /**
     * Načte konfiguraci databáze z prostředí ($_ENV).
     *
     * @return array Pole s konfigurací obsahující klíče:
     *               - host: adresa databázového serveru
     *               - name: název databáze
     *               - user: uživatelské jméno
     *               - pass: heslo
     */
    public static function load(): array
    {
        return [
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASS'],
        ];
    }
}