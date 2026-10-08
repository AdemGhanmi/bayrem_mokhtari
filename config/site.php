<?php

/*
|--------------------------------------------------------------------------
| Site configuration (languages, editable settings, editable page sections)
|--------------------------------------------------------------------------
| Everything the dashboard can edit is declared here, so adding a new setting
| or a new page section only needs a new line - no new view or controller.
*/

return [
    'locales' => [
        'en' => ['name' => 'English',  'short' => 'EN', 'dir' => 'ltr', 'flag' => 'EN'],
        'fr' => ['name' => 'Français', 'short' => 'FR', 'dir' => 'ltr', 'flag' => 'FR'],
        'ar' => ['name' => 'العربية',  'short' => 'AR', 'dir' => 'rtl', 'flag' => 'AR'],
    ],
    'default_locale' => 'fr',

    // Settings shown in Dashboard > Settings. type: text | textarea | url | email | image
    // `t` => true means translatable (one value per language).
    'settings' => [
        'Identity' => [
            'site_title'       => ['label' => 'Site title',            'type' => 'text',     't' => true],
            'meta_description' => ['label' => 'SEO description',       'type' => 'textarea', 't' => true],
            'footer_text'      => ['label' => 'Footer tagline',        'type' => 'text',     't' => true],
            'location'         => ['label' => 'Location (footer)',     'type' => 'text',     't' => true],
            'og_image'         => ['label' => 'Social share image',    'type' => 'image'],
        ],
        'Home hero' => [
            'hero_line1'    => ['label' => 'Hero line 1',    'type' => 'text',     't' => true],
            'hero_line2'    => ['label' => 'Hero line 2',    'type' => 'text',     't' => true],
            'hero_line3'    => ['label' => 'Hero line 3 (gold)', 'type' => 'text', 't' => true],
            'hero_subtitle' => ['label' => 'Hero paragraph', 'type' => 'textarea', 't' => true],
            'hero_image'    => ['label' => 'Hero image',     'type' => 'image'],
        ],
        'Page images' => [
            'story_image'    => ['label' => 'Story cover image',    'type' => 'image'],
            'story_portrait' => ['label' => 'Story portrait',       'type' => 'image'],
            'career_image'   => ['label' => 'Career cover image',   'type' => 'image'],
            'journal_image'  => ['label' => 'Journal cover image',  'type' => 'image'],
            'gallery_image'  => ['label' => 'Gallery cover image',  'type' => 'image'],
            'contact_image'  => ['label' => 'Contact portrait',     'type' => 'image'],
        ],
        'Story facts' => [
            'fact_known_as'   => ['label' => 'Known as',    'type' => 'text', 't' => true],
            'fact_born'       => ['label' => 'Born',        'type' => 'text', 't' => true],
            'fact_birthplace' => ['label' => 'Birthplace',  'type' => 'text', 't' => true],
            'fact_nationality'=> ['label' => 'Nationality', 'type' => 'text', 't' => true],
            'fact_markets'    => ['label' => 'Football markets', 'type' => 'text', 't' => true],
        ],
        'Contact & social' => [
            'email'         => ['label' => 'Public email',      'type' => 'email'],
            'instagram'     => ['label' => 'Instagram URL',     'type' => 'url'],
            'instagram_handle' => ['label' => 'Instagram @handle', 'type' => 'text'],
            'youtube'       => ['label' => 'YouTube channel URL','type' => 'url'],
            'youtube_embed' => ['label' => 'Home video (YouTube embed URL)', 'type' => 'url'],
            'facebook'      => ['label' => 'Facebook URL',      'type' => 'url'],
            'linkedin'      => ['label' => 'LinkedIn URL',      'type' => 'url'],
        ],
    ],

    // Editable blocks per public page. Each block has: eyebrow, title, body, cta (all translatable) + image/link.
    'pages' => [
        'home'    => ['hero', 'iconic', 'journey', 'man', 'journal', 'motion', 'cta'],
        'story'   => ['hero', 'intro', 'method', 'timeline', 'cta'],
        'career'  => ['hero', 'journey', 'records'],
        'journal' => ['hero', 'index'],
        'gallery' => ['hero', 'index'],
        'contact' => ['hero', 'form'],
        'global'  => ['footer'],
    ],
];
