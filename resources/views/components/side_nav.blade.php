 <!-- BEGIN: Side Menu -->
 <nav class="side-nav hidden w-[80px] overflow-x-hidden pb-16 pr-5 md:block xl:w-[230px]">
     <a class="flex items-center pt-4 pl-5 intro-x" href="#">
         <img class="w-6" src="dist/images/logo.svg" alt="logo">
         <span class="hidden ml-3 text-lg text-white xl:block"> Salama </span>
     </a>
     <div class="my-6 side-nav__divider"></div>
     <ul>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("dashboard") || Route::is("reports.patrols") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="monitor" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Tableau de bord
                     <div class="side-menu__sub-icon">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="/"
                         class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Monitoring
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url('/reports.patrols') }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des patrouilles
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("site.create.view") || Route::is("sites.list")  ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="home" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Sites
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="/site.create" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Création site
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/sites.list") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des sites
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("agent.create") || Route::is("agents.list") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="users" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Agents
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="/agent.create" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Création agent
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="/agents.list" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des agents
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("tasks") || Route::is("reports.tasks") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Tâches
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/tasks") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Gestion tâches
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/reports.tasks") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des tâches
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu {{  Route::is("visit.creating") ? 'side-menu--active' : ''  }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="badge-check" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Gestion visiteurs
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="ezuyezueze" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Nouvelle visite
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="euzeiuzie" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des visites
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("presence.horaires") || Route::is("reports.presences") || Route::is("agent.groupe") ? 'side-menu--active' : ''}} ">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Présences
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/presence.horaires") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Horaires
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("agent.groupe") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Groupes
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/reports.presences") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des présences
                         </div>
                     </a>
                 </li>
             </ul>
         </li>

         <li>
             <a href="{{ url("/requests") }}" class="side-menu {{ Route::is("requests") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="message-circle" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Requêtes
                 </div>
             </a>
         </li>
         <li>
             <a href="{{ url("/schedules") }}" class="side-menu {{ Route::is("schedules") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Planning
                 </div>
             </a>
         </li>
         <li>
             <a href="{{ url("/announces") }}" class="side-menu {{ Route::is("announces") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Communiqués
                 </div>
             </a>
         </li>
         <li>
             <a href="{{ url("/signalements") }}" class="side-menu {{ Route::is("signalements") ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="bell-ring" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Signalements
                 </div>
             </a>
         </li>
         <li>
             <a href="javascript:;" class="side-menu  {{ Route::is("log.phones") || Route::is("log.activities") || Route::is("log.panics") ? 'side-menu--active': '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="history" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Rapport de logs
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/log.phones") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Téléphone agent
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/log.activities") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Travailleur isolé
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/log.panics") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Alertes paniques
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="user" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Utilisateurs
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="zaaazazaz" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rôle & habilitation
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="azzezeezee" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Attribution accès
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des utilisateurs
                         </div>
                     </a>
                 </li>
             </ul>
         </li>

     </ul>
 </nav>
 <!-- END: Side Menu -->
