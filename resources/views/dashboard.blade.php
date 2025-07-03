@extends("layouts.app")

@section("content")
<!-- BEGIN: Content -->
<div
    class="md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
    <!-- BEGIN: Top Bar -->
    <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex">
            <ol class="flex items-center text-theme-1 dark:text-slate-300">
                <li class="">
                    <a href="#">Salama</a>
                </li>
                <li
                    class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 cursor-text dark:text-slate-400">
                    <a href="#">Tableau de bord</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6" id="App" v-cloak>
        <div class="col-span-12" :class="allPendingPatrols.length > 0 ? '2xl:col-span-8' : '2xl:col-span-12'">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Official Store -->
                <div class="col-span-12 mt-6 xl:col-span-12">
                    <div class="intro-y block h-10 items-center sm:flex">
                        <h2 class="mr-5 truncate text-lg font-medium">Tableau de bord</h2>
                        <!-- <div class="relative mt-3 text-slate-500 sm:ml-auto sm:mt-0">
                            <i data-tw-merge="" data-lucide="map-pin"
                                class="stroke-1.5 absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4"></i>
                            <input data-tw-merge="" type="text" placeholder="Filtrer par site..."
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box pl-10 sm:w-56">
                        </div> -->
                    </div>
                    <div class="intro-y box mt-12 p-5 sm:mt-5">
                        <div>
                            La cartographie de tous les sites qui utilisent la plateforme salama.
                        </div>
                        <div data-lat="-6.2425342" data-long="106.8626478" data-sources=""
                            class="main-leaflet leaflet z-0 [&_.leaflet-tile-pane]:contrast-105 [&_.leaflet-tile-pane]:grayscale [&_.leaflet-tile-pane]:dark:contrast-[.8] [&_.leaflet-tile-pane]:dark:invert mt-5 rounded-md bg-slate-200" style="height: 100vh;">
                        </div>
                    </div>
                </div>
                <!-- END: Official Store -->
            </div>
        </div>
        <div class="col-span-12 2xl:col-span-4" v-if="allPendingPatrols.length > 0">
            <div class="-mb-10 pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="intro-x flex h-10 items-center">
                            <h2 class="mr-5 truncate text-lg font-medium">Patrouilles en cours</h2>
                        </div>
                        <div class="mt-5">
                            <div class="intro-x" v-for="(data,i) in patrolPendings" :key="i" :data-id="data.id" @click.prevent="selectedPatrol = data; getPatrolDetailMap();" data-tw-toggle="modal" data-tw-target="#patrol-view-modal" >
                                <div class="box zoom-in mb-3 flex items-center px-3 py-3">
                                    <div class="image-fit h-10 w-10 flex-none overflow-hidden rounded-lg">
                                        <img src="{{ asset("assets/images/patrol.gif") }}"
                                            alt="illustration">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-extrabold">@{{data.site.name}}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">
                                            @{{data.agent.matricule }} | @{{data.agent.fullname}}
                                        </div>
                                    </div>
                                    <div v-if="data.scans">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white group-hover:bg-slate-100">
                                           +@{{ data.scans.length }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Transactions -->
                </div>
            </div>
        </div>
        <div
            data-tw-backdrop=""
            aria-hidden="true"
            tabindex="-1"
            id="patrol-view-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div
                data-tw-merge
                class="form-site w-[25%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px]">
                <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-extrabold uppercase font-extrabold text-blue-500" v-if="selectedPatrol">
                        Patrouille en cours | site @{{ selectedPatrol.site.name }}
                    </h2>
                    <button id="btn-reset"
                        data-tw-merge
                        data-tw-dismiss="modal"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                            data-tw-merge
                            data-lucide="x"
                            class="stroke-1.5 h-4 w-4"></i>
                    </button>
                </div>

                <div data-lat="-6.2425342" data-long="106.8626478" data-sources="" 
                    class="detail-leaflet leaflet z-0 [&_.leaflet-tile-pane]:contrast-105 [&_.leaflet-tile-pane]:grayscale [&_.leaflet-tile-pane]:dark:contrast-[.8] [&_.leaflet-tile-pane]:dark:invert mt-5 ml-5 mr-5 mb-5 rounded-md bg-slate-200" style="height: 320px;">
                </div>
               
                <div
                    class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400"><button
                        data-tw-merge
                        data-tw-dismiss="modal" type="button" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-1 w-20 mr-1 w-20">Fermer</button>
                </div>
            </d>
        </div>
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/monitoring.js") }}"></script>

@endpush

@push("styles")
<style>
    .tooltip-red.leaflet-tooltip-top::before {
        border-top-color: #dc2626 !important;
        z-index: 2000;
    }
</style>
@endpush
