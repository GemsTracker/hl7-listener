# Migrations
[Phinx](https://book.cakephp.org/phinx) is used for Database migrations 


## Usage

All commands can be done with `php vendor/bin/phinx`

### Status check
```bash
php vendor/bin/phinx status
```

### Migrate
```bash
php vendor/bin/phinx migrate
```

### Migrate dry-run
Queries will be shown but not run

```bash
php vendor/bin/phinx migrate
```

### Rollback

rollback the last migration, or to a specific migration

```bash
php vendor/bin/phinx rollback
```

add the -d parameter to rollback to a specific date
```bash
php vendor/bin/phinx rollback -d 20220622
```

### Create migration

```bash
php vendor/bin/phinx create
```

### Create seed
```bash
php vendor/bin/phinx seed:create
```

### Run seeds
```bash
php vendor/bin/phinx seed:run
```

### Run a specific seed 
```bash
php vendor/bin/phinx seed:run -s MySeedName
```
