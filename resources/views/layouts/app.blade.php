<!DOCTYPE html>

<html lang="en" class="light">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <link href="dist/images/security.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Icewall admin for weeding app">
    <meta name="keywords" content="Rapid Tech Property">
    <meta name="author" content="Gaston Delimond Dev">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <title>Salama Plateforme</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/tom-select.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/zoom-vanilla.css") }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tippy.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/litepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/themes/rubick/side-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/toastify.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/js/vendors/sweetalert2/sweetalert2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/highlight.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/tippy.css") }}">
    <link rel="stylesheet" href="{{ asset('dist/css/components/mobile-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}">
    @stack("styles")

    <!-- END: CSS Assets-->
</head>
<!-- BEGIN: JS Assets-->
<body>

<div class="rubick px-5 sm:px-8 py-5 before:content-[''] before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 dark:before:from-darkmode-800 dark:before:to-darkmode-800 before:fixed before:inset-0 before:z-[-1]">
    <!-- BEGIN: Mobile Menu -->
    @include("components.mobile_menu")
    <!-- END: Mobile Menu -->
    <div class="mt-[4.7rem] flex md:mt-0">
        <!-- BEGIN: Top Bar -->
        @include("components.side_nav")
        <!-- END: Top Bar -->
        @yield("content")
    </div>
</div>

<div id="supervision--toast" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
    <img title="photo agent"  src="assets/images/profil-2.png" alt="photo agent" style="width:70px; height:70px; object-fit: cover; object-position: center;" class="notif-photo rounded-lg border-2 border-white shadow-md">
    <div class="ml-4 mr-4">
        <div class="font-medium"> <span class="notif-matricule font-bold"></span> <span class="notif-nom font-semibold text-base">Nom Superviseur</span></div>
        <div class="text-slate-500 mt-1 notif-station">notif-station</div>
        <div class="text-slate-500 mt-1 text-xs notif-heure">à --:--</div>
    </div>
</div>


<!-- BEGIN: Js assets -->
 <!-- @vite('resources/js/app.js') -->
<script src="{{ asset('dist/js/vendors/dom.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tailwind-merge.js') }}"></script>
<script src="{{ asset("dist/js/vendors/tom-select.js") }}"></script>
<script src="{{ asset("dist/js/vendors/image-zoom.js") }}"></script>
<script src="{{ asset("dist/js/vendors/alert.js") }}"></script>
<script src="{{ asset('dist/js/vendors/lucide.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tab.js') }}"></script>
<script src="{{ asset('dist/js/vendors/accordion.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tippy.js') }}"></script>
<script src="{{ asset('dist/js/vendors/dayjs.js') }}"></script>
<script src="{{ asset('dist/js/vendors/litepicker.js') }}"></script>
<script src="{{ asset('dist/js/vendors/popper.js') }}"></script>
<script src="{{ asset('dist/js/vendors/dropdown.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tiny-slider.js') }}"></script>
<script src="{{ asset('dist/js/vendors/transition.js') }}"></script>
<script src="{{ asset('dist/js/vendors/chartjs.js') }}"></script>
<script src="{{ asset('dist/js/vendors/leaflet-map.js') }}"></script>
<script src="{{ asset("dist/js/vendors/toastify.js") }}"></script>
<script src="{{ asset("dist/js/vendors/pristine.js") }}"></script>
<script src="{{ asset('dist/js/vendors/axios.js') }}"></script>
<script src="{{ asset('dist/js/utils/colors.js') }}"></script>
<script src="{{ asset('dist/js/utils/helper.js') }}"></script>
<script src="{{ asset('dist/js/vendors/simplebar.js') }}"></script>
<script src="{{ asset('dist/js/vendors/modal.js') }}"></script>
<script src="{{ asset('dist/js/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('dist/js/components/base/theme-color.js') }}"></script>
<script src="{{ asset('dist/js/components/base/lucide.js') }}"></script>
<script src="{{ asset('dist/js/components/base/tippy.js') }}"></script>
<script src="{{ asset('dist/js/components/base/litepicker.js') }}"></script>
<script src="{{ asset('dist/js/components/report-line-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-pie-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-donut-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-donut-chart-1.js') }}"></script>
<script src="{{ asset('dist/js/components/simple-line-chart-1.js') }}"></script>
<script src="{{ asset('dist/js/components/base/tiny-slider.js') }}"></script>
<script src="{{ asset('dist/js/themes/rubick.js') }}"></script>
<script src="{{ asset('dist/js/components/mobile-menu.js') }}"></script>
<script src="{{ asset('dist/js/components/themes/rubick/top-bar.js') }}"></script>
<script>
async function checkNotifications() {
    try {
        const res = await fetch('/notifications.push');
        const data = await res.json();
        
        if (data.new) {
            const n = data.data;
            let message = '';

            if (n.category === 'sup') {
                const action = n.type === 'arrivée' 
                    ? 'est arrivé à la station' 
                    : 'est parti de la station';
                message = `Le superviseur ${n.nom_superviseur}, matricule ${n.matricule}, ${action} ${n.station}, à ${heure}.`;
            } else {
                const action = n.type === 'start'
                    ? 'a démarré sa ronde'
                    : 'a terminé sa ronde';
                message = `L'agent ${n.nom_superviseur}, matricule ${n.matricule}, ${action} à la station ${n.station}, à ${heure}.`;
            }

            // Création de la voix
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = 'fr-FR';
            utterance.pitch = 1;
            utterance.rate = 0.95; // un peu plus lent, plus naturel
            utterance.volume = 1;

            const voices = window.speechSynthesis.getVoices();
            const frenchVoice = voices.find(v => v.lang.startsWith('fr'));
            if (frenchVoice) utterance.voice = frenchVoice;
            // 🔊 Lecture
            window.speechSynthesis.cancel(); // stoppe la lecture précédente
            window.speechSynthesis.speak(utterance);
        }
    } catch (e) {
        console.error("Erreur lors de la vérification de notification :", e);
    }
}
// 🔁 Vérification toutes les 3 secondes
setInterval(checkNotifications, 3000);
</script>

<script src="{{ asset("assets/js/libs/vue2.js") }}"></script>
{{-- For pusher notification  --}}
<!-- <script type="module" src="{{ asset("assets/js/scripts/talkiewalkie_controller.js") }}"></script> -->
@stack("scripts")
<!-- END: JS Assets-->
</body>

</html>
