 <!-- BEGIN: Side Menu -->
 <nav class="side-nav hidden w-[80px] overflow-x-hidden pb-16 pr-5 md:block xl:w-[230px]">
     <a class="flex items-center pt-4 pl-5 intro-x" href="/">
         <img class="w-10" src="dist/images/security.svg" alt="logo">
         <span class="hidden ml-2 text-lg text-white xl:block font-bold"> Salama </span>
     </a>
     <div class="my-6 side-nav__divider"></div>
     <ul>
         @if (Auth::user()->hasMenu("patrouilles"))
         <li>
             <a href="javascript:;" class="side-menu @active(['dashboard','global.view'])">
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
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Monitoring
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/global.view") }}"
                         class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Vue globale
                         </div>
                     </a>
                 </li>
                
             </ul>
         </li>
         @endif
         <li>
             <a href="javascript:;" class="side-menu @active(['round.reports','reports.patrols' ])">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="file" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                    Rapports
                     <div class="side-menu__sub-icon">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url('/round.reports') }}"
                         class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Rapports des rondes 011
                         </div>
                     </a>
                 </li>
                  @if (Auth::user()->hasPermission("patrouilles", "view"))
                 <li>
                     <a href="{{ url('/reports.patrols') }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des patrouilles
                         </div>
                     </a>
                 </li>
                 @endif
             </ul>
         </li>

         @if (Auth::user()->hasMenu("sites"))
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
                 @if (Auth::user()->hasPermission("sites", "create"))
                 <li>
                     <a href="/site.create" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Création site
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("sites", "view"))
                 <li>
                     <a href="{{ url("/sites.list") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
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
             <a href="javascript:;" class="side-menu {{ Route::is("agent.create") || Route::is("agents.list") || Route::is("agents.history") ?  'side-menu--active' : '' }}">
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
                 @if (Auth::user()->hasPermission("agents", "create"))
                 <li>
                     <a href="/agent.create" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Création agent
                         </div>
                     </a>
                 </li>
                 @endif

                 @if (Auth::user()->hasPermission("agents","view"))
                 <li>
                     <a href="/agents.list" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des agents
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="/agents.history" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
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
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Gestion tâches
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/reports.tasks") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des tâches
                         </div>
                     </a>
                 </li>
             </ul>
        </li>
        @endif

        @if (Auth::user()->hasMenu("visites"))
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
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Nouvelle visite
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="euzeiuzie" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des visites
                         </div>
                     </a>
                 </li>
             </ul>
        </li>
        @endif -->

         @if (Auth::user()->hasMenu("presences"))
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("presence.horaires") || Route::is("reports.presences") || Route::is("agent.groupe") || Route::is("reports.presences.filter") ? 'side-menu--active' : ''}} ">
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
                 @if (Auth::user()->hasPermission("presences", "create"))
                 <li>
                     <a href="{{ url("/presence.horaires") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Horaires
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("agent.groupe") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Groupes
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("presences", "view"))
                 <li>
                     <a href="{{ url("/reports.presences") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des présences
                         </div>
                     </a>
                 </li>
                 @endif
                 <!-- <li>
                     <a href="{{ url("/reports.presences.filter") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport filtré
                         </div>
                     </a>
                 </li> -->
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("requetes"))
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
         @endif

         @if (Auth::user()->hasMenu("planning"))
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("schedules") || Route::is("schedules.supervisor") || Route::is("schedules.report")  ? 'side-menu--active' : '' }}">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Plannings
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/schedules") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Planning agents
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/schedules.supervisor") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Planning superviseurs
                         </div>
                     </a>
                 </li>
                 <!--  <li>
                     <a href="javascript:;" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapports
                             <div class="side-menu__sub-icon ">
                                 <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                             </div>
                         </div>
                     </a>
                     <ul class="">
                         <li>
                             <a href="#" class="side-menu">
                                 <div class="side-menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="side-menu__title">
                                    Rapport agents
                                 </div>
                             </a>
                         </li>
                         <li>
                             <a href="{{ url("/schedules.report") }}" class="side-menu">
                                 <div class="side-menu__icon">
                                     <i data-tw-merge="" data-lucide="zap" class="stroke-1.5 w-2 h-2"></i>
                                 </div>
                                 <div class="side-menu__title">
                                    Rapport superviseurs
                                 </div>
                             </a>
                         </li>
                     </ul>
                 </li> -->
                 <!-- <li>
                     <a href="{{ url("/schedules.report") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport de planning
                         </div>
                     </a>
                 </li> -->
             </ul>
         </li>
         @endif

         <li>
             <a href="javascript:;" class="side-menu  @active(['conges.management', 'ldd.management', 'pointages.agents'])">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="users" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     RH
                     <div class="side-menu__sub-icon">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                     </div>
                 </div>
             </a>
             <ul class="side-menu__sub">
                <li>
                     <a href="{{ url("/pointages.agents") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Pointages
                         </div>
                     </a>
                 </li>
                <li>
                     <a href="{{ url("/conges.management") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Gestion des congés
                         </div>
                     </a>
                 </li>
                <li>
                     <a href="/ldd.management" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            LDD
                         </div>
                     </a>
                 </li>
             </ul>
         </li>

         @if (Auth::user()->hasMenu("communiques"))
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
         @endif

         @if (Auth::user()->hasMenu("signalements"))
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
         @endif

         <div class="my-4 side-nav__divider"></div>
         @if (Auth::user()->hasMenu("configurations"))
         <li>
             <a href="javascript:;" class="side-menu @active(['secteurs', 'elements', 'config.planning'])">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="settings" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Configurations
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="{{ url("/secteurs") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Secteurs
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/elements") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Inspection Eléments
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/config.planning") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Planning automatique
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("utilisateurs"))
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("user.add") || Route::is("user.list") ? 'side-menu--active' : '' }}">
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
                 @if (Auth::user()->hasPermission("utilisateurs", "create"))
                 <li>
                     <a href="{{ url("/user.add") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Création utilisateur
                         </div>
                     </a>
                 </li>
                 @endif
                 @if (Auth::user()->hasPermission("utilisateurs", "view"))
                 <li>
                     <a href="{{ url("/user.list") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des utilisateurs
                         </div>
                     </a>
                 </li>
                 @endif

                 <!-- <li>
                    <a href="azzezeezee" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Attribution accès
                        </div>
                    </a>
                </li> -->
             </ul>
         </li>
         @endif

         @if (Auth::user()->hasMenu("logs"))
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
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport de téléphones
                         </div>
                     </a>
                 </li>
                 <!-- <li>
                     <a href="{{ url("/log.activities") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Travailleur isolé
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="{{ url("/log.panics") }}" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Alertes paniques
                         </div>
                     </a>
                 </li> -->
             </ul>
         </li>
         @endif

     </ul>
 </nav>
 <!-- END: Side Menu -->