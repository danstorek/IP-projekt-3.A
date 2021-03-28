# Jednoduchý prohlížeč a editor databáze pro firmy

## Fuknce
- prohlížení a úprava položek v databázi (úprava dostupná pouze pro administrátory)
- možnost změny hesla pro každého uživatele

## Nasazení na server
1) Nahrát soubory na Apache server.
2) V souboru "/inc/dbConnect.php" nastavit přihlašovací údaje databáze, popřípadě IP adresu.
3) Do databáze naimportovat soubor "ip3_dump.sql". Zde je potřeba změnit na řádku 11 a 14 'ip_3' na název Vaší databáze.
4) Do tabulky employee je dále potřeba přidat sloupce "login" (VARCHAR), "password" (VARCHAR) a "admin" (INT). Sloupec "password" musí obsahovat již zahashované heslo algoritmem BCRYPT.
5) Nyní by mělo být vše připraveno k používání.

