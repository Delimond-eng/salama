 <!-- BEGIN: Side Menu -->
 <nav class="side-nav hidden w-[80px] overflow-x-hidden pb-16 pr-5 md:block xl:w-[230px]">
     <a class="flex items-center pt-4 pl-5 intro-x" href="#">
         <img class="w-6" src="dist/images/logo.svg" alt="Midone - Tailwind Admin Dashboard Template">
         <span class="hidden ml-3 text-lg text-white xl:block"> Salama </span>
     </a>
     <div class="my-6 side-nav__divider"></div>
     <ul>
         <li>
             <a href="javascript:;" class="side-menu {{ Route::is("dashboard") ? 'side-menu--active' : '' }}">
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
                     <a href="rubick-side-menu-dashboard-overview-2-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Rapport des patrouilles
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-dashboard-overview-2-page.html" class="side-menu">
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
             <a href="javascript:;" class="side-menu {{ Route::is("site.create") || Route::is("sites.list")  ? 'side-menu--active' : '' }}">
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
             <a href="rubick-side-menu-file-manager-page.html" class="side-menu">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="message-circle" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                    Requêtes
                 </div>
             </a>
         </li>

         <li>
             <a href="javascript:;" class="side-menu">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Planning
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="rubick-side-menu-categories-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Planning ciblé
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Planning global
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Liste des planning
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" class="side-menu">
                 <div class="side-menu__icon">
                     <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 w-5 h-5"></i>
                 </div>
                 <div class="side-menu__title">
                     Communiqués
                     <div class="side-menu__sub-icon ">
                         <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-3 h-3"></i>
                     </div>
                 </div>
             </a>
             <ul class="">
                 <li>
                     <a href="rubick-side-menu-categories-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Communiqué ciblé
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Communiqué global
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                             Liste des Communiqués
                         </div>
                     </a>
                 </li>
             </ul>
         </li>
         <li class="my-6 side-nav__divider"></li>
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
                     <a href="rubick-side-menu-categories-page.html" class="side-menu">
                         <div class="side-menu__icon">
                             <i data-tw-merge="" data-lucide="navigation" class="stroke-1.5 w-2 h-2"></i>
                         </div>
                         <div class="side-menu__title">
                            Rôle & habilitation
                         </div>
                     </a>
                 </li>
                 <li>
                     <a href="rubick-side-menu-add-product-page.html" class="side-menu">
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