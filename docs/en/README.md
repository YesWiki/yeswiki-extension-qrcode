# qrcode extension

QR code generator for wiki pages, and the QrcodeTroc application for swapping contacts
by scanning.

## Generating a QR code

In a page, while editing:

```
{{qrcode text="the qrcode text"}}
```

| Parameter | Purpose |
|---|---|
| `text` | the content encoded in the QR code |

## QrcodeTroc

The application prints badges carrying a QR code, scans other people's, and shows the
encounters that result.

Three entries are added to the cog wheel menu automatically:

| Entry | Purpose |
|---|---|
| badge list | the printable badge sheet, QR codes included |
| scanner | opens the camera to read a QR code |
| visualisation | the graph of links created by the scans |

Nothing to add to your pages: the extension hooks into the menu on its own.

## Configuration

| `wakka.config.php` key | Purpose |
|---|---|
| `qrcode_config` | QrcodeTroc application settings |
