# NUNDÌA – Custom WordPress Theme

A modern, high-performance, and responsive custom WordPress theme designed and built for **NUNDÌA** (Sicilian Dolce & Gastronomy). Featuring modern typography, smooth micro-interactions, native Tailwind CSS (v4) build pipeline, WooCommerce integration, and flexible Advanced Custom Fields (ACF) architecture.

---

## 🌟 Key Features

- **Tailwind CSS v4 Pipeline:** Built-in modern build system with zero-dependency CSS bundle and hot-reload watch mode.
- **ACF JSON Synchronization:** Custom field groups sync automatically across environments using the `acf-json/` directory.
- **Custom ACF Gutenberg Blocks:** Pre-built modular blocks (hero slider, promo banner, testimonials, speciality showcase, etc.).
- **Dynamic Content Safeguards:** Empty fields do not render empty HTML elements; completely blank sections hide cleanly.
- **WooCommerce Ready:** Optimized shop, single product, cart, and checkout template overrides.
- **Ultra-Smooth Header:** Adaptive scroll header with smooth logo crossfade and height scaling.

---

## 📋 Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 8.0 or higher (PHP 8.2 recommended)
- **Node.js & npm:** (Optional, only needed if modifying Tailwind styles)
- **Recommended Plugins:**
  - [Advanced Custom Fields PRO (ACF Pro)](https://www.advancedcustomfields.com/pro/)
  - [WooCommerce](https://woocommerce.com/)

---

## 🚀 Installation & Setup

### Method 1: Git Clone (Recommended for Developers)

1. Open your terminal and navigate to your WordPress themes directory:
   ```bash
   cd wp-content/themes
   ```

2. Clone this repository into a folder named `nundia`:
   ```bash
   git clone https://github.com/afzalbare/nundia-theme.git nundia
   ```

---

### Method 2: Manual ZIP Download

1. Click on the green **Code** button at the top of this repository and select **Download ZIP**.
2. Extract the downloaded archive into your WordPress site's themes directory:
   `wp-content/themes/nundia/`
   *(Ensure the parent theme folder is named `nundia`)*.

---

## ⚙️ Post-Installation Activation

1. **Activate the Theme:**
   - Log in to your WordPress Admin Dashboard.
   - Go to **Appearance > Themes**.
   - Locate **NUNDÌA** and click **Activate**.

2. **Sync ACF Field Groups:**
   - Go to **Custom Fields > Field Groups**.
   - If prompted with **"Sync available"**, click **Sync** to automatically import all required custom field definitions into your database.

3. **Permalinks:**
   - Go to **Settings > Permalinks** and click **Save Changes** once to flush rewrite rules.

---

## 🛠️ Tailwind CSS Development Workflow

The production-ready minified CSS is already compiled and included (`_/css/nundia.min.css`), meaning the theme **works immediately out of the box** without running npm.

If you want to edit Tailwind styles or customize the theme CSS:

1. Navigate to the asset and build directory:
   ```bash
   cd wp-content/themes/nundia/_
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Run the development watcher (monitors PHP and CSS files for changes):
   ```bash
   npm run watch
   ```

4. Or generate an optimized production build:
   ```bash
   npm run build
   ```

---

## 📁 Repository Structure

```text
nundia/
├── _/                      # Tailwind pipeline, build config, scripts, images & helper functions
│   ├── css/               # Output stylesheet (nundia.min.css) & custom overrides (custom.css)
│   ├── inc/               # Core theme logic, ACF field definitions, navigation & Gutenberg setup
│   ├── src/               # Tailwind v4 source entry file (input.css)
│   ├── package.json       # Build scripts and npm dependencies
│   └── tailwind.config.js # Tailwind CSS configuration
├── acf-json/              # Synced JSON configurations for ACF field groups
├── blocks/                # Custom ACF Gutenberg blocks (block.json + PHP templates)
├── layout-modules/        # Modular page layout components
├── page-templates/        # Specialized page templates (Chi siamo, B2B, Contatti, FAQ)
├── woocommerce/           # WooCommerce custom layout overrides
├── functions.php          # Theme setup, asset enqueuing & hooks
├── style.css              # WordPress theme metadata header
└── README.md              # Theme documentation
```

---

## 👨‍💻 Author & Developer Credits

This custom WordPress theme was developed and architected by:

- **Lead WordPress Developer:** **Afzal**
- **GitHub Profile:** [@afzalbare](https://github.com/afzalbare)
- **Repository:** [https://github.com/afzalbare/nundia-theme](https://github.com/afzalbare/nundia-theme)

All architecture, custom block development, responsive layouts, dynamic ACF integrations, and Tailwind styling are crafted by **Afzal**.

---

## 📄 License & Copyright

Copyright © 2026 **Afzal**. Developed for **NUNDÌA**. All rights reserved.
