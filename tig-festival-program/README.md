# TIG Festival Program

> WordPress plugin fesztiválok és rendezvények programjának megjelenítéséhez  
> WordPress plugin for displaying festival and event schedules

---

## 🇭🇺 Magyar

### Mi ez?

A **TIG Festival Program** egy WordPress shortcode plugin, amely fesztiválok, családi napok és más rendezvények napi programját jeleníti meg interaktív táblázatban — helyszínenként oszlopokba rendezve, mobilon kártyás nézetben.

### Telepítés

1. Töltsd le vagy klónozd a repót
2. Másold a `tig-festival-program/` mappát a WordPress `wp-content/plugins/` könyvtárába
3. Aktiváld a plugint a WordPress adminban: **Bővítmények → Telepített bővítmények**

### Rendszerkövetelmények

- WordPress 5.8+
- PHP 7.4+

### Shortcode használata

Illeszd be az alábbi shortcode-ot bármely oldalra vagy bejegyzésbe:

```
[tig_program]
```

**Elérhető attribútumok:**

| Attribútum | Leírás | Példa |
|------------|--------|-------|
| `title` | Főcím a program fejlécében | `title="I. TIG Fesztivál"` |
| `subtitle` | Alcím (pl. helyszín neve) | `subtitle="Budapest · Program"` |
| `display_date` | Megjelenítendő dátum (YYYY-MM-DD) | `display_date="2026-06-14"` |
| `date` | Csak adott nap megjelenítése | `date="2026-06-14"` |

**Teljes példa:**
```
[tig_program title="I. TIG Fesztivál" subtitle="Budapest · Városliget" display_date="2026-06-14"]
```

### Admin felület

A program szerkesztője a WordPress adminban érhető el: **TIG Program → Szerkesztő**

Az adminban a következőket tudod beállítani:

- **Helyszínek** — név, szín, szövegszín (pl. Főszínpad, Workshop, Gyerekzóna)
- **Tagek / Típusok** — programpont kategóriák (pl. Fő program, Workshop, Folyamatos)
- **Menetrend** — időpontok, programpontok, helyszín hozzárendelés, végidő, leírás, link, kép URL

#### Toolbar gombok

| Gomb | Funkció |
|------|---------|
| **Program mentése** | Elmenti a teljes programot |
| **↺ Visszavonás** | Visszaállítja az előző mentett állapotot (csak akkor látható, ha van visszavonható mentés) |
| **↓ Exportálás (JSON)** | Letölti a teljes programot JSON fájlként |
| **↑ Importálás (JSON)** | JSON fájlból betölti a programot (felülírja a jelenlegit) |
| **Összes adat törlése** | Törli az összes helyszínt, típust és programpontot |

### Frontenden elérhető funkciók

- **Füles napi navigáció** — több nap esetén automatikusan megjelenik
- **▶ Most gomb** — az aktuális időpontra ugrik és kiemeli azt
- **Helyszín szűrő** — checkbox chipekkel ki/bekapcsolható helyszínek
- **Kattintható esemény modal** — részletes leírás, kép, link megjelenítése
- **Tooltip** — helyszín neve megjelenik az egér ráhúzásakor

### REST API

A program adatai publikusan lekérhetők:

```
GET /wp-json/tig-festival/v1/program          → teljes adatkészlet
GET /wp-json/tig-festival/v1/days             → napok + menetrend
GET /wp-json/tig-festival/v1/days?date=YYYY-MM-DD  → adott nap
GET /wp-json/tig-festival/v1/venues           → helyszínek
```

### Fejlesztők számára

Ha a shortcode Widget-ben vagy Gutenberg blokkban van és a CSS/JS nem töltődik be, add hozzá ezt a `functions.php`-hoz:

```php
add_filter('tig_festival_program_force_enqueue', '__return_true');
```

---

## 🇬🇧 English

### What is this?

**TIG Festival Program** is a WordPress shortcode plugin that displays festival, family day, and event schedules in an interactive timetable — organised by venue in columns on desktop, and as cards on mobile.

### Installation

1. Download or clone this repository
2. Copy the `tig-festival-program/` folder into your WordPress `wp-content/plugins/` directory
3. Activate the plugin in the WordPress admin: **Plugins → Installed Plugins**

### Requirements

- WordPress 5.8+
- PHP 7.4+

### Shortcode usage

Insert the shortcode into any page or post:

```
[tig_program]
```

**Available attributes:**

| Attribute | Description | Example |
|-----------|-------------|---------|
| `title` | Main heading in the program header | `title="TIG Festival I."` |
| `subtitle` | Subtitle (e.g. venue name or city) | `subtitle="Budapest · Program"` |
| `display_date` | Date to display in the header (YYYY-MM-DD) | `display_date="2026-06-14"` |
| `date` | Show only a specific day | `date="2026-06-14"` |

**Full example:**
```
[tig_program title="TIG Festival I." subtitle="Budapest · City Park" display_date="2026-06-14"]
```

### Admin interface

The schedule editor is available in the WordPress admin: **TIG Program → Editor**

You can configure:

- **Venues** — name, background colour, text colour (e.g. Main Stage, Workshop, Kids Zone)
- **Tags / Types** — event categories (e.g. Main Event, Workshop, Ongoing)
- **Schedule** — time slots, events, venue assignment, end time, description, link, image URL

#### Toolbar buttons

| Button | Function |
|--------|----------|
| **Save program** | Saves the entire schedule |
| **↺ Undo** | Restores the previously saved state (only visible when a backup exists) |
| **↓ Export (JSON)** | Downloads the full schedule as a JSON file |
| **↑ Import (JSON)** | Loads a schedule from a JSON file (overwrites current data) |
| **Delete all data** | Clears all venues, types, and schedule entries |

### Frontend features

- **Day tabs** — automatically shown when multiple days are configured
- **▶ Now button** — scrolls to and highlights the current time slot
- **Venue filter** — toggle venue columns on/off with checkbox chips
- **Clickable event modal** — shows description, image, and link for each event
- **Tooltip** — venue name appears on hover

### REST API

Schedule data is publicly available:

```
GET /wp-json/tig-festival/v1/program          → full dataset
GET /wp-json/tig-festival/v1/days             → days + schedule
GET /wp-json/tig-festival/v1/days?date=YYYY-MM-DD  → specific day
GET /wp-json/tig-festival/v1/venues           → venues
```

### For developers

If the shortcode is used inside a Widget or Gutenberg block and the CSS/JS is not loading, add this to your theme's `functions.php`:

```php
add_filter('tig_festival_program_force_enqueue', '__return_true');
```

---

## Changelog

| Version | Changes |
|---------|---------|
| 0.7.6 | Undo / restore previous save |
| 0.7.5 | REST API endpoints |
| 0.7.4 | CSS/JS loaded only on pages with shortcode |
| 0.7.3 | Event modal (description, link, image), venue filter |
| 0.7.2 | JSON export/import |
| 0.7.1 | `display_date` shortcode attribute |
| 0.7.0 | Multi-day support with tab navigation, current-row highlight |
| 0.6.0 | `title`/`subtitle` shortcode attributes, admin reset |
| 0.5.0 | Initial release |

## License

MIT
