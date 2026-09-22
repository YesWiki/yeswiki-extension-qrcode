# Extension qrcode

Générateur de QR codes pour les pages du wiki, et application QrcodeTroc pour
échanger des contacts par scan.

## Générer un QR code

Dans une page, en mode édition :

```
{{qrcode text="le texte du qrcode"}}
```

| Paramètre | Rôle |
|---|---|
| `text` | le contenu encodé dans le QR code |

## QrcodeTroc

L'application sert à imprimer des badges portant un QR code, à scanner ceux des
autres, et à visualiser les rencontres qui en résultent.

Trois entrées sont ajoutées automatiquement au menu de la roue crantée :

| Entrée | Rôle |
|---|---|
| liste des badges | la planche de badges à imprimer, QR codes inclus |
| scanner | ouvre la caméra pour lire un QR code |
| visualisation | le graphe des liens créés par les scans |

Rien à ajouter dans vos pages : l'extension se branche seule sur le menu.

## Configuration

| Clé de `wakka.config.php` | Rôle |
|---|---|
| `qrcode_config` | réglages de l'application QrcodeTroc |
