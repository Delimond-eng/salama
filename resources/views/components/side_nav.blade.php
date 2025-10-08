 <!-- BEGIN: Side Menu -->
 <nav class="side-nav hidden w-[80px] overflow-x-hidden pb-16 pr-5 md:block xl:w-[230px]">
    <a class="flex items-center pt-4 pl-5 intro-x" href="/">
        <img class="w-10" src="dist/images/security.svg" alt="logo">
        <span class="hidden ml-2 text-lg text-white xl:block font-bold"> Salama </span>
    </a>
    <div class="my-6 side-nav__divider"></div>
    <ul>
        {{-- TABLEAU DE BORD / PATROUILLES --}}
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
                <!-- <li>
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
             -->
            </ul>
        </li>

        {{-- RAPPORTS --}}
        @can('rapports.view')
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
                        Rapports des supervisions
                        </div>
                    </a>
                </li>
                @can("patrouilles.view")
                <li>
                    <a href="{{ url('/reports.patrols') }}" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Rapport rondes agents
                        </div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- SITES --}}
        @can('sites.view')
        <li>
            <a href="javascript:;" class="side-menu @active(['site.create.view', 'sites.list'])">
                <div class="side-menu__icon">
                    <i data-tw-merge="" data-lucide="home" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">
                    Stations
                    <div class="side-menu__sub-icon ">
                        <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                @can("sites.create")
                <li>
                    <a href="/site.create" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Création station
                        </div>
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ url("/sites.list") }}" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Liste des stations
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        @endcan

        {{-- AGENTS --}}
        @can('agents.view')
        <li>
            <a href="javascript:;" class="side-menu @active(['agent.histories.single', 'agent.create', 'agents.list', 'agents.history'])">
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
                @can("agents.create")
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
                @endcan

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
            </ul>
        </li>
        @endcan

        {{-- PRESENCES --}}
        @can('presences.view')
        <li>
            <a href="javascript:;" class="side-menu @active(['presence.horaires','reports.presences', 'agent.groupe', 'presence.planning' ])">
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
                    <a href="{{ url("/reports.presences") }}" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Rapport des présences
                        </div>
                    </a>
                </li>
                @can("presences.create")
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                        </div>
                        <div class="side-menu__title">
                            Paramètres
                            <div class="side-menu__sub-icon ">
                                <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                            </div>
                        </div>
                    </a>
                    @can("presences.create")
                    <ul class="">
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
                        <li>
                            <a href="{{ url('/presence.plannings') }}" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-tw-merge="" data-lucide="arrow-right-circle" class="stroke-1.5 w-2 h-2"></i>
                                </div>
                                <div class="side-menu__title">
                                    Plannings rotatifs
                                </div>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- REQUÊTES --}}
        @can('requetes.view')
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
        @endcan

        {{-- PLANNING --}}
        @can('planning.view')
        <li>
            <a href="javascript:;" class="side-menu @active(['schedules', 'schedules.supervisor', 'schedules.report'])">
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
        @endcan

        {{-- RESSOURCES HUMAINES --}}
        @can('rh.view')
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
        @endcan

        {{-- COMMUNIQUÉS --}}
        @can('communiques.view')
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
        @endcan

        {{-- SIGNALEMENTS --}}
        @can('signalements.view')
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
        @endcan

        <div class="my-4 side-nav__divider"></div>

        {{-- CONFIGURATIONS --}}
        @can('configurations.view')
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
        @endcan

        {{-- UTILISATEURS --}}
        @can('utilisateurs.view')
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
                @can('utilisateurs.create')
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
                @endcan

                @can('utilisateurs.view')
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
                @endcan
            </ul>
        </li>
        @endcan

        {{-- LOGS --}}
        @can('logs.view')
        <li>
            <a href="javascript:;" class="side-menu @active(['log.phones','log.activities', 'log.panics'])">
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
        @endcan
    </ul>
 </nav>
 <!-- END: Side Menu -->