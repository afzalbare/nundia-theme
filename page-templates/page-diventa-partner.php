<?php
/**
 * Template Name: Diventa Partner
 * 
 * Partnership, agenzia e distribuzione esclusiva NUNDÌA per l'Italia e l'estero.
 * 100% Dynamic - All sections, cards, criteria, and form texts are editable via ACF / WordPress.
 */
get_header();
$pid = get_queried_object_id();

$hero = nd_hero('diventa-partner', $pid);
if (empty($hero['title'])) {
    $hero['title'] = 'Diventa Partner NUNDÌA';
}
if (empty($hero['eyebrow'])) {
    $hero['eyebrow'] = 'Partnership & Rappresentanza';
}
if (empty($hero['subtitle'])) {
    $hero['subtitle'] = 'Opportunità per distributori, agenti e importatori in Italia e nel mondo.';
}
if (empty($hero['image'])) {
    $hero['image'] = nundia_img('orchard.png');
}
nundia_page_hero($hero);

$input = 'mt-1.5 w-full border border-input bg-white px-3.5 py-2.5 text-[14px] outline-none transition-colors focus:border-foreground rounded-sm';
$label = 'text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground font-medium';

/* Section 1: Opportunità & Profili */
$eyebrow_opp = nd('dp_opp_eyebrow') ?: 'Opportunità di collaborazione';
$heading_opp = nd('dp_opp_heading') ?: 'Rappresenta l’eccellenza siciliana nel tuo territorio';
$intro_opp   = nd('dp_opp_intro') ?: 'NUNDÌA seleziona partner commerciali qualificati per sviluppare la presenza del marchio nelle varie regioni italiane e nei mercati internazionali. Offriamo la possibilità di concordare accordi di rappresentanza esclusiva a fronte di comprovati requisiti professionali e distributivi.';

$profiles_raw = nd_rows(nd('dp_profiles'));
if (!empty($profiles_raw)) {
    $profiles = array();
    foreach ($profiles_raw as $r) {
        $profiles[] = array(
            'tag'   => isset($r[0]) ? $r[0] : '',
            'title' => isset($r[1]) ? $r[1] : '',
            'desc'  => isset($r[2]) ? $r[2] : '',
        );
    }
} else {
    $profiles = array(
        array(
            'title' => 'Centri di Distribuzione',
            'desc'  => 'Piattaforme logistiche e centri distributivi specializzati nel canale alimentare, con catena del freddo e capacità capillare di rifornimento.',
            'tag'   => 'Distribuzione'
        ),
        array(
            'title' => 'Agenti Monomandatari e Plurimandatari',
            'desc'  => 'Professionisti commerciali con radicata presenza sul territorio e portafoglio clienti consolidato nei canali gourmet, Ho.Re.Ca. e retail specializzato.',
            'tag'   => 'Rete Vendita'
        ),
        array(
            'title' => 'Rappresentanti Commerciali',
            'desc'  => 'Figure dedicate allo sviluppo strategico del business, capaci di valorizzare il posizionamento premium e l’artigianalità dei prodotti NUNDÌA.',
            'tag'   => 'Sviluppo Business'
        ),
        array(
            'title' => 'Importatori ed Operatori Esteri',
            'desc'  => 'Partner internazionali strutturati per l’importazione, lo sdoganamento e la commercializzazione delle specialità siciliane nel proprio Paese.',
            'tag'   => 'Mercati Esteri'
        ),
    );
}

/* Section 2: Requisiti per la Rappresentanza Esclusiva */
$eyebrow_req = nd('dp_req_eyebrow') ?: 'Criteri di selezione';
$heading_req = nd('dp_req_heading') ?: 'Requisiti necessari per la rappresentanza esclusiva';
$intro_req   = nd('dp_req_intro') ?: 'Per poter rappresentare NUNDÌA in via esclusiva in un determinato Paese o regione d’Italia, richiediamo la conformità ai seguenti parametri qualitativi e quantitativi:';

$requirements_raw = nd_rows(nd('dp_requirements'));
if (!empty($requirements_raw)) {
    $requirements = array();
    foreach ($requirements_raw as $r) {
        $requirements[] = array(
            'num'   => isset($r[0]) ? $r[0] : '',
            'title' => isset($r[1]) ? $r[1] : '',
            'desc'  => isset($r[2]) ? $r[2] : '',
        );
    }
} else {
    $requirements = array(
        array(
            'num'   => '01',
            'title' => 'Presenza sul Mercato',
            'desc'  => 'Anni di attività continuativa e comprovata stabilità commerciale all’interno del mercato o del territorio di riferimento.'
        ),
        array(
            'num'   => '02',
            'title' => 'Esperienza nel Settore Agroalimentare',
            'desc'  => 'Solida esperienza specifica nel comparto dolciario, pasticceria artigianale, specialty food o prodotti enogastronomici d’eccellenza.'
        ),
        array(
            'num'   => '03',
            'title' => 'Portafoglio Clienti Attivi',
            'desc'  => 'Consistenza numerica e qualitativa della clientela servita: gastronomie di livello, boutique gourmet, Ho.Re.Ca. selezionato e GDO premium.'
        ),
        array(
            'num'   => '04',
            'title' => 'Fatturato e Solidità Finanziaria',
            'desc'  => 'Volume d’affari adeguato e capacità economico-finanziaria coerente con gli obiettivi di sell-in e sell-out concordati.'
        ),
        array(
            'num'   => '05',
            'title' => 'Bacino d’Utenza e Rete Distributiva',
            'desc'  => 'Ampiezza della copertura geografica, tempi di consegna garantiti e capacità di presidiare capillarmente la zona assegnata.'
        ),
        array(
            'num'   => '06',
            'title' => 'Track Record e Qualifiche',
            'desc'  => 'Risultati tangibili conseguiti con altri marchi affini, referenze professionali verificabili e adeguate certificazioni aziendali.'
        ),
    );
}

/* Section 3: Modulo Candidatura & Upload Proposta PDF */
$form_eyebrow      = nd('dp_form_eyebrow') ?: 'Candidatura Partner';
$form_heading      = nd('dp_form_heading') ?: 'Inviaci la tua proposta';
$form_intro        = nd('dp_form_intro') ?: 'Sei interessato a rappresentare NUNDÌA nel tuo mercato di competenza? Inviaci la tua lettera di presentazione e il profilo aziendale allegando la documentazione in formato PDF.';
$form_box_title    = nd('dp_form_box_title') ?: 'Cosa includere nella proposta:';
$form_box_bullets  = nd_lines(nd('dp_form_box_bullets'));
if (empty($form_box_bullets)) {
    $form_box_bullets = array(
        "Descrizione dell'azienda o profilo dell'agente con anni di operatività.",
        "Territorio, regione o Paese per cui si richiede la rappresentanza esclusiva.",
        "Canali di vendita presidiati, tipologia di clientela e fatturato indicativo.",
    );
}
$form_contact_note = nd('dp_form_contact_note') ?: 'Per comunicazioni dirette della divisione commerciale:';
$form_title        = nd('dp_form_title') ?: 'Modulo di candidatura';
$form_sub          = nd('dp_form_sub') ?: 'Tutti i campi contrassegnati con * sono obbligatori.';
$form_cta          = nd('dp_form_cta') ?: 'Inviaci la tua proposta';
$form_upload_label = nd('dp_form_upload_label') ?: 'Carica proposta / Profilo aziendale (PDF) *';
$form_upload_help  = nd('dp_form_upload_help') ?: 'Carica la tua lettera di presentazione o presentazione aziendale in formato PDF (max 10MB).';
$email_orders      = nd_opt('email_orders') ?: 'info@nundia.it';
?>

<!-- Section 1: Intro & Target Profiles -->
<section class="container-ed py-12 lg:py-20">
    <div class="max-w-3xl reveal">
        <?php if ($eyebrow_opp): ?>
            <p class="eyebrow rule-accent"><?php echo esc_html($eyebrow_opp); ?></p>
        <?php endif; ?>
        <?php if ($heading_opp): ?>
            <h2 class="mt-4 text-[clamp(1.75rem,3.2vw,2.6rem)] leading-[1.15]"><?php echo esc_html($heading_opp); ?></h2>
        <?php endif; ?>
        <?php if ($intro_opp): ?>
            <p class="mt-4 text-[15px] leading-relaxed text-muted-foreground"><?php echo esc_html($intro_opp); ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($profiles)): ?>
    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($profiles as $i => $prof): ?>
            <div class="reveal rounded-xl bg-muted/30 p-6 transition-all duration-300 hover:bg-muted/50 hover:shadow-sm"<?php echo $i ? ' style="transition-delay: ' . (70 * $i) . 'ms;"' : ''; ?>>
                <?php if (!empty($prof['tag'])): ?>
                    <span class="inline-block text-[10px] font-semibold uppercase tracking-[0.18em] text-primary"><?php echo esc_html($prof['tag']); ?></span>
                <?php endif; ?>
                <?php if (!empty($prof['title'])): ?>
                    <h3 class="mt-3 text-[17px] font-bold leading-snug"><?php echo esc_html($prof['title']); ?></h3>
                <?php endif; ?>
                <?php if (!empty($prof['desc'])): ?>
                    <p class="mt-2 text-[13px] leading-relaxed text-muted-foreground"><?php echo esc_html($prof['desc']); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- Section 2: Requirements for Exclusive Representation -->
<section class="bg-secondary/40 py-14 lg:py-20">
    <div class="container-ed">
        <div class="max-w-3xl reveal">
            <?php if ($eyebrow_req): ?>
                <p class="eyebrow rule-accent"><?php echo esc_html($eyebrow_req); ?></p>
            <?php endif; ?>
            <?php if ($heading_req): ?>
                <h2 class="mt-4 text-[clamp(1.75rem,3.2vw,2.6rem)] leading-[1.15]"><?php echo esc_html($heading_req); ?></h2>
            <?php endif; ?>
            <?php if ($intro_req): ?>
                <p class="mt-4 text-[15px] leading-relaxed text-muted-foreground"><?php echo esc_html($intro_req); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($requirements)): ?>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($requirements as $i => $req): ?>
                <div class="reveal rounded-xl bg-white p-7 shadow-[0_2px_12px_rgba(0,0,0,0.03)] transition-transform duration-300 hover:-translate-y-1"<?php echo $i ? ' style="transition-delay: ' . (70 * $i) . 'ms;"' : ''; ?>>
                    <?php if (!empty($req['num'])): ?>
                        <span class="font-mono text-2xl font-bold tracking-tight text-primary/80"><?php echo esc_html($req['num']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($req['title'])): ?>
                        <h3 class="mt-3 text-lg font-bold"><?php echo esc_html($req['title']); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($req['desc'])): ?>
                        <p class="mt-2 text-[13.5px] leading-relaxed text-muted-foreground"><?php echo esc_html($req['desc']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Section 3: Proposal Submission & PDF Upload Form -->
<section class="container-ed py-14 lg:py-24" id="candidatura">
    <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16 items-start">
        <div class="reveal">
            <?php if ($form_eyebrow): ?>
                <p class="eyebrow rule-accent"><?php echo esc_html($form_eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($form_heading): ?>
                <h2 class="mt-4 text-[clamp(1.75rem,3.2vw,2.5rem)] leading-tight"><?php echo esc_html($form_heading); ?></h2>
            <?php endif; ?>
            <?php if ($form_intro): ?>
                <p class="mt-4 text-[14.5px] leading-relaxed text-muted-foreground"><?php echo esc_html($form_intro); ?></p>
            <?php endif; ?>

            <?php if (!empty($form_box_bullets)): ?>
            <div class="mt-8 space-y-4 rounded-xl bg-muted/30 p-6 text-[13px] leading-relaxed text-foreground/80">
                <?php if ($form_box_title): ?>
                    <p class="font-semibold text-foreground"><?php echo esc_html($form_box_title); ?></p>
                <?php endif; ?>
                <ul class="space-y-2 text-muted-foreground">
                    <?php foreach ($form_box_bullets as $bullet): ?>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-primary shrink-0"></span>
                            <span><?php echo esc_html($bullet); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ($email_orders): ?>
                <p class="mt-6 text-[12.5px] text-muted-foreground">
                    <?php echo esc_html($form_contact_note); ?> <a href="mailto:<?php echo esc_attr($email_orders); ?>" class="font-medium text-foreground underline hover:text-primary"><?php echo esc_html($email_orders); ?></a>
                </p>
            <?php endif; ?>
        </div>

        <div class="reveal rounded-2xl bg-white p-6 sm:p-10 shadow-[0_4px_30px_rgba(0,0,0,0.04)]" style="transition-delay: 120ms;">
            <form class="js-ajax-form space-y-5" data-action="nundia_ajax_partner" enctype="multipart/form-data">
                <div>
                    <?php if ($form_title): ?>
                        <h3 class="text-xl font-bold"><?php echo esc_html($form_title); ?></h3>
                    <?php endif; ?>
                    <?php if ($form_sub): ?>
                        <p class="mt-1 text-[12.5px] text-muted-foreground"><?php echo esc_html($form_sub); ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="<?php echo $label; ?>">Azienda / Ragione Sociale *</span>
                        <input name="company" class="<?php echo $input; ?>" required autocomplete="organization">
                    </label>
                    <label class="block">
                        <span class="<?php echo $label; ?>">Partita IVA / Tax ID *</span>
                        <input name="vat" class="<?php echo $input; ?>" required>
                    </label>
                    <label class="block">
                        <span class="<?php echo $label; ?>">Nome e Cognome Referente *</span>
                        <input name="name" class="<?php echo $input; ?>" required autocomplete="name">
                    </label>
                    <label class="block">
                        <span class="<?php echo $label; ?>">Email aziendale *</span>
                        <input name="email" type="email" class="<?php echo $input; ?>" required autocomplete="email">
                    </label>
                    <label class="block">
                        <span class="<?php echo $label; ?>">Telefono / Cellulare *</span>
                        <input name="phone" type="tel" class="<?php echo $input; ?>" required autocomplete="tel">
                    </label>
                    <label class="block">
                        <span class="<?php echo $label; ?>">Territorio / Paese di interesse *</span>
                        <input name="territory" class="<?php echo $input; ?>" placeholder="Es. Lombardia, Germania, ecc." required>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="<?php echo $label; ?>">Tipologia di operatore *</span>
                        <select name="type" class="<?php echo $input; ?>" required>
                            <option value="">Seleziona...</option>
                            <option value="Centro di distribuzione">Centro di distribuzione</option>
                            <option value="Agente monomandatario">Agente monomandatario</option>
                            <option value="Agente plurimandatario">Agente plurimandatario</option>
                            <option value="Rappresentante commerciale">Rappresentante commerciale</option>
                            <option value="Importatore / Distributore estero">Importatore / Distributore estero</option>
                            <option value="Altro operatore del settore">Altro operatore del settore</option>
                        </select>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="<?php echo $label; ?>">Lettera di presentazione / Messaggio *</span>
                        <textarea name="message" rows="4" class="<?php echo $input; ?>" placeholder="Descrivi brevemente la tua attività, la presenza sul mercato e la tua motivazione..." required></textarea>
                    </label>
                </div>

                <!-- PDF File Upload -->
                <div class="rounded-xl border border-dashed border-border bg-muted/20 p-5">
                    <label class="block cursor-pointer">
                        <span class="<?php echo $label; ?> block text-foreground"><?php echo esc_html($form_upload_label); ?></span>
                        <span class="mt-1 block text-[12px] text-muted-foreground"><?php echo esc_html($form_upload_help); ?></span>
                        <input type="file" name="proposal_pdf" accept=".pdf,application/pdf" required class="mt-3 block w-full text-[13px] text-muted-foreground file:mr-4 file:rounded-sm file:border-0 file:bg-primary file:px-4 file:py-2 file:text-[11px] file:font-semibold file:uppercase file:tracking-[0.14em] file:text-white file:transition-colors hover:file:bg-primary/90 cursor-pointer">
                    </label>
                </div>

                <label class="flex items-start gap-2.5 text-[12px] text-muted-foreground cursor-pointer pt-1">
                    <input type="checkbox" name="privacy" required class="mt-0.5 rounded border-border text-primary focus:ring-primary">
                    <span>Ho letto e accetto l'<a href="<?php echo esc_url(home_url('/privacy/')); ?>" target="_blank" class="underline hover:text-foreground">informativa sulla privacy</a> e acconsento al trattamento dei dati personali. *</span>
                </label>

                <button type="submit" class="group mt-4 inline-flex min-h-12 w-full items-center justify-center gap-2.5 bg-primary px-8 text-[11px] font-semibold uppercase tracking-[0.18em] text-white transition-all hover:bg-primary/90 disabled:opacity-60 sm:w-auto">
                    <span><?php echo esc_html($form_cta); ?></span>
                    <?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8); ?>
                </button>

                <p class="js-form-msg mt-4 text-[13px] font-medium" role="status" hidden></p>
            </form>
        </div>
    </div>
</section>

<?php get_footer();
