<?php
/**
 * Editable content registry.
 *
 * Every text/image on the site is defined ONCE here with its default value
 * (taken from the reference design). The same definitions are used to:
 *  - register the ACF fields shown in the WordPress admin, and
 *  - render the templates (falling back to the default while a field is empty).
 *
 * Admin locations:
 *  - "NUNDÌA Sito" options page  → global settings (brand, contacts, footer…)
 *  - Home page (front page)       → home sections + hero slides
 *  - Each page                    → "Intestazione pagina" (hero) + page sections
 */
if (!defined('ABSPATH')) exit;

/* ------------------------------------------------------------------ *
 * 1. Global settings (options page)
 * ------------------------------------------------------------------ */
function nundia_option_fields() {
    return array(
        'tab_header'        => array('Header', 'tab', ''),
        'header_logo_light' => array('Logo Header (Chiaro / Trasparente)', 'image', ''),
        'header_logo_dark'  => array('Logo Header (Scuro / Scroll)', 'image', ''),
        'header_cta_text'   => array('Pulsante Header – testo', 'text', 'Richiesta commerciale'),
        'header_cta_link'   => array('Pulsante Header – link', 'text', '/b2b/'),
        'phone'             => array('Telefono', 'text', '+39 095 7411949'),
        'mobile'            => array('WhatsApp (dall’estero)', 'text', '+39 342 588 7830'),
        'email_general'     => array('Email informazioni generali', 'text', 'info@nundia.it'),
        'tab_footer'        => array('Footer & Brand', 'tab', ''),
        'footer_logo'       => array('Logo Footer', 'image', ''),
        'brand_name'        => array('Nome brand', 'text', 'NUNDÌA'),
        'brand_slogan'      => array('Slogan (sotto il nome)', 'text', 'Sapori di Sicilia'),
        'brand_group'       => array('Gruppo (terza riga del logo)', 'text', 'Typhoeus Group'),
        'brand_intro'       => array('Descrizione breve (footer)', 'textarea', 'Selezioniamo specialità della tradizione siciliana e le presentiamo con un’attenzione contemporanea al dettaglio: ingredienti riconoscibili, ricette rispettate, confezioni pensate per essere donate.'),
        'email_orders'      => array('Email ordini e clienti commerciali', 'text', 'ordini@nundia.it'),
        'email_suppliers'   => array('Email fornitori', 'text', 'acquisti@nundia.it'),
        'address'           => array('Sede legale', 'text', 'Via Vito D\'Anna, 18 — 95024 Acireale (CT), Italia'),
        'vat'               => array('Partita IVA', 'text', '05757810873'),
        'instagram'         => array('URL Instagram', 'url', 'https://www.instagram.com/'),
        'facebook'          => array('URL Facebook', 'url', 'https://www.facebook.com/'),
        'linkedin'          => array('URL LinkedIn', 'url', 'https://www.linkedin.com/'),
        'payment_methods'   => array('Pagamenti accettati', 'text', 'Visa · Mastercard · American Express · Maestro · Postepay · PayPal'),
        'footer_legal'      => array('Footer – riga aziendale', 'text', 'NUNDÌA – Gruppo Typhoeus – Via Vito D’Anna, 18. 95024 – Acireale (CT) – Italia – P.IVA 05757810873'),
        'footer_note'       => array('Footer – nota (lascia vuoto per nasconderla)', 'text', 'Immagini e contenuti provvisori in attesa dei materiali ufficiali.'),
        'tab_shop'          => array('Note Shop & Spedizioni', 'tab', ''),
        'shipping_cost_it'        => array('Costo Spedizione Italia (€)', 'text', '6.90'),
        'shipping_cost_fr'        => array('Costo Spedizione Francia (€)', 'text', '12.00'),
        'shipping_cost_de'        => array('Costo Spedizione Germania (€)', 'text', '12.00'),
        'shipping_cost_eu'        => array('Costo Spedizione Altri Paesi UE (€)', 'text', '15.00'),
        'shipping_free_threshold' => array('Soglia Spedizione Gratuita (€) – 0 per disattivare', 'text', '50.00'),
        'payment_note'      => array('Nota metodo di pagamento (checkout)', 'textarea', 'Dopo la conferma riceverai via email le istruzioni per il bonifico bancario. Il pagamento con carta sarà attivo non appena collegato il provider.'),
        'shipping_note'     => array('Nota spedizioni (checkout)', 'textarea', 'Spediamo in tutti i paesi dell’Unione Europea con corriere espresso tracciato.'),
        'cart_note'         => array('Nota carrello', 'textarea', 'Imballaggio isotermico garantito e spedizione tracciata con corriere espresso.'),
        'tab_settings'      => array('Impostazioni Generali', 'tab', ''),
        'site_font'         => array('Carattere del sito', 'select', 'arial'),
        'show_draft_badges' => array('Mostra le etichette “Contenuto provvisorio — da confermare”', 'true_false', 1),
        'draft_label'       => array('Testo etichetta contenuto provvisorio', 'text', 'Contenuto provvisorio — da confermare'),
    );
}

/** Font choices for "Carattere del sito" (client: Arial or Calibri). */
function nundia_font_choices() {
    return array('arial' => 'Arial', 'calibri' => 'Calibri');
}
function nundia_font_stack($key) {
    return $key === 'calibri'
        ? 'Calibri, Arial, "Helvetica Neue", Helvetica, sans-serif'
        : 'Arial, "Helvetica Neue", Helvetica, sans-serif';
}

/* ------------------------------------------------------------------ *
 * 2. Page hero ("Intestazione pagina") – defaults by page slug
 * ------------------------------------------------------------------ */
function nundia_hero_defaults() {
    return array(
        'chi-siamo' => array('image' => '6c011f10c_generated_0fe9fef8.png', 'eyebrow' => 'Chi siamo', 'title' => 'NUNDÌA – Sapori di Sicilia', 'subtitle' => 'Un progetto dedicato alle specialità dolciarie siciliane: ricette della tradizione, presentate con un’attenzione contemporanea.'),
        'shop'      => array('image' => '82b806293_generated_6a70ec89.png', 'eyebrow' => 'Shop', 'title' => 'Lo shop', 'subtitle' => 'Dolci, conserve e specialità della tradizione siciliana.'),
        'b2b'       => array('image' => '4fe13ad40_generated_e39a5532.png', 'eyebrow' => 'B2B', 'title' => 'Partnership professionali', 'subtitle' => 'GDO, HO.RE.CA. e distribuzione internazionale.'),
        'richiesta-commerciale' => array('image' => '4fe13ad40_generated_e39a5532.png', 'eyebrow' => 'B2B', 'title' => 'Richiesta commerciale', 'subtitle' => 'GDO, HO.RE.CA. e distribuzione internazionale.'),
        'journal'   => array('image' => '1da71479e_generated_75f1e7dd.png', 'eyebrow' => 'Notizie', 'title' => 'Notizie', 'subtitle' => 'Storie, cultura del cibo e novità dalla Sicilia.'),
        'ricette'   => array('image' => 'ce874592d_generated_8720d246.png', 'eyebrow' => 'Ricette', 'title' => 'Ricette', 'subtitle' => 'La tradizione siciliana, spiegata passo per passo.'),
        'faq'       => array('image' => 'a1945ab87_generated_34975dd0.png', 'eyebrow' => 'FAQ', 'title' => 'Domande frequenti', 'subtitle' => ''),
        'contatti'  => array('image' => 'd6b7c4f81_generated_58a4e38e.png', 'eyebrow' => 'Contatti', 'title' => 'Parliamo', 'subtitle' => 'Per informazioni sui prodotti, ordini o collaborazioni.'),
        'diventa-partner' => array('image' => 'orchard.png', 'eyebrow' => 'Partnership & Rappresentanza', 'title' => 'Diventa Partner NUNDÌA', 'subtitle' => 'Opportunità per distributori, agenti e importatori in Italia e nel mondo.'),
        'carrello'  => array('image' => '', 'eyebrow' => 'Carrello', 'title' => 'Il tuo carrello', 'subtitle' => 'Rivedi la selezione prima di procedere.'),
        'checkout'  => array('image' => '', 'eyebrow' => 'Vai al pagamento', 'title' => 'Completa l’ordine', 'subtitle' => ''),
        '_legal'    => array('image' => '', 'eyebrow' => 'Informazioni legali', 'title' => '', 'subtitle' => ''),
    );
}

/* ------------------------------------------------------------------ *
 * 3. Page section fields – grouped by page slug ('front' = home)
 * ------------------------------------------------------------------ */
function nundia_page_fields() {
    return array(
        'front' => array(
            'title' => 'Contenuti Home',
            'fields' => array(
                'tab_cat'            => array('Categorie', 'tab', ''),
                'home_cat_eyebrow'   => array('Categorie – etichetta', 'text', 'Categorie'),
                'home_cat_title'     => array('Categorie – titolo', 'text', 'Le specialità siciliane'),
                'tab_about'          => array('Chi siamo', 'tab', ''),
                'home_about_image'   => array('Chi siamo – immagine', 'image', '253cae95a_generated_0ab3e108.png'),
                'home_about_eyebrow' => array('Chi siamo – etichetta', 'text', 'Chi siamo'),
                'home_about_title'   => array('Chi siamo – titolo', 'text', 'Un’idea semplice: fare le cose come una volta'),
                'home_about_sub'     => array('Chi siamo – sottotitolo', 'text', 'Sicilia, autenticamente.'),
                'home_about_body'    => array('Chi siamo – testo', 'textarea', 'Selezioniamo specialità della tradizione siciliana e le presentiamo con un’attenzione contemporanea al dettaglio: ingredienti riconoscibili, ricette rispettate, confezioni pensate per essere donate.'),
                'home_about_cta'     => array('Chi siamo – pulsante', 'text', 'La nostra storia'),
                'home_about_cta_url' => array('Chi siamo – link del pulsante', 'text', '/chi-siamo/'),
                'tab_best'           => array('I più amati', 'tab', ''),
                'home_best_eyebrow'  => array('I più amati – etichetta', 'text', 'Shop'),
                'home_best_title'    => array('I più amati – titolo (mostra i prodotti “In evidenza” di WooCommerce)', 'text', 'I più amati'),
                'home_best_link'     => array('I più amati – link', 'text', 'Vedi tutto'),
                'tab_b2b'            => array('B2B', 'tab', ''),
                'home_b2b_image'     => array('B2B – immagine di sfondo', 'image', '4fe13ad40_generated_e39a5532.png'),
                'home_b2b_eyebrow'   => array('B2B – etichetta', 'text', 'B2B'),
                'home_b2b_title'     => array('B2B – titolo', 'text', 'Porta la Sicilia nel tuo business'),
                'home_b2b_body'      => array('B2B – testo', 'textarea', 'GDO, hotel, ristoranti e distributori internazionali: costruiamo assortimenti su misura, formati dedicati e forniture continuative.'),
                'home_b2b_tags'      => array('B2B – etichette (una per riga)', 'textarea', "GDO\nHO.RE.CA.\nImportatori"),
                'home_b2b_cta'       => array('B2B – pulsante', 'text', 'Richiesta commerciale'),
                'home_b2b_cta_url'   => array('B2B – link del pulsante', 'text', '/richiesta-commerciale/'),
                'tab_editorial'      => array('Notizie e ricette', 'tab', ''),
                'home_journal_title' => array('Notizie – titolo', 'text', 'Ultime notizie'),
                'home_recipes_title' => array('Ricette – titolo', 'text', 'Ricette da rifare a casa'),
                'home_see_all'       => array('Etichetta “Vedi tutto”', 'text', 'Vedi tutto'),
                'tab_nl'             => array('Newsletter', 'tab', ''),
                'home_nl_eyebrow'    => array('Newsletter – etichetta', 'text', 'Contatti'),
                'home_nl_title'      => array('Newsletter – titolo', 'text', 'Storie, ricette e nuove collezioni'),
                'home_nl_body'       => array('Newsletter – testo', 'textarea', 'Unisciti alla lista NUNDÌA. Poche email, solo quando abbiamo qualcosa da raccontare.'),
                'home_nl_button'     => array('Newsletter – pulsante', 'text', 'Iscrivimi'),
            ),
        ),
        'chi-siamo' => array(
            'title' => 'Contenuti Chi siamo',
            'fields' => array(
                'about_story_title'      => array('Storia – titolo', 'text', 'La storia di NUNDÌA'),
                'about_story_body'       => array('Storia – testo', 'textarea', 'Sezione da completare con la storia ufficiale dell’azienda. Il testo definitivo sarà inserito dal cliente: origini, persone, luoghi e tappe di crescita.'),
                'about_story_image'      => array('Storia – immagine', 'image', 'ce874592d_generated_8720d246.png'),
                'about_story_draft'      => array('Storia – mostra etichetta “provvisorio”', 'true_false', 1),
                'about_heritage_eyebrow' => array('Eredità – etichetta', 'text', 'Una cultura del dolce che attraversa i secoli'),
                'about_heritage_title'   => array('Eredità – titolo', 'text', 'L’eredità siciliana'),
                'about_heritage_body'    => array('Eredità – testo', 'textarea', 'La pasticceria siciliana nasce dall’incontro di culture: araba, normanna, spagnola. Cannolo, cassata, frutta martorana e biscotti di mandorla sono patrimonio collettivo dell’isola, e NUNDÌA li affronta con rispetto.'),
                'about_philosophy_title' => array('Filosofia – titolo', 'text', 'La nostra filosofia'),
                'about_philosophy_body'  => array('Filosofia – testo', 'textarea', 'Poche cose, fatte bene. Ogni prodotto deve poter essere riconosciuto al primo assaggio: per il profumo, per la consistenza, per la memoria che richiama.'),
                'about_gallery_1'        => array('Galleria – immagine 1', 'image', '1da71479e_generated_75f1e7dd.png'),
                'about_gallery_2'        => array('Galleria – immagine 2', 'image', 'fe3531811_generated_5afbaa39.png'),
                'about_gallery_3'        => array('Galleria – immagine 3', 'image', '253cae95a_generated_0ab3e108.png'),
                'about_quality_eyebrow'  => array('Qualità – etichetta', 'text', 'Qualità e produzione'),
                'about_quality_body'     => array('Qualità – testo', 'textarea', 'Informazioni su stabilimento, certificazioni e controlli saranno pubblicate solo dopo verifica documentale da parte del cliente.'),
                'about_quality_items'    => array('Qualità – punti (uno per riga: Titolo | Testo)', 'textarea', "Materie prime | Contenuto provvisorio — da confermare\nLavorazione | Contenuto provvisorio — da confermare\nControllo qualità | Contenuto provvisorio — da confermare"),
                'about_cta_title'        => array('Invito – titolo', 'text', 'Scopri la collezione'),
                'about_cta_body'         => array('Invito – testo', 'text', 'Dolci da gustare, da regalare, da portare in tavola.'),
                'about_cta_button'       => array('Invito – pulsante', 'text', 'Scopri i prodotti'),
                'about_cta_url'          => array('Invito – link del pulsante', 'text', '/shop/'),
            ),
        ),
        'b2b' => array(
            'title' => 'Contenuti B2B',
            'fields' => array(
                'b2b_band_image'       => array('Fascia immagine', 'image', '4fe13ad40_generated_e39a5532.png'),
                'b2b_cards'            => array('Canali (uno per riga: Sigla | Titolo | Testo)', 'textarea', "GDO | Grande distribuzione | Assortimenti dedicati, formati per scaffale e piani di fornitura continuativi, con materiali di supporto al punto vendita.\nHO.RE.CA. | Hotel, ristoranti, catering | Porzionature professionali, prodotti pronti al servizio e referenze selezionate per la carta dessert.\nImport | Importatori e distributori | Documentazione prodotto, etichettatura per mercato e supporto alla costruzione del catalogo."),
                'b2b_cards_draft'      => array('Canali – mostra etichetta “provvisorio”', 'true_false', 1),
                'b2b_banner_eyebrow'   => array('Banda nera – etichetta', 'text', 'B2B'),
                'b2b_banner_heading'   => array('Banda nera – testo', 'textarea', 'Per GDO, HO.RE.CA. e distribuzione internazionale utilizza il modulo dedicato.'),
                'b2b_banner_cta_label' => array('Banda nera – pulsante testo', 'text', 'Richiesta commerciale'),
                'b2b_banner_cta_link'  => array('Banda nera – pulsante link', 'text', '/richiesta-commerciale/'),
            ),
        ),
        'richiesta-commerciale' => array(
            'title' => 'Contenuti Richiesta commerciale',
            'fields' => array(
                'rc_eyebrow'    => array('Etichetta sopra il titolo', 'text', 'Richiesta commerciale'),
                'rc_heading'    => array('Titolo', 'text', 'Parliamo del tuo assortimento'),
                'rc_intro'      => array('Testo introduttivo', 'textarea', 'Indica la tua attività e i prodotti di interesse: ti risponderemo con condizioni, formati disponibili e tempi di consegna.'),
                'rc_bullets'    => array('Punti elenco (uno per riga)', 'textarea', "Assortimenti personalizzati\nEtichettatura multilingua\nForniture programmate"),
                'rc_form_title' => array('Titolo modulo', 'text', 'Richiesta commerciale'),
                'rc_form_sub'   => array('Sottotitolo modulo', 'text', 'Rispondiamo alle richieste commerciali entro pochi giorni lavorativi.'),
            ),
        ),
        'contatti' => array(
            'title' => 'Contenuti Contatti',
            'fields' => array(
                'contact_social_label' => array('Social – etichetta', 'text', 'Contatti'),
                'contact_social_text'  => array('Social – testo (se vuoto mostra le icone social)', 'text', 'Da definire'),
                'contact_privacy_note' => array('Nota privacy sotto il modulo', 'textarea', 'Inviando il modulo acconsenti al trattamento dei dati per rispondere alla tua richiesta. Vedi la Privacy policy.'),
            ),
        ),
        'diventa-partner' => array(
            'title' => 'Contenuti Diventa Partner',
            'fields' => array(
                'tab_dp_opp'          => array('Opportunità & Profili', 'tab', ''),
                'dp_opp_eyebrow'      => array('Opportunità – etichetta', 'text', 'Opportunità di collaborazione'),
                'dp_opp_heading'      => array('Opportunità – titolo', 'text', 'Rappresenta l’eccellenza siciliana nel tuo territorio'),
                'dp_opp_intro'        => array('Opportunità – testo introduttivo', 'textarea', 'NUNDÌA seleziona partner commerciali qualificati per sviluppare la presenza del marchio nelle varie regioni italiane e nei mercati internazionali. Offriamo la possibilità di concordare accordi di rappresentanza esclusiva a fronte di comprovati requisiti professionali e distributivi.'),
                'dp_profiles'         => array('Profili ricercati (uno per riga: Categoria | Titolo | Descrizione)', 'textarea', "Distribuzione | Centri di Distribuzione | Piattaforme logistiche e centri distributivi specializzati nel canale alimentare, con catena del freddo e capacità capillare di rifornimento.\nRete Vendita | Agenti Monomandatari e Plurimandatari | Professionisti commerciali con radicata presenza sul territorio e portafoglio clienti consolidato nei canali gourmet, Ho.Re.Ca. e retail specializzato.\nSviluppo Business | Rappresentanti Commerciali | Figure dedicate allo sviluppo strategico del business, capaci di valorizzare il posizionamento premium e l’artigianalità dei prodotti NUNDÌA.\nMercati Esteri | Importatori ed Operatori Esteri | Partner internazionali strutturati per l’importazione, lo sdoganamento e la commercializzazione delle specialità siciliane nel proprio Paese."),
                'tab_dp_req'          => array('Requisiti Esclusiva', 'tab', ''),
                'dp_req_eyebrow'      => array('Requisiti – etichetta', 'text', 'Criteri di selezione'),
                'dp_req_heading'      => array('Requisiti – titolo', 'text', 'Requisiti necessari per la rappresentanza esclusiva'),
                'dp_req_intro'        => array('Requisiti – testo introduttivo', 'textarea', 'Per poter rappresentare NUNDÌA in via esclusiva in un determinato Paese o regione d’Italia, richiediamo la conformità ai seguenti parametri qualitativi e quantitativi:'),
                'dp_requirements'     => array('Requisiti esclusiva (uno per riga: Numero | Titolo | Descrizione)', 'textarea', "01 | Presenza sul Mercato | Anni di attività continuativa e comprovata stabilità commerciale all’interno del mercato o del territorio di riferimento.\n02 | Esperienza nel Settore Agroalimentare | Solida esperienza specifica nel comparto dolciario, pasticceria artigianale, specialty food o prodotti enogastronomici d’eccellenza.\n03 | Portafoglio Clienti Attivi | Consistenza numerica e qualitativa della clientela servita: gastronomie di livello, boutique gourmet, Ho.Re.Ca. selezionato e GDO premium.\n04 | Fatturato e Solidità Finanziaria | Volume d’affari adeguato e capacità economico-finanziaria coerente con gli obiettivi di sell-in e sell-out concordati.\n05 | Bacino d’Utenza e Rete Distributiva | Ampiezza della copertura geografica, tempi di consegna garantiti e capacità di presidiare capillarmente la zona assegnata.\n06 | Track Record e Qualifiche | Risultati tangibili conseguiti con altri marchi affini, referenze professionali verificabili e adeguate certificazioni aziendali."),
                'tab_dp_form'         => array('Modulo Candidatura & Proposta', 'tab', ''),
                'dp_form_eyebrow'     => array('Modulo – etichetta', 'text', 'Candidatura Partner'),
                'dp_form_heading'     => array('Modulo – titolo principale', 'text', 'Inviaci la tua proposta'),
                'dp_form_intro'       => array('Modulo – testo introduttivo', 'textarea', 'Sei interessato a rappresentare NUNDÌA nel tuo mercato di competenza? Inviaci la tua lettera di presentazione e il profilo aziendale allegando la documentazione in formato PDF.'),
                'dp_form_box_title'   => array('Box Proposta – titolo', 'text', 'Cosa includere nella proposta:'),
                'dp_form_box_bullets' => array('Box Proposta – punti elenco (uno per riga)', 'textarea', "Descrizione dell'azienda o profilo dell'agente con anni di operatività.\nTerritorio, regione o Paese per cui si richiede la rappresentanza esclusiva.\nCanali di vendita presidiati, tipologia di clientela e fatturato indicativo."),
                'dp_form_contact_note'=> array('Nota contatto commerciale (lascia vuoto per default)', 'text', 'Per comunicazioni dirette della divisione commerciale:'),
                'dp_form_title'       => array('Modulo – titolo del form', 'text', 'Modulo di candidatura'),
                'dp_form_sub'         => array('Modulo – sottotitolo del form', 'text', 'Tutti i campi contrassegnati con * sono obbligatori.'),
                'dp_form_cta'         => array('Pulsante di invio – testo', 'text', 'Inviaci la tua proposta'),
                'dp_form_upload_label'=> array('Campo upload PDF – etichetta', 'text', 'Carica proposta / Profilo aziendale (PDF) *'),
                'dp_form_upload_help' => array('Campo upload PDF – testo di aiuto', 'text', 'Carica la tua lettera di presentazione o presentazione aziendale in formato PDF (max 10MB).'),
                'dp_form_success_msg' => array('Messaggio di successo dopo l’invio', 'textarea', 'Grazie per la proposta! Abbiamo ricevuto la tua candidatura e il documento PDF. Il nostro team commerciale la esaminerà con la massima attenzione e ti risponderà a breve.'),
            ),
        ),
    );
}

/** Default hero slides for the home page (ACF repeater "home_slides"). */
function nundia_default_slides() {
    return array(
        array('image' => 'cd13e8a64_generated_9ac4bb17.png', 'eyebrow' => 'Pasticceria e specialità siciliane', 'title' => "Una terra di mille sapori\ne di infinite storie da raccontare", 'body' => 'Dolci della tradizione siciliana, sapori autentici e una passione per la nostra terra.', 'cta_label' => 'Scopri i prodotti', 'cta_link' => '/shop/', 'alt' => 'Cannolo siciliano appena spolverato di zucchero in un raggio di luce calda'),
        array('image' => '82b806293_generated_6a70ec89.png', 'eyebrow' => 'Shop', 'title' => 'I più amati', 'body' => 'Una selezione dei nostri classici, scelti da chi ci conosce.', 'cta_label' => 'Vedi tutto', 'cta_link' => '/shop/', 'alt' => 'Cannoli siciliani con ricotta e pistacchio'),
        array('image' => '4fe13ad40_generated_e39a5532.png', 'eyebrow' => 'B2B', 'title' => 'Porta la Sicilia nel tuo business', 'body' => 'GDO, hotel, ristoranti e distributori internazionali: costruiamo assortimenti su misura, formati dedicati e forniture continuative.', 'cta_label' => 'Richiesta commerciale', 'cta_link' => '/b2b/', 'alt' => 'Selezione e confezionamento delle specialità siciliane'),
    );
}

/* ------------------------------------------------------------------ *
 * 4. Read helpers
 * ------------------------------------------------------------------ */

/** True when an ACF value should be treated as "not set". */
function nundia_is_empty($v) {
    return $v === null || $v === false || $v === '' || (is_array($v) && empty($v));
}

/** Global option (Site Settings / NUNDÌA Sito). */
function nd_opt($key) {
    // blessing-child alias support
    $aliases = array(
        'header_logo'    => 'header_logo_light',
        'o_email'        => 'email_general',
        'o_phone'        => 'phone',
        'facebook_link'  => 'facebook',
        'whatsapp_link'  => 'mobile',
        'instagram_link' => 'instagram',
        'linkedin_link'  => 'linkedin',
        'o_address'      => 'address',
    );
    if (isset($aliases[$key])) {
        $val = function_exists('get_field') ? get_field($key, 'option') : null;
        if (!nundia_is_empty($val)) return $val;
        $key = $aliases[$key];
    }

    $defs = nundia_option_fields();
    $default = isset($defs[$key]) ? $defs[$key][2] : '';
    if (!function_exists('get_field')) return ($key === 'brand_name' ? (get_bloginfo('name') ?: 'NUNDÌA') : '');
    $v = get_field($key, 'option');
    if (isset($defs[$key])) {
        if ($defs[$key][1] === 'true_false') {
            return (bool) $v;
        }
        if ($defs[$key][1] === 'image') {
            if (is_array($v) && !empty($v['url'])) return $v['url'];
            if (is_numeric($v)) return wp_get_attachment_image_url((int) $v, 'full') ?: '';
            if (is_string($v) && $v !== '') return $v;
            return '';
        }
    }
    if ($key === 'brand_name' && nundia_is_empty($v)) {
        return get_bloginfo('name') ?: 'NUNDÌA';
    }
    return nundia_is_empty($v) ? '' : $v;
}

/** Fallback for blessing-child option field names */
add_filter('acf/load_value', function ($value, $post_id, $field) {
    if ($post_id !== 'options' && $post_id !== 'option') return $value;
    if (!empty($value)) return $value;

    $map = array(
        'o_email'        => 'email_general',
        'o_phone'        => 'phone',
        'facebook_link'  => 'facebook',
        'whatsapp_link'  => 'mobile',
        'instagram_link' => 'instagram',
        'linkedin_link'  => 'linkedin',
        'o_address'      => 'address',
        'header_logo'    => 'header_logo_light',
    );
    if (!empty($field['name']) && isset($map[$field['name']])) {
        $fallback = get_option('options_' . $map[$field['name']]);
        if (!empty($fallback)) return $fallback;
    }
    return $value;
}, 10, 3);

/** Page section field. $post_id defaults to the current page. */
function nd($key, $post_id = null) {
    static $map = null;
    if ($map === null) {
        $map = array();
        foreach (nundia_page_fields() as $group) {
            foreach ($group['fields'] as $k => $def) $map[$k] = $def;
        }
    }
    $def = isset($map[$key]) ? $map[$key] : array('', 'text', '');
    if ($post_id === null) $post_id = get_queried_object_id();
    $v = function_exists('get_field') ? get_field($key, $post_id) : null;

    if ($def[1] === 'image') {
        $url = '';
        if (is_array($v) && !empty($v['url'])) $url = $v['url'];
        elseif (is_numeric($v)) $url = wp_get_attachment_image_url((int) $v, 'full');
        elseif (is_string($v) && $v !== '') $url = $v;
        return $url ?: '';
    }
    if ($def[1] === 'true_false') {
        return (bool) $v;
    }
    return nundia_is_empty($v) ? '' : $v;
}

/** Link typed by the editor: "/shop/" becomes a full site URL, full URLs are kept as they are. */
function nd_url($url, $fallback = '') {
    $url = trim((string) $url);
    if ($url === '') $url = $fallback;
    if ($url === '' || preg_match('~^(https?:|mailto:|tel:|#)~i', $url)) return $url;
    return home_url('/' . ltrim($url, '/'));
}

/** Split a "one per line" textarea into trimmed lines. */
function nd_lines($text) {
    if (empty($text)) return array();
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text)), 'strlen'));
}

/** Split "A | B | C" lines into arrays. */
function nd_rows($text) {
    if (empty($text)) return array();
    return array_map(function ($l) { return array_map('trim', explode('|', $l)); }, nd_lines($text));
}

/** Page used to edit an archive's hero (shop / ricette / journal). */
function nundia_page_for_slug($slug) {
    $p = get_page_by_path($slug);
    return $p ? $p->ID : 0;
}

/** Hero data for a page slug, merged with the ACF "Intestazione pagina" fields of that page. */
function nd_hero($slug, $post_id = 0) {
    if (!$post_id) $post_id = nundia_page_for_slug($slug);
    $out = array(
        'image'    => '',
        'eyebrow'  => '',
        'title'    => '',
        'subtitle' => '',
    );
    if ($post_id && function_exists('get_field')) {
        foreach (array('eyebrow', 'title', 'subtitle') as $k) {
            $v = get_field('hero_' . $k, $post_id);
            if (!nundia_is_empty($v)) $out[$k] = $v;
        }
        $img = get_field('hero_image', $post_id);
        if (is_array($img) && !empty($img['url'])) $out['image'] = $img['url'];
        elseif (is_numeric($img)) $out['image'] = wp_get_attachment_image_url((int) $img, 'full') ?: '';
        elseif (is_string($img) && $img !== '') $out['image'] = $img;
    }
    if ($out['title'] === '' && $post_id) $out['title'] = get_the_title($post_id);
    return $out;
}

/** Draft badge ("Contenuto provvisorio — da confermare") if enabled globally. */
function nd_draft_badge($extra = 'mt-6') {
    if (!nd_opt('show_draft_badges')) return '';
    return '<p class="inline-flex items-center gap-2 border border-dashed border-primary bg-primary/12 px-3 py-1.5 text-[10px] uppercase tracking-[0.18em] text-foreground/60 ' . esc_attr($extra) . '"><span class="h-1.5 w-1.5 rounded-full bg-primary" aria-hidden="true"></span>' . esc_html(nd_opt('draft_label')) . '</p>';
}

/* ------------------------------------------------------------------ *
 * 5. ACF registration
 * ------------------------------------------------------------------ */
function nundia_acf_field($group_key, $name, $def) {
    list($label, $type, $default) = $def;
    $f = array(
        'key'   => 'field_nd_' . $group_key . '_' . $name,
        'label' => $label,
        'name'  => $name,
        'type'  => $type,
    );
    if ($type === 'tab') {
        unset($f['name']);
        $f['placement'] = 'top';
        return $f;
    }
    if ($type === 'image') {
        $f['return_format'] = 'array';
        $f['preview_size'] = 'medium';
        $f['instructions'] = 'Lascia vuoto per non mostrare l’immagine.';
    } elseif ($type === 'select') {
        $f['choices'] = nundia_font_choices();
        $f['default_value'] = $default;
        $f['instructions'] = 'Arial è identico su tutti i dispositivi; Calibri è disponibile su Windows/Office (altrove viene usato Arial).';
    } elseif ($type === 'true_false') {
        $f['ui'] = 1;
        $f['default_value'] = $default;
    } else {
        $f['default_value'] = $default;
        if ($type === 'textarea') {
            $f['rows'] = 3;
            $f['new_lines'] = '';
        }
    }
    return $f;
}

/* ------------------------------------------------------------------ *
 * ACF JSON save and load paths (like blessingChild)
 * ------------------------------------------------------------------ */
add_filter('acf/settings/save_json', function ($path) {
    return get_template_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});

function nundia_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    // Global settings
    $fields = array();
    foreach (nundia_option_fields() as $name => $def) $fields[] = nundia_acf_field('opt', $name, $def);
    acf_add_local_field_group(array(
        'key' => 'group_nundia_site_settings',
        'title' => 'Site Settings (Impostazioni NUNDÌA)',
        'fields' => $fields,
        'location' => array(
            array(array('param' => 'options_page', 'operator' => '==', 'value' => 'site-settings')),
            array(array('param' => 'options_page', 'operator' => '==', 'value' => 'nundia-settings')),
        ),
    ));

    // Page hero (all pages except home / Woo account)
    acf_add_local_field_group(array(
        'key' => 'group_nundia_page_hero',
        'title' => 'Intestazione pagina (Hero)',
        'fields' => array(
            nundia_acf_field('hero', 'hero_eyebrow', array('Etichetta sopra il titolo', 'text', '')),
            nundia_acf_field('hero', 'hero_title', array('Titolo', 'text', '')),
            nundia_acf_field('hero', 'hero_subtitle', array('Sottotitolo', 'textarea', '')),
            nundia_acf_field('hero', 'hero_image', array('Immagine di sfondo', 'image', '')),
        ),
        'instructions' => '',
        'location' => array(array(
            array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
            array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
        )),
        'position' => 'acf_after_title',
        'menu_order' => 0,
        'description' => 'Titolo vuoto = titolo della pagina. Gli altri campi, se vuoti, non vengono mostrati.',
    ));

    // Home: slides
    acf_add_local_field_group(array(
        'key' => 'group_nundia_home_slides',
        'title' => 'Home – Slide principali (Hero Slider)',
        'fields' => array(array(
            'key' => 'field_nd_home_slides',
            'label' => 'Slide (scorrono automaticamente ogni 9 secondi)',
            'name' => 'home_slides',
            'type' => 'repeater',
            'layout' => 'block',
            'button_label' => 'Aggiungi slide',
            'instructions' => 'Lascia vuoto per usare le 3 slide predefinite.',
            'sub_fields' => array(
                array('key' => 'field_nd_slide_image', 'label' => 'Immagine', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
                array('key' => 'field_nd_slide_eyebrow', 'label' => 'Etichetta', 'name' => 'eyebrow', 'type' => 'text'),
                array('key' => 'field_nd_slide_title', 'label' => 'Titolo', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => ''),
                array('key' => 'field_nd_slide_body', 'label' => 'Testo', 'name' => 'body', 'type' => 'textarea', 'rows' => 2, 'new_lines' => ''),
                array('key' => 'field_nd_slide_cta_label', 'label' => 'Pulsante – testo', 'name' => 'cta_label', 'type' => 'text'),
                array('key' => 'field_nd_slide_cta_link', 'label' => 'Pulsante – link', 'name' => 'cta_link', 'type' => 'text'),
            ),
        )),
        'location' => array(array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'))),
        'menu_order' => 1,
    ));

    // Page sections
    foreach (nundia_page_fields() as $slug => $group) {
        $fields = array();
        foreach ($group['fields'] as $name => $def) $fields[] = nundia_acf_field($slug, $name, $def);
        if ($slug === 'front') {
            $location = array(array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')));
        } else {
            $page_id = nundia_page_for_slug($slug);
            $location = array(
                array(array('param' => 'page_template', 'operator' => '==', 'value' => 'page-' . $slug . '.php')),
                array(array('param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-' . $slug . '.php')),
            );
            if ($page_id) {
                $location[] = array(array('param' => 'page', 'operator' => '==', 'value' => $page_id));
            }
        }
        acf_add_local_field_group(array(
            'key' => 'group_nundia_page_' . str_replace('-', '_', $slug),
            'title' => $group['title'],
            'fields' => $fields,
            'location' => $location,
            'menu_order' => 2,
        ));
    }

    // Product extra fields
    acf_add_local_field_group(array(
        'key' => 'group_nundia_product_fields',
        'title' => 'Dettagli Prodotto NUNDÌA',
        'fields' => array(
            array('key' => 'field_product_unit_label', 'label' => 'Peso Netto (es. 360 g, 500 gr, 1 kg)', 'name' => 'unit_label', 'type' => 'text', 'instructions' => 'Indicare solo il peso netto e l’unità di misura (es. “360 g”, “500 gr”, “1 kg”), senza “barattolo” o altro. Visualizzato nella scheda prodotto e nel carrello.'),
            array(
                'key' => 'field_product_formats',
                'label' => 'Formati disponibili (Varianti di peso e prezzo)',
                'name' => 'product_formats',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Aggiungi formato',
                'instructions' => 'Se il prodotto è disponibile in più formati (es. 240 gr, 360 gr), inseriscili qui. Lascia vuoto se il prodotto ha un unico formato.',
                'sub_fields' => array(
                    array('key' => 'field_fmt_label', 'label' => 'Etichetta Formato (es. 240 gr)', 'name' => 'format_label', 'type' => 'text'),
                    array('key' => 'field_fmt_price', 'label' => 'Prezzo (€)', 'name' => 'format_price', 'type' => 'text'),
                    array('key' => 'field_fmt_stock', 'label' => 'Disponibile', 'name' => 'format_in_stock', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
                    array('key' => 'field_fmt_net_weight', 'label' => 'Peso netto (es. 240 g, 360 gr)', 'name' => 'format_net_weight', 'type' => 'text'),
                ),
            ),
            array('key' => 'field_product_ingredients', 'label' => 'Ingredienti', 'name' => 'ingredients', 'type' => 'textarea'),
            array('key' => 'field_product_allergens', 'label' => 'Allergeni', 'name' => 'allergens', 'type' => 'textarea'),
            array('key' => 'field_product_nutrition', 'label' => 'Valori nutrizionali', 'name' => 'nutrition', 'type' => 'textarea'),
            array('key' => 'field_product_packaging', 'label' => 'Confezionamento', 'name' => 'packaging', 'type' => 'textarea'),
        ),
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'product'))),
    ));

    // Recipe extra fields
    acf_add_local_field_group(array(
        'key' => 'group_nundia_recipe_fields',
        'title' => 'Dettagli Ricetta',
        'fields' => array(
            array('key' => 'field_recipe_prep', 'label' => 'Tempo preparazione (minuti)', 'name' => 'prep_minutes', 'type' => 'number'),
            array('key' => 'field_recipe_cook', 'label' => 'Tempo cottura (minuti)', 'name' => 'cook_minutes', 'type' => 'number'),
            array('key' => 'field_recipe_servings', 'label' => 'Persone', 'name' => 'servings', 'type' => 'number'),
            array('key' => 'field_recipe_ingredients', 'label' => 'Ingredienti (uno per riga)', 'name' => 'ingredients', 'type' => 'textarea', 'new_lines' => ''),
            array('key' => 'field_recipe_instructions', 'label' => 'Procedimento (un passaggio per riga)', 'name' => 'instructions', 'type' => 'textarea', 'new_lines' => ''),
            array('key' => 'field_recipe_related', 'label' => 'Prodotti correlati (“Potrebbe interessarti”)', 'name' => 'related_products', 'type' => 'relationship', 'post_type' => array('product'), 'return_format' => 'id', 'filters' => array('search')),
        ),
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'recipe'))),
    ));
}
add_action('acf/init', 'nundia_register_acf_fields');
