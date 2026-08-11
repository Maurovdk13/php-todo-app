# Currency App

Een pure PHP schoolproject waarmee gebruikers virtuele XD tokens kunnen sturen en ontvangen.

## Belangrijkste onderdelen

- Registratie met `@student.thomasmore.be` email
- Wachtwoorden met `password_hash()` en `password_verify()`
- Login/logout met PHP sessions
- PDO database connectie
- Prepared statements tegen SQL injection
- `htmlspecialchars()` tegen XSS
- Startsalo van 10.00 XD bij registratie
- Veilige transfers met `beginTransaction()`, `commit()` en `rollBack()`
- AJAX user autocomplete met `fetch()`
- AJAX balance polling elke 10 seconden
- Transactieoverzicht en detailpagina

## Lokale setup

1. Start Apache en MySQL in XAMPP.
2. Maak de database aan met `database.sql`.
3. Open de app via:

```text
http://localhost/currency-app/
```

## Database

De app gebruikt minimaal deze tabellen:

- `users`
- `transactions`
