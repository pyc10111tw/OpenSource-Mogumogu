# Admin Guide

## Configuration

Application settings are stored in `private/db.php`.

Modify this file if any of the following change:

- Database host
- Username
- Password
- Database name

## Maintenance

### Update the Application

```bash
git pull
```

### Restore Contributor Information

```bash
php private/member_setup.php
```

---

For installation, deployment, and troubleshooting instructions, see [Installation.md](Installation.md).