import { ref, computed } from 'vue';

const locale = ref(localStorage.getItem('locale') || 'en');

const messages = {
    en: {
        home: 'Home',
        about: 'About',
        contacts: 'Contacts',
        blog: 'Blog',
        market: 'Market',
        login: 'Login',
        logout: 'Logout',
        profile: 'Profile',
        cart: 'Cart',
        // Banner
        banner: 'Limited-Time Offer! Get 40% OFF on all items!',
        // Testimonial
        testimonial_greeting: 'Dear Valued Customer,',
        testimonial_p1: 'Welcome to COMTEM — where cutting-edge technology meets exceptional service. Whether you\'re a tech enthusiast, a professional seeking reliable solutions, or simply looking for the latest innovations, we\'re here to support you every step of the way.',
        testimonial_p2: 'Our commitment goes beyond products. We focus on quality, reliability, and customer satisfaction to ensure you always get technology you can trust.',
        testimonial_cite: '— COMTEM DEV GROUP',
        // Partners
        partners_title: 'Our partners',
        partners_p1: 'Best quality product at an affordable price.',
        partners_p2: 'Limited time offer, hurry up!',
        partners_p3: 'Top choice among customers this month.',
        partners_p4: 'Special discount for a limited period.',
        // Contacts page
        contacts_title: 'Contacts',
        // Comments
        comments_title: 'Comments',
        comment_placeholder: 'Write a comment…',
        comment_hint: 'Ctrl + Enter to send',
        comment_post: 'Post',
        comment_login: 'Please log in to comment.',
        comment_empty: 'No comments yet. Be the first!',
        // Chat
        chat_welcome: 'Hi! Ask me anything about COMTEM.',
        chat_placeholder: 'Ask me anything…',
        // Beta popup
        beta_title: 'Beta Version',
        beta_body: 'This website is currently in beta testing. Some features may not work as expected.',
        beta_btn: 'Got it',
        // FAQ
        faq_title: 'Frequently Asked Questions',
        faq_subtitle: 'Find answers to common questions about our product and services',
        faq_search: 'Search questions...',
        faq_all_categories: 'All Categories',
        faq_no_results: 'No questions found',
        faq_no_results_hint: 'Try adjusting your search or filter',
    },
    lv: {
        home: 'Sākums',
        about: 'Par mums',
        contacts: 'Kontakti',
        blog: 'Blogs',
        market: 'Veikals',
        login: 'Ieiet',
        logout: 'Iziet',
        profile: 'Profils',
        cart: 'Grozs',
        banner: 'Ierobežots piedāvājums! Saņem 40% ATLAIDI visām precēm!',
        testimonial_greeting: 'Cienījamais klient,',
        testimonial_p1: 'Laipni lūdzam COMTEM — kur modernās tehnoloģijas satiekas ar izcilu servisu. Neatkarīgi no tā, vai esat tehnoloģiju entuziasts, profesionālis, kas meklē uzticamus risinājumus, vai vienkārši interesējaties par jaunākajām inovācijām — mēs esam šeit, lai jūs atbalstītu katrā solī.',
        testimonial_p2: 'Mūsu apņemšanās pārsniedz produktus. Mēs koncentrējamies uz kvalitāti, uzticamību un klientu apmierinātību, lai nodrošinātu, ka jūs vienmēr saņemat tehnoloģijas, kurām varat uzticēties.',
        testimonial_cite: '— COMTEM IZSTRĀDES GRUPA',
        partners_title: 'Mūsu partneri',
        partners_p1: 'Labākā kvalitāte par pieejamu cenu.',
        partners_p2: 'Ierobežots laika piedāvājums, steidzies!',
        partners_p3: 'Šī mēneša populārākā izvēle klientu vidū.',
        partners_p4: 'Īpaša atlaide ierobežotam laikam.',
        contacts_title: 'Kontakti',
        comments_title: 'Komentāri',
        comment_placeholder: 'Raksti komentāru…',
        comment_hint: 'Ctrl + Enter, lai nosūtītu',
        comment_post: 'Publicēt',
        comment_login: 'Lūdzu, piesakies, lai komentētu.',
        comment_empty: 'Vēl nav komentāru. Esi pirmais!',
        chat_welcome: 'Sveiki! Jautājiet man jebko par COMTEM.',
        chat_placeholder: 'Jautājiet jebko…',
        beta_title: 'Beta versija',
        beta_body: 'Šī vietne pašlaik tiek testēta beta versijā. Dažas funkcijas var nedarboties pareizi.',
        beta_btn: 'Sapratu',
        faq_title: 'Biežāk uzdotie jautājumi',
        faq_subtitle: 'Atrodiet atbildes uz biežākajiem jautājumiem par mūsu produktiem un pakalpojumiem',
        faq_search: 'Meklēt jautājumus...',
        faq_all_categories: 'Visas kategorijas',
        faq_no_results: 'Jautājumi nav atrasti',
        faq_no_results_hint: 'Mēģini pielāgot meklēšanu vai filtru',
    }
};

export function useTranslation() {
    const t = (key) => messages[locale.value]?.[key] ?? messages.en[key] ?? key;

    const toggleLocale = () => {
        locale.value = locale.value === 'en' ? 'lv' : 'en';
        localStorage.setItem('locale', locale.value);
    };

    return { locale, t, toggleLocale };
}
