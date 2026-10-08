<?php

namespace Database\Seeders;

use App\Models\CareerEntry;
use App\Models\Honour;
use App\Models\MediaItem;
use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Trilingual (EN / FR / AR) starter content.
 *
 * SAFE TO RE-RUN: it never overwrites text an editor changed in the dashboard.
 * It only creates what is missing, and fills a language that is empty or is still an
 * untranslated copy of the English text (the old seeder copied EN into FR and AR).
 */
class ContentSeeder extends Seeder
{
    private function t(string $en, string $fr, string $ar): array
    {
        return ['en' => $en, 'fr' => $fr, 'ar' => $ar];
    }

    /** Merge new translations into existing ones without clobbering real edits. */
    private function merge(?array $old, array $new): array
    {
        $old ??= [];
        $out = [];
        foreach (['en', 'fr', 'ar'] as $l) {
            $cur = trim((string) ($old[$l] ?? ''));
            $untranslatedCopy = $l !== 'en' && $cur !== '' && $cur === trim((string) ($old['en'] ?? '')) && $cur !== trim($new[$l]);
            $out[$l] = ($cur === '' || $untranslatedCopy) ? $new[$l] : $cur;
        }

        return $out;
    }

    private function mergeModel($model, array $fields): void
    {
        foreach ($fields as $f => $v) {
            $model->{$f} = $this->merge($model->{$f}, $v);
        }
        $model->save();
    }

    public function run(): void
    {
        $this->legacySections();
        $this->settings();
        $this->career();
        $this->honours();
        $this->gallery();
        $this->journal();
        $this->videos();
        $this->diplomas();
        $this->sections();
    }

    /** Old keys from the first version of the project are renamed instead of duplicated. */
    private function legacySections(): void
    {
        $map = [
            ['story', 'intro', 'hero'], ['career', 'intro', 'hero'], ['journal', 'intro', 'hero'],
            ['gallery', 'intro', 'hero'], ['contact', 'intro', 'hero'], ['home', 'story', 'man'],
            ['story', 'born', 'card-born'], ['story', 'tunis', 'card-tunis'], ['story', 'saudi', 'card-saudi'],
            ['story', 'egypt', 'card-egypt'], ['story', 'headcoach', 'card-headcoach'], ['story', 'analyst', 'card-analyst'],
        ];
        // Order matters: 'intro' is renamed to 'hero' only if no 'hero' exists yet, then story gets a fresh 'intro'.
        foreach ($map as [$page, $old, $new]) {
            $s = PageSection::where('page_slug', $page)->where('section_key', $old)->first();
            if ($s && ! PageSection::where('page_slug', $page)->where('section_key', $new)->exists()) {
                $s->update(['section_key' => $new]);
            }
        }
        PageSection::whereIn('page_slug', ['honours', 'sources'])->delete();
    }

    private function settings(): void
    {
        $defaults = [
            'site_title' => $this->t('Coach Bayrem Mokhtari', 'Coach Bayrem Mokhtari', 'المدرب بيرم مختاري'),
            'meta_description' => $this->t('Coach Bayrem Mokhtari — football coach, analyst and sports writer.', 'Coach Bayrem Mokhtari — entraîneur de football, analyste et journaliste sportif.', 'المدرب بيرم مختاري — مدرب كرة قدم ومحلل وكاتب رياضي.'),
            'footer_text' => $this->t('Football · Coaching · Analysis · Media', 'Football · Coaching · Analyse · Médias', 'كرة القدم · التدريب · التحليل · الإعلام'),
            'location' => $this->t('El Kef · Tunisia', 'El Kef · Tunisie', 'الكاف · تونس'),
            'hero_line1' => $this->t('READ', 'LIS', 'اقرأ'),
            'hero_line2' => $this->t('THE GAME.', 'LE JEU.', 'اللعبة.'),
            'hero_line3' => $this->t('LEAD IT.', 'DIRIGE-LE.', 'وقدها.'),
            'hero_subtitle' => $this->t(
                'From El Kef to Saudi Arabia, Egypt, Qatar and Oman: sixteen years of coaching, assistant roles and football analysis built on preparation, observation and communication.',
                "D'El Kef à l'Arabie saoudite, l'Égypte, le Qatar et Oman : seize ans de coaching, de postes d'adjoint et d'analyse football fondés sur la préparation, l'observation et la communication.",
                'من الكاف إلى السعودية ومصر وقطر وعُمان: ستة عشر عامًا من التدريب والعمل كمساعد والتحليل الكروي، قائمة على الإعداد والملاحظة والتواصل.'),
            'fact_known_as' => $this->t('Bayrem Mokhtari', 'Bayrem Mokhtari', 'بيرم مختاري'),
            'fact_born' => $this->t('4 January 1981', '4 janvier 1981', '4 يناير 1981'),
            'fact_birthplace' => $this->t('El Kef, Tunisia', 'El Kef, Tunisie', 'الكاف، تونس'),
            'fact_nationality' => $this->t('Tunisian', 'Tunisienne', 'تونسي'),
            'fact_markets' => $this->t('Tunisia · Saudi Arabia · Egypt · Qatar · Oman', 'Tunisie · Arabie saoudite · Égypte · Qatar · Oman', 'تونس · السعودية · مصر · قطر · عُمان'),
        ];
        foreach ($defaults as $key => $val) {
            $row = SiteSetting::firstOrNew(['key' => $key]);
            $row->value = $this->merge(is_array($row->value) ? $row->value : null, $val);
            $row->save();
        }

        $plain = [
            'hero_image' => 'assets/images/profile.png', 'story_image' => 'assets/images/profile.png',
            'story_portrait' => 'assets/images/portrait-original.jpg', 'career_image' => 'assets/images/portrait-original.jpg',
            'journal_image' => 'assets/images/1535040556641.jpg', 'gallery_image' => 'assets/images/touchline-02.jpg',
            'contact_image' => 'assets/images/coach-portrait.png', 'og_image' => 'assets/images/profile.png',
            'email' => 'elitesport.tn@gmail.com', 'instagram_handle' => '@bayremmokhtari_officiel',
            'instagram' => 'https://www.instagram.com/bayremmokhtari_officiel/',
            'youtube' => 'https://www.youtube.com/@coachbayremmokhtari/videos',
            'facebook' => 'https://www.facebook.com/share/v/1DhjgsFZ3h/',
            'linkedin' => 'https://www.linkedin.com/in/bayrem-mokhtari-00129216b/',
            'youtube_embed' => 'https://www.youtube-nocookie.com/embed/Ygppq3yjyT0?rel=0&modestbranding=1',
        ];
        foreach ($plain as $key => $val) {
            if (! SiteSetting::where('key', $key)->exists()) {
                SiteSetting::put($key, $val);
            }
        }
    }

    private function career(): void
    {
        // [period, club en/fr/ar, country, role en/fr/ar, logo]
        $rows = [
            ['2005 — 2007', ['Olympique du Kef', 'Olympique du Kef', 'أولمبيك الكاف'], 'Tunisia', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/olympique-kef.png'],
            ['2007 — 2008', ['Espérance Sportive de Tunis', 'Espérance Sportive de Tunis', 'الترجي الرياضي التونسي'], 'Tunisia', ['Olympic squad coach', "Entraîneur de l'équipe olympique", 'مدرب الفريق الأولمبي'], 'assets/logos/esperance.png'],
            ['2008 — 2009', ['Tunisia Youth National Team', 'Équipe nationale jeunes de Tunisie', 'منتخب تونس للشباب'], 'Tunisia', ['Youth national setup', 'Encadrement des équipes jeunes', 'منظومة المنتخبات الشابة'], 'assets/logos/tunisia.png'],
            ['2009 — 2010', ['Al Jeel', 'Al Jeel', 'الجيل'], 'Saudi Arabia', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/al-jil.png'],
            ['2010 — 2012', ['Al Fateh', 'Al Fateh', 'الفتح'], 'Saudi Arabia', ['Assistant coach', 'Entraîneur adjoint', 'مدرب مساعد'], 'assets/logos/al-fateh.png'],
            ['2013 — 2014', ['Smouha', 'Smouha', 'سموحة'], 'Egypt', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/smouha.png'],
            ['2014 — 2015', ['Al Ittihad', 'Al Ittihad', 'الاتحاد'], 'Saudi Arabia', ['Assistant / first-team', 'Adjoint · équipe première', 'مساعد · الفريق الأول'], 'assets/logos/al-ittihad.png'],
            ['2015 — 2016', ['Al Shamal', 'Al Shamal', 'الشمال'], 'Qatar', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/al-shamal.png'],
            ['2016 — 2017', ['Al Ittihad', 'Al Ittihad', 'الاتحاد'], 'Saudi Arabia', ['Technical / assistant role', 'Rôle technique / adjoint', 'دور فني / مساعد'], 'assets/logos/al-ittihad.png'],
            ['2017 — 2018', ['Damac', 'Damac', 'ضمك'], 'Saudi Arabia', ['Caretaker / head coach', 'Entraîneur principal (intérim)', 'مدرب رئيسي (مؤقت)'], 'assets/logos/damac.png'],
            ['2018 — 2019', ['Al Orouba', 'Al Orouba', 'العروبة'], 'Oman', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/al-orouba-oman.gif'],
            ['2019 — 2021', ['Al Orobah', 'Al Orobah', 'العروبة'], 'Saudi Arabia', ['Head coach', 'Entraîneur principal', 'مدرب رئيسي'], 'assets/logos/al-orobah-saudi.png'],
        ];
        $countries = ['Tunisia' => ['Tunisie', 'تونس'], 'Saudi Arabia' => ['Arabie saoudite', 'السعودية'], 'Egypt' => ['Égypte', 'مصر'], 'Qatar' => ['Qatar', 'قطر'], 'Oman' => ['Oman', 'عُمان']];
        foreach ($rows as $i => [$period, $club, $country, $role, $logo]) {
            [$cfr, $car] = $countries[$country];
            $desc = $this->t(
                "{$role[0]} at {$club[0]} ({$country}), {$period}.",
                "{$role[1]} à {$club[1]} ({$cfr}), {$period}.",
                "{$role[2]} في {$club[2]} ({$car})، {$period}."
            );
            $e = CareerEntry::firstOrNew(['period' => $period, 'country' => $country]);
            $isNew = ! $e->exists;
            if ($isNew) {
                $e->logo = $logo;
                $e->sort_order = $i;
                $e->is_active = true;
            }
            $e->club ??= null;
            $this->mergeModel($e, [
                'club' => $this->t(...$club),
                'role' => $this->t(...$role),
                'description' => $desc,
            ]);
        }
    }

    private function honours(): void
    {
        $rows = [
            [$this->t('Al Fateh chapter', 'Étape Al Fateh', 'محطة الفتح'), $this->t('Saudi league title', 'Titre de champion saoudien', 'لقب الدوري السعودي'), '2012'],
            [$this->t('Asian campaign', 'Campagne asiatique', 'المشاركة الآسيوية'), $this->t('Involvement in an Asian competition run', "Participation à un parcours en compétition asiatique", 'المشاركة في مسيرة بطولة آسيوية'), '2015'],
            [$this->t('Football analysis', 'Analyse football', 'التحليل الكروي'), $this->t('Television, radio and press analysis', 'Analyses à la télévision, à la radio et dans la presse', 'تحليلات تلفزيونية وإذاعية وصحفية'), ''],
        ];
        foreach ($rows as $i => [$title, $desc, $year]) {
            $h = Honour::firstOrNew(['sort_order' => $i]);
            if (! $h->exists) {
                $h->year = $year;
                $h->is_active = true;
            }
            $this->mergeModel($h, ['title' => $title, 'description' => $desc]);
        }
    }

    private function gallery(): void
    {
        $rows = [
            ['coach-portrait.png', ['Coach Bayrem Mokhtari', 'Coach Bayrem Mokhtari', 'المدرب بيرم مختاري'], 'media'],
            ['coach-pointing.png', ['Coach on the touchline', 'Le coach en bord de terrain', 'المدرب على خط الملعب'], 'matchday'],
            ['touchline-01.jpg', ['Touchline', 'Ligne de touche', 'خط الملعب'], 'matchday'],
            ['touchline-02.jpg', ['Matchday', 'Jour de match', 'يوم المباراة'], 'matchday'],
            ['training-01.jpg', ['Training', 'Entraînement', 'التدريب'], 'training'],
            ['portrait-original.jpg', ['Portrait', 'Portrait', 'صورة شخصية'], 'media'],
            ['nesmasprt.jpg', ['Nessma Sport', 'Nessma Sport', 'نسمة سبورت'], 'media'],
            ['aljanoubyasport.jpg', ['Al Janoubiya Sport', 'Al Janoubiya Sport', 'الجنوبية سبورت'], 'media'],
            ['ittihad-01.jpg', ['Al Ittihad', 'Al Ittihad', 'الاتحاد'], 'matchday'],
        ];
        foreach ($rows as $i => [$file, $title, $cat]) {
            $m = MediaItem::firstOrNew(['type' => 'gallery', 'image' => 'assets/images/'.$file]);
            if (! $m->exists) {
                $m->category = $cat;
                $m->sort_order = $i;
                $m->is_active = true;
            }
            $this->mergeModel($m, ['title' => $this->t(...$title)]);
        }
    }

    private function journal(): void
    {
        $rows = [
            ['profile', '2026', 'Global Council of Sport Science', 'https://gcss.se/member-details.php?serialnumber=58310104062020&username=Bayrem04', 'assets/images/1535040556641.jpg', null,
                ['Bayrem Mokhtari — Football coach and analyst', 'Bayrem Mokhtari — Entraîneur de football et analyste', 'بيرم مختاري — مدرب كرة قدم ومحلل'],
                ['Coach, analyst and sports writer with a documented path across Tunisia, Saudi Arabia, Egypt, Qatar and Oman.', "Entraîneur, analyste et journaliste sportif, avec un parcours documenté en Tunisie, en Arabie saoudite, en Égypte, au Qatar et à Oman.", 'مدرب ومحلل وكاتب رياضي بمسيرة موثقة بين تونس والسعودية ومصر وقطر وعُمان.']],
            ['career', '2023', 'Arabica', 'https://3rabica.org/%D8%A8%D9%8A%D8%B1%D9%85_%D9%85%D8%AE%D8%AA%D8%A7%D8%B1%D9%8A', 'assets/images/portrait-original.jpg', null,
                ['A career across Tunisia and the Gulf', 'Une carrière entre la Tunisie et le Golfe', 'مسيرة بين تونس والخليج'],
                ['A documented coaching path covering Tunisia, Saudi Arabia, Egypt, Qatar and Oman.', 'Un parcours d\'entraîneur documenté couvrant la Tunisie, l\'Arabie saoudite, l\'Égypte, le Qatar et Oman.', 'مسيرة تدريبية موثقة تشمل تونس والسعودية ومصر وقطر وعُمان.']],
            ['analysis', '2023', 'Arabica', 'https://3rabica.org/%D8%A8%D9%8A%D8%B1%D9%85_%D9%85%D8%AE%D8%AA%D8%A7%D8%B1%D9%8A', 'assets/images/nesmasprt.jpg', null,
                ['From the touchline to football analysis', "De la ligne de touche à l'analyse football", 'من خط الملعب إلى التحليل الكروي'],
                ['Published biographical material records Bayrem Mokhtari’s work in television, radio and sports press analysis.', "Des sources biographiques publiées relatent le travail de Bayrem Mokhtari en analyse à la télévision, à la radio et dans la presse sportive.", 'تذكر مواد سيرة منشورة عمل بيرم مختاري في التحليل التلفزيوني والإذاعي والصحافة الرياضية.']],
            ['matchday', '2026', 'Bayrem Archive', null, 'assets/images/touchline-01.jpg', null,
                ['The touchline archive', 'Les archives de la ligne de touche', 'أرشيف خط الملعب'],
                ['A visual football archive: coaching moments, matchday preparation and the detail around the pitch.', "Une archive visuelle du football : moments de coaching, préparation des matchs et détails autour du terrain.", 'أرشيف كروي مرئي: لحظات التدريب وتحضيرات يوم المباراة وتفاصيل ما حول الملعب.']],
            ['video', '2026', 'Bayrem TV', null, 'assets/images/b1.jpg', 'assets/video/b1.mp4',
                ['Bayrem TV — in motion', 'Bayrem TV — en mouvement', 'Bayrem TV — في الحركة'],
                ['A video chapter from the project archive. Replace it, reorder it or add more video stories from the dashboard.', "Un chapitre vidéo des archives du projet. Remplacez-le, réordonnez-le ou ajoutez d'autres vidéos depuis le tableau de bord.", 'فصل مصوّر من أرشيف المشروع. يمكنك استبداله أو إعادة ترتيبه أو إضافة فيديوهات أخرى من لوحة التحكم.']],
            ['career', '2026', 'Career archive', 'https://en.wikipedia.org/wiki/Al-Orobah_FC', 'assets/images/coach-pointing.png', null,
                ['Al Orobah — a coaching chapter', 'Al Orobah — une étape d\'entraîneur', 'العروبة — محطة تدريبية'],
                ['A documented chapter in the Saudi and Omani coaching journey.', "Une étape documentée du parcours d'entraîneur en Arabie saoudite et à Oman.", 'محطة موثقة في المسيرة التدريبية بين السعودية وعُمان.']],
        ];
        foreach ($rows as $i => [$cat, $year, $source, $url, $img, $video, $title, $desc]) {
            // Matched by English title (the old seeder matched on external_url, which merged items that had no URL).
            $m = MediaItem::where('type', 'journal')->get()->first(fn ($x) => ($x->title['en'] ?? '') === $title[0]) ?? new MediaItem(['type' => 'journal']);
            if (! $m->exists) {
                $m->fill(['category' => $cat, 'year' => $year, 'source_name' => $source, 'external_url' => $url, 'image' => $img, 'video_url' => $video, 'sort_order' => $i, 'is_active' => true]);
            }
            $this->mergeModel($m, ['title' => $this->t(...$title), 'description' => $this->t(...$desc)]);
        }
    }

    private function videos(): void
    {
        $rows = [
            ['https://www.youtube.com/@coachbayremmokhtari/videos', ['Bayrem TV', 'Bayrem TV', 'Bayrem TV'], ['Official YouTube channel', 'Chaîne YouTube officielle', 'قناة يوتيوب الرسمية']],
            ['https://www.youtube.com/watch?v=Ygppq3yjyT0', ['Latest video', 'Dernière vidéo', 'آخر فيديو'], ['Coach Bayrem Mokhtari', 'Coach Bayrem Mokhtari', 'المدرب بيرم مختاري']],
        ];
        foreach ($rows as $i => [$url, $title, $desc]) {
            $m = MediaItem::firstOrNew(['type' => 'video', 'video_url' => $url]);
            if (! $m->exists) {
                $m->fill(['external_url' => $url, 'source_name' => 'YouTube', 'sort_order' => $i, 'is_active' => true]);
            }
            $this->mergeModel($m, ['title' => $this->t(...$title), 'description' => $this->t(...$desc)]);
        }
    }

    private function diplomas(): void
    {
        \Illuminate\Support\Facades\Artisan::call('assets:normalize-diplomas');
        $files = glob(public_path('assets/diplomas/diploma-*.jpg')) ?: [];
        sort($files, SORT_NATURAL);
        foreach ($files as $i => $file) {
            $m = MediaItem::firstOrNew(['type' => 'diploma', 'image' => 'assets/diplomas/'.basename($file)]);
            if (! $m->exists) {
                $m->fill(['category' => 'diploma', 'sort_order' => $i, 'is_active' => true]);
            }
            $n = $i + 1;
            $this->mergeModel($m, ['title' => $this->t("Diploma $n", "Diplôme $n", "شهادة $n")]);
        }
    }

    private function sections(): void
    {
        $s = fn ($page, $key, $order, $eyebrow = null, $title = null, $body = null, $cta = null) => [$page, $key, $order, $eyebrow, $title, $body, $cta];
        $rows = [
            // ---- home
            $s('home', 'hero', 0, $this->t('BAYREM MOKHTARI', 'BAYREM MOKHTARI', 'بيرم مختاري')),
            $s('home', 'iconic', 1, $this->t('01 · ICONIC MOMENTS', '01 · MOMENTS MARQUANTS', '01 · لحظات لا تُنسى'), $this->t('Only Bayrem.', 'Seulement Bayrem.', 'بيرم فقط.'),
                $this->t('Moments from a career lived between the touchline, the training ground and the world of football analysis.', "Des moments d'une carrière vécue entre la ligne de touche, le terrain d'entraînement et l'analyse du football.", 'لحظات من مسيرة عاشها بين خط الملعب وميدان التدريب وعالم التحليل الكروي.')),
            $s('home', 'journey', 2, $this->t('02 · THE JOURNEY', '02 · LE PARCOURS', '02 · الرحلة'), $this->t('Great clubs. One path.', 'De grands clubs. Un seul chemin.', 'أندية كبيرة. طريق واحد.'),
                $this->t('From Olympique du Kef to Saudi Arabia, Qatar, Oman and Egypt.', "De l'Olympique du Kef à l'Arabie saoudite, au Qatar, à Oman et à l'Égypte.", 'من أولمبيك الكاف إلى السعودية وقطر وعُمان ومصر.'), $this->t('VIEW CAREER', 'VOIR LA CARRIÈRE', 'عرض المسيرة')),
            $s('home', 'man', 3, $this->t('03 · BEYOND THE TOUCHLINE', '03 · AU-DELÀ DE LA LIGNE DE TOUCHE', '03 · ما وراء خط الملعب'), $this->t('The man. The mentality.', "L'homme. La mentalité.", 'الرجل. العقلية.'),
                $this->t('Coach, analyst and sports writer — with a career shaped by different football cultures.', 'Entraîneur, analyste et journaliste sportif — une carrière façonnée par différentes cultures du football.', 'مدرب ومحلل وكاتب رياضي — بمسيرة صقلتها ثقافات كروية مختلفة.'), $this->t('HIS STORY', 'SON HISTOIRE', 'قصته')),
            $s('home', 'journal', 4, $this->t('04 · THE JOURNAL', '04 · LE JOURNAL', '04 · المجلة'), $this->t('Latest moments.', 'Derniers moments.', 'أحدث اللحظات.'), null, $this->t('ALL STORIES', 'TOUS LES ARTICLES', 'كل المقالات')),
            $s('home', 'motion', 5, $this->t('IN MOTION', 'EN MOUVEMENT', 'في الحركة'), $this->t('Watch the latest videos.', 'Regardez les dernières vidéos.', 'شاهد أحدث الفيديوهات.'),
                $this->t('Football analysis, coaching and commentary, straight from the YouTube channel.', 'Analyse football, coaching et commentaires, directement depuis la chaîne YouTube.', 'تحليل كروي وتدريب وتعليق، مباشرة من قناة يوتيوب.'), $this->t('OPEN THE CHANNEL', 'OUVRIR LA CHAÎNE', 'افتح القناة')),
            $s('home', 'cta', 6, null, null, null, $this->t("LET'S TALK", 'PARLONS-EN', 'لنتحدث')),
            // ---- story
            $s('story', 'hero', 0, $this->t('01 / THE STORY', "01 / L'HISTOIRE", '01 / القصة'), $this->t('The Story', "L'histoire", 'القصة')),
            $s('story', 'intro', 1, $this->t('EL KEF · TUNISIA', 'EL KEF · TUNISIE', 'الكاف · تونس'), $this->t('It started with the game.', 'Tout a commencé par le jeu.', 'بدأ كل شيء باللعبة.'),
                $this->t('A long football journey: coaching environments, first-team experience, youth development, analysis and media.', "Un long parcours dans le football : environnements d'entraînement, expérience en équipe première, formation des jeunes, analyse et médias.", 'رحلة كروية طويلة: بيئات تدريبية وتجربة مع الفريق الأول وتكوين الشباب والتحليل والإعلام.')),
            $s('story', 'method', 2, $this->t('THE METHOD', 'LA MÉTHODE', 'المنهج')),
            $s('story', 'timeline', 90, $this->t('THE TIMELINE', 'LA CHRONOLOGIE', 'الخط الزمني'), $this->t('Year after year.', 'Année après année.', 'سنة بعد سنة.')),
            $s('story', 'cta', 99, null, null, null, $this->t("LET'S TALK", 'PARLONS-EN', 'لنتحدث')),
            $s('story', 'card-born', 10, null, $this->t('Born in El Kef', 'Né à El Kef', 'وُلد في الكاف'), $this->t('Bayrem Mokhtari was born in 1981 in El Kef, in north-western Tunisia.', 'Bayrem Mokhtari est né en 1981 à El Kef, dans le nord-ouest de la Tunisie.', 'وُلد بيرم مختاري سنة 1981 في الكاف، في شمال غرب تونس.')),
            $s('story', 'card-tunis', 11, null, $this->t('Tunis and the youth teams', 'Tunis et les équipes de jeunes', 'تونس ومنتخبات الشباب'), $this->t('In 2007 he joined Espérance Sportive de Tunis to coach the Olympic squad, then worked in the Tunisia youth national setup.', "En 2007, il rejoint l'Espérance Sportive de Tunis pour entraîner l'équipe olympique, puis travaille au sein des équipes nationales jeunes de Tunisie.", 'في 2007 انضم إلى الترجي الرياضي التونسي لتدريب الفريق الأولمبي، ثم عمل ضمن منظومة المنتخبات الشابة التونسية.')),
            $s('story', 'card-saudi', 12, null, $this->t('Saudi Arabia: the breakthrough years', "Arabie saoudite : les années de percée", 'السعودية: سنوات الانطلاقة'), $this->t('In 2009 he moved to Saudi Arabia and built a long professional path across several clubs.', "En 2009, il s'installe en Arabie saoudite et construit un long parcours professionnel dans plusieurs clubs.", 'في 2009 انتقل إلى السعودية وبنى مسارًا مهنيًا طويلًا عبر عدة أندية.')),
            $s('story', 'card-egypt', 13, null, $this->t('Egypt and Al Ittihad', 'Égypte et Al Ittihad', 'مصر والاتحاد'), $this->t('After a season at Smouha, he joined Al Ittihad in Jeddah as assistant coach.', 'Après une saison à Smouha, il rejoint Al Ittihad à Djeddah comme entraîneur adjoint.', 'بعد موسم في سموحة، انضم إلى الاتحاد في جدة كمدرب مساعد.')),
            $s('story', 'card-headcoach', 14, null, $this->t('Head coach in three countries', 'Entraîneur principal dans trois pays', 'مدرب رئيسي في ثلاثة بلدان'), $this->t('He later coached in Qatar, Saudi Arabia and Oman.', 'Il a ensuite entraîné au Qatar, en Arabie saoudite et à Oman.', 'درّب لاحقًا في قطر والسعودية وعُمان.')),
            $s('story', 'card-analyst', 15, null, $this->t('Analyst, writer, founder', 'Analyste, auteur, fondateur', 'محلل وكاتب ومؤسس'), $this->t('Alongside the touchline, he has worked as a football analyst and sports writer and leads Elite Sports Innovation.', "En parallèle du terrain, il travaille comme analyste football et journaliste sportif et dirige Elite Sports Innovation.", 'إلى جانب خط الملعب، عمل محللًا كرويًا وكاتبًا رياضيًا ويدير Elite Sports Innovation.')),
            // ---- career
            $s('career', 'hero', 0, $this->t('02 / THE CAREER', '02 / LA CARRIÈRE', '02 / المسيرة'), $this->t('The Career', 'La carrière', 'المسيرة'),
                $this->t('Twelve documented chapters across five countries, from El Kef to the Gulf.', 'Douze étapes documentées dans cinq pays, d\'El Kef au Golfe.', 'اثنتا عشرة محطة موثقة في خمسة بلدان، من الكاف إلى الخليج.')),
            $s('career', 'journey', 1, $this->t('THE JOURNEY', 'LE PARCOURS', 'الرحلة'), $this->t('Every club. One path.', 'Chaque club. Un seul chemin.', 'كل نادٍ. طريق واحد.')),
            $s('career', 'records', 2, $this->t('THE RECORD', 'LE PALMARÈS', 'السجل'), $this->t('Honours & diplomas', 'Palmarès et diplômes', 'الإنجازات والشهادات'),
                $this->t('Professional highlights and coaching qualifications, presented as part of the career.', "Faits marquants et qualifications d'entraîneur, présentés dans le cadre de la carrière.", 'أبرز المحطات المهنية والمؤهلات التدريبية كجزء من المسيرة.')),
            // ---- journal
            $s('journal', 'hero', 0, $this->t('03 / THE JOURNAL', '03 / LE JOURNAL', '03 / المجلة'), $this->t('The Journal', 'Le journal', 'المجلة'),
                $this->t('Articles, interviews, analysis, images and video from the football journey.', 'Articles, interviews, analyses, images et vidéos du parcours football.', 'مقالات وحوارات وتحليلات وصور وفيديو من الرحلة الكروية.')),
            $s('journal', 'index', 1, $this->t('LATEST STORIES', 'DERNIERS ARTICLES', 'أحدث المقالات'), $this->t('The archive', 'Les archives', 'الأرشيف')),
            // ---- gallery
            $s('gallery', 'hero', 0, $this->t('04 / THE GALLERY', '04 / LA GALERIE', '04 / المعرض'), $this->t('In focus', "Dans l'objectif", 'تحت الضوء'),
                $this->t('Portraits, touchline moments, training sessions and Al Ittihad.', "Portraits, moments en bord de terrain, séances d'entraînement et Al Ittihad.", 'صور شخصية ولحظات من خط الملعب وحصص تدريبية والاتحاد.')),
            $s('gallery', 'index', 1, $this->t('THE ARCHIVE', 'LES ARCHIVES', 'الأرشيف'), $this->t('The images', 'Les images', 'الصور')),
            // ---- contact
            $s('contact', 'hero', 0, $this->t('06 / GET IN TOUCH', '06 / CONTACT', '06 / تواصل'), $this->t("Let's talk", 'Parlons-en', 'لنتحدث'),
                $this->t('For coaching, football analysis, media, speaking or any professional enquiry, write directly. Every message is read.', "Pour le coaching, l'analyse football, les médias, les conférences ou toute demande professionnelle, écrivez directement. Chaque message est lu.", 'للتدريب وتحليل كرة القدم والإعلام والمحاضرات أو أي استفسار مهني، راسلنا مباشرة. تتم قراءة كل رسالة.')),
            $s('contact', 'form', 1, $this->t('SEND A MESSAGE', 'ENVOYER UN MESSAGE', 'أرسل رسالة'), $this->t('Have a project in mind?', 'Un projet en tête ?', 'هل لديك مشروع؟'),
                $this->t('Write directly. Every professional message is reviewed.', 'Écrivez-nous directement. Chaque message professionnel est lu.', 'اكتب مباشرة. تتم مراجعة كل رسالة مهنية.'), $this->t('SEND MESSAGE', 'ENVOYER LE MESSAGE', 'إرسال الرسالة')),
            // ---- global
            $s('global', 'footer', 0, null, null, $this->t('Have a project or professional enquiry?', 'Un projet ou une demande professionnelle ?', 'هل لديك مشروع أو استفسار مهني؟'), $this->t("LET'S TALK", 'PARLONS-EN', 'لنتحدث')),
        ];
        foreach ($rows as [$page, $key, $order, $eyebrow, $title, $body, $cta]) {
            $sec = PageSection::firstOrNew(['page_slug' => $page, 'section_key' => $key]);
            if (! $sec->exists) {
                $sec->sort_order = $order;
                $sec->is_active = true;
                $sec->type = 'text';
            }
            $blank = $this->t('', '', '');
            $this->mergeModel($sec, ['eyebrow' => $eyebrow ?? $blank, 'title' => $title ?? $blank, 'body' => $body ?? $blank, 'cta' => $cta ?? $blank]);
        }
    }
}
