<!-- BEGIN: Mobile Menu -->
<div
    class="mobile-menu group top-0 inset-x-0 fixed bg-theme-1/90 z-[60] border-b border-white/[0.08] dark:bg-darkmode-800/90 md:hidden before:content-[''] before:w-full before:h-screen before:z-10 before:fixed before:inset-x-0 before:bg-black/90 before:transition-opacity before:duration-200 before:ease-in-out before:invisible before:opacity-0 [&.mobile-menu--active]:before:visible [&.mobile-menu--active]:before:opacity-100">
    <div class="flex h-[70px] items-center px-3 sm:px-8">
        <a class="mr-auto flex" href="#">
            <img class="w-6" src="dist/images/logo.svg" alt="Salama">
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
        <ul class="py-2">
            <li>
                <a href="javascript:;" class="menu {{ Route::is("dashboard") || Route::is("reports.patrols") ? 'menu--active' : '' }}">
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
                <ul class="">
                    <li>
                        <a href="/"
                            class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Monitoring
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/reports.patrols') }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Rapport des patrouilles
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is("site.create") || Route::is("sites.list")  ? 'menu--active' : '' }}">
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
                    <li>
                        <a href="/site.create" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Création site
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/sites.list") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Liste des sites
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is("agent.create") || Route::is("agents.list") ? 'menu--active' : '' }}">
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
                    <li>
                        <a href="/agent.create" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Création agent
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/agents.list" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Liste des agents
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
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
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Gestion tâches
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/reports.tasks") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Rapport des tâches
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
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
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Nouvelle visite
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="euzeiuzie" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Rapport des visites
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is("presence.horaires") || Route::is("reports.presences") ? 'menu--active' : ''}} ">
                    <div class="menu__icon">
                        <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5"></i>
                    </div>
                    <div class="menu__title">
                        Présences
                        <div class="menu__sub-icon ">
                            <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                        </div>
                    </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ url("/presence.horaires") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Horaire
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Shift
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/reports.presences") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Rapport des présences
                            </div>
                        </a>
                    </li>
                </ul>
            </li>

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
            <li>
                <a href="{{ url("/schedules") }}" class="menu {{ Route::is("schedules") ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 w-5 h-5"></i>
                    </div>
                    <div class="menu__title">
                        Planning
                    </div>
                </a>
            </li>
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
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Téléphone agent
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/log.activities") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Travailleur isolé
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/log.panics") }}" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Alertes paniques
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu">
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
                    <li>
                        <a href="zaaazazaz" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Rôle & habilitation
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="azzezeezee" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Attribution accès
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="rubick-menu-add-product-page.html" class="menu">
                            <div class="menu__icon">
                                <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                            </div>
                            <div class="menu__title">
                                Liste des utilisateurs
                            </div>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>
</div>
<!-- END: Mobile Menu -->