# Date range

## Installation

Add github repository to composer.json
```json
{
   "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:gusarov112/date-range.git"
        }
    ]
}
```
Than require package
```bash
composer require gusarov112/date-range
```

## Usage

```php
$dateRange = new \Gusarov112\DateRange\DateRange(
    new DateTimeImmutable('first day of this month 00:00:00'),
    new DateTimeImmutable('last day of this month 23:59:59')
);
```
