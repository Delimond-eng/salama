<!-- BEGIN: Mobile Menu -->
<div
    class="mobile-menu group top-0 inset-x-0 fixed bg-theme-1/90 z-[60] border-b border-white/[0.08] dark:bg-darkmode-800/90 md:hidden before:content-[''] before:w-full before:h-screen before:z-10 before:fixed before:inset-x-0 before:bg-black/90 before:transition-opacity before:duration-200 before:ease-in-out before:invisible before:opacity-0 [&.mobile-menu--active]:before:visible [&.mobile-menu--active]:before:opacity-100">
    <div class="flex h-[70px] items-center px-3 sm:px-8">
        <a class="mr-auto flex" href="/">
            <img class="w-10" src="dist/images/security.svg" alt="logo">
        </a>
        </a>
        <a class="mobile-menu-toggler" href="#">
            <i data-tw-merge="" data-lucide="bar-chart2"
                class="stroke-1.5 h-8 w-8 -rotate-90 transform text-white"></i>
        </a>
    </div>
    <div
        class="scrollable h-screen z-20 top-0 left-0 w-[270px] -ml-[100%] bg-primary transition-all duration-300 ease-in-out dark:bg-darkmode-800 [&[data-simplebar]]:fixed [&_.simplebar-scrollbar]:before:bg-black/50 group-[.mobile-menu--active]:ml-0">
        <a href="#"
            class="fixed top-0 right-0 mt-4 mr-4 transition-opacity duration-200 ease-in-out invisible opacity-0 group-[.mobile-menu--active]:visible group-[.mobile-menu--active]:opacity-100">
            <i data-tw-merge="" data-lucide="x-circle"
                class="stroke-1.5 mobile-menu-toggler h-8 w-8 -rotate-90 transform text-white"></i>
        </a>
       <ul>
         @if (Auth::user()->hasMenu("patrouilles"))
         <li>
             <a href="javascript:;" class="menu @active(['dashboard','reports.patrols', 'global.view'])">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="monitor" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Tableau de bord
                     <div class="menu__sub-icon">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="py-2">
                 <li>
                     <a href="/"
                         class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Monitoring
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/global.view") }}"
                         class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                            Vue globale
                         </div>
                     </a>
                 </li>
                 @if (Auth::user()->hasPermission("patrouilles", "view"))
                 <li>
                     <a href="{{ url('/reports.patrols') }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport des patrouilles
                         </div>
                     </a>
                 </li>
                 @endif
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("sites"))
         <li>
             <a href="javascript:;" class="menu {{ Route::is("site.create.view") || Route::is("sites.list")  ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="home" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Sites
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 @if (Auth::user()->hasPermission("sites", "create"))
                 <li>
                     <a href="/site.create" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Création site
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("sites", "view"))
                 <li>
                     <a href="{{ url("/sites.list") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Liste des sites
                         </div>
                     </a>
                 </li>
                 @endif
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("agents"))
         <li>
             <a href="javascript:;" class="menu {{ Route::is("agent.create") || Route::is("agents.list") || Route::is("agents.history") ?  'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="users" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Agents
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 @if (Auth::user()->hasPermission("agents", "create"))
                 <li>
                     <a href="/agent.create" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Création agent
                         </div>
                     </a>
                 </li>
                 @endif

                 @if (Auth::user()->hasPermission("agents","view"))
                 <li>
                     <a href="/agents.list" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Liste des agents
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="/agents.history" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Historique des agents
                         </div>
                     </a>
                 </li>
                 @endif
             </ul>
         </li>
         @endif

         <!-- @if (Auth::user()->hasMenu("taches"))
        <li>
             <a href="javascript:;" class="menu {{ Route::is("tasks") || Route::is("reports.tasks") ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Tâches
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                
                 <li>
                     <a href="{{ url("/tasks") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Gestion tâches
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/reports.tasks") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport des tâches
                         </div>
                     </a>
                 </li>
             </ul>
        </li>
        @endif

        @if (Auth::user()->hasMenu("visites"))
        <li>
            <a href="javascript:;" class="menu {{  Route::is("visit.creating") ? 'menu--active' : ''  }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="badge-check" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Gestion visiteurs
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="ezuyezueze" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Nouvelle visite
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="euzeiuzie" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport des visites
                         </div>
                     </a>
                 </li>
             </ul>
        </li>
        @endif -->

         @if (Auth::user()->hasMenu("presences"))
         <li>
             <a href="javascript:;" class="menu {{ Route::is("presence.horaires") || Route::is("reports.presences") || Route::is("agent.groupe") || Route::is("reports.presences.filter") ? 'menu--active' : ''}} ">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Présences
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 @if (Auth::user()->hasPermission("presences", "create"))
                 <li>
                     <a href="{{ url("/presence.horaires") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Horaires
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("agent.groupe") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Groupes
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("presences", "view"))
                 <li>
                     <a href="{{ url("/reports.presences") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport des présences
                         </div>
                     </a>
                 </li>
                 @endif
                 <li>
                     <a href="{{ url("/reports.presences.filter") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport filtré
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("requetes"))
         <li>
             <a href="{{ url("/requests") }}" class="menu {{ Route::is("requests") ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="message-circle" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Requêtes
                 </div>
             </a>
         </li>
         @endif

         @if (Auth::user()->hasMenu("planning"))
         <li>
             <a href="javascript:;" class="menu {{ Route::is("schedules") || Route::is("schedules.supervisor") || Route::is("schedules.report")  ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Plannings
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/schedules") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Planning agents
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/schedules.supervisor") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Planning superviseurs
                         </div>
                     </a>
                 </li>
                  <li>
                     <a href="javascript:;" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapports
                             <div class="menu__sub-icon ">
                                 <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                             </div>
                         </div>
                     </a>
                     <ul class="">
                         <li>
                             <a href="#" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Rapport agents
                                 </div>
                             </a>
                         </li>
                         <li>
                             <a href="{{ url("/schedules.report") }}" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Rapport superviseurs
                                 </div>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <!-- <li>
                     <a href="{{ url("/schedules.report") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport de planning
                         </div>
                     </a>
                 </li> -->
             </ul>
         </li>
         @endif

         <li>
             <a href="javascript:;" class="menu  @active(['conges.management', 'ldd.management', 'pointages.agents'])">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="users" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     RH
                     <div class="menu__sub-icon">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                     </div>
                 </div>
             </a>
             <ul class="menu__sub">
                <li>
                     <a href="{{ url("/pointages.agents") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                            Pointages
                         </div>
                     </a>
                 </li>
                <li>
                     <a href="{{ url("/conges.management") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Gestion des congés
                         </div>
                     </a>
                 </li>
                <li>
                     <a href="/ldd.management" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                            LDD
                         </div>
                     </a>
                 </li>
                
                 <li>
                     <a href="javascript:;" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapports
                             <div class="menu__sub-icon ">
                                 <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                             </div>
                         </div>
                     </a>
                     <ul class="">
                         <li>
                             <a href="rubick-menu-invoice-layout-1-page.html" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Présences Globales
                                 </div>
                             </a>
                         </li>
                         <li>
                             <a href="rubick-menu-invoice-layout-1-page.html" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Absences
                                 </div>
                             </a>
                         </li>
                         <li>
                             <a href="rubick-menu-invoice-layout-1-page.html" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Retards
                                 </div>
                             </a>
                         </li>
                         <li>
                             <a href="rubick-menu-invoice-layout-1-page.html" class="menu">
                                 <div class="menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="menu__title">
                                    Congés
                                 </div>
                             </a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </li>

         @if (Auth::user()->hasMenu("communiques"))
         <li>
             <a href="{{ url("/announces") }}" class="menu {{ Route::is("announces") ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Communiqués
                 </div>
             </a>
         </li>
         @endif

         @if (Auth::user()->hasMenu("signalements"))
         <li>
             <a href="{{ url("/signalements") }}" class="menu {{ Route::is("signalements") ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="bell-ring" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Signalements
                 </div>
             </a>
         </li>
         @endif

         <div class="my-4 side-nav__divider"></div>
         @if (Auth::user()->hasMenu("configurations"))
         <li>
             <a href="javascript:;" class="menu @active(['secteurs', 'elements'])">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="settings" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Configurations
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/secteurs") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Secteurs
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/elements") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Inspection Eléments
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("utilisateurs"))
         <li>
             <a href="javascript:;" class="menu {{ Route::is("user.add") || Route::is("user.list") ? 'menu--active' : '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="user" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Utilisateurs
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 @if (Auth::user()->hasPermission("utilisateurs", "create"))
                 <li>
                     <a href="{{ url("/user.add") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Création utilisateur
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("utilisateurs", "view"))
                 <li>
                     <a href="{{ url("/user.list") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Liste des utilisateurs
                         </div>
                     </a>
                 </li>
                 @endif

                 <!-- <li>
                    <a href="azzezeezee" class="menu">
                        <div class="menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="menu__title">
                            Attribution accès
                        </div>
                    </a>
                </li> -->
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("logs"))
         <li>
             <a href="javascript:;" class="menu  {{ Route::is("log.phones") || Route::is("log.activities") || Route::is("log.panics") ? 'menu--active': '' }}">
                 <div class="menu__icon">
                     <i data-tw-merge="" data-lucide="history" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="menu__title">
                     Rapport de logs
                     <div class="menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/log.phones") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Rapport de téléphones
                         </div>
                     </a>
                 </li>
                 <!-- <li>
                     <a href="{{ url("/log.activities") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                             Travailleur isolé
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/log.panics") }}" class="menu">
                         <div class="menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="menu__title">
                            Alertes paniques
                         </div>
                     </a>
                 </li> -->
             </ul>
         </li>
         @endif

     </ul>

    </div>
</div>
<!-- END: Mobile Menu -->