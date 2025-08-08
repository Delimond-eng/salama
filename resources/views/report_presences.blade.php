@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
    <!-- BEGIN: Top Bar -->
    <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex">
            <ol class="flex items-center text-theme-1 dark:text-slate-300">
                <li class="">
                    <a href="#">Salama</a>
                </li>
                <li class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 cursor-text dark:text-slate-400">
                    <a href="#">Rapport des présences</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="mt-5 grid grid-cols-16 gap-6" id="App" v-cloak>
        <div class="intro-y box col-span-12 lg:col-span-8">
            <div class="flex flex-wrap items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                <h2 class="mr-auto text-base font-extrabold uppercase">Rapport des présences par site</h2>
                <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                    <div class="relative w-56 text-slate-500">
                        <input data-tw-merge="" v-model="search" @input="pagination1.current_page=1;viewAllSites();" type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                        <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto h-4 w-4 mr-2"></i>
                    </div>
                    <!-- <button onclick="location.href='/site.create'" class="bg-primary text-white transition duration-200 border border-primary shadow-sm inline-flex items-center justify-center py-2 px-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 disabled:opacity-70 disabled:cursor-not-allowed hover:bg-opacity-90 hover:border-opacity-90">
                        <span class="flex h-5 w-5 items-center justify-center">
                            <i class="w-4 h-4" data-lucide="plus"></i>
                        </span>
                    </button> -->
                </div>
            </div>
            <div class="space-y-4" v-if="allSites.length > 0">
                <div v-for="(data, i) in allSites" :key="i" class="border border-slate-200 rounded-bottom overflow-hidden">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-6 py-5 hover:bg-slate-50 transition-colors duration-200 cursor-pointer">
                        <!-- Site Infos -->
                        <div class="flex items-center gap-4">
                            <i data-lucide="home" class="h-6 w-6 text-primary"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">@{{ data.name }}</h3>
                                <p class="text-sm text-slate-500">@{{ data.code }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <!-- <button @click.stop="selectedSiteId = data.id; openAccordion = i; viewPresenceBySite();" class="bg-red-100 text-danger border border-red-300 rounded-lg px-2 py-1.5 text-sm hover:bg-red-200 hover:border-red-400">
                                <span v-if="delete_id === data.id" class="h-4 w-4">
                                    <svg class="h-full w-full" width="15" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="a" x1="8.042%" y1="0%" x2="65.682%" y2="23.865%">
                                                <stop stop-color="#2d3748" stop-opacity="0" offset="0%" />
                                                <stop stop-color="#2d3748" offset="100%" />
                                                <stop stop-color="#2d3748" stop-opacity=".631" offset="63.146%" />
                                            </linearGradient>
                                        </defs>
                                        <g fill="none" fill-rule="evenodd">
                                            <g transform="translate(1 1)">
                                                <path id="Oval-2" d="M36 18c0-9.94-8.06-18-18-18" stroke="url(#a)" stroke-width="3">
                                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                                </path>
                                                <circle fill="#2d3748" cx="36" cy="18" r="1">
                                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                                </circle>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <i v-if="openAccordion === i" data-lucide="eye-off" class="w-4 h-4"> </i>
                                <i v-else data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button data-tw-toggle="modal" data-tw-target="#presence-view-modal" @click="selectedSiteId = data.id; viewPresenceBySite();" class="ml-2 bg-red-100 text-text-blue-500 rounded-lg px-2 py-1.5 text-sm hover:bg-red-200 hover:border-red-400">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button> -->
                            <button data-tw-merge data-tw-toggle="modal" data-tw-target="#presence-view-modal" @click="selectedSiteId = data.id; viewPresenceBySite();" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-primary text-primary dark:border-primary [&:hover:not(:disabled)]:bg-primary/10 mb-2 mr-1 inline-block mb-2 mr-1 inline-block">Voir présences</button>
                        </div>
                    </div>

                    <!-- Content -->
                    <!-- <transition name="fade">
                        <div v-if="openAccordion === i" class="grid grid-cols-12 gap-6 bg-slate-50 border-t border-slate-200 px-6 py-5 overflow-auto 2xl:overflow-visible lg:overflow-visible">
                            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center xl:flex-nowrap overflow-auto 2xl:overflow-visible">
                                <button v-show="presences.length !== 0"  @click.stop="exportToExcel" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary text-white dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                    Exporter en Excel</button>
                                <div class="mx-auto hidden text-slate-500 xl:block">

                                </div>
                                <div class="relative w-56 text-slate-500 mr-1">
                                    <input data-tw-merge="" type="date" v-model="filter_datep"
                                        @change="viewPresenceBySite();" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                                    <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                                </div>
                                <div v-show="presences.length !== 0" class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto gap-2">
                                    <div class="relative w-56 text-slate-500">
                                        <input data-tw-merge="" @input="pagination.current_page=1;viewPresenceBySite();" v-model="search2" type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                                    </div>
                                </div>
                            </div>

                            <div v-if="isPresenceLoading" class="col-span-12 flex justify-center align-items-center">
                                <span class="h-16 w-16">
                                    <svg class="h-full w-full" width="50" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748">
                                        <g fill="none" fill-rule="evenodd">
                                            <g transform="translate(1 1)" stroke-width="4">
                                                <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>

                            <div class="col-span-12 grid grid-cols-12 overflow-auto 2xl:overflow-visible lg:overflow-visible" v-else>
                                <div class="col-span-12" v-if="presences.length === 0">
                                    <x-empty-state message="Aucun rapport de présence disponible"></x-empty-state>
                                </div>
                                <div v-else class="intro-y col-span-12 overflow-auto 2xl:overflow-visible lg:overflow-visible">
                                    <table data-tw-merge="" class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                                        <thead ata-tw-merge="" class="text-blue-500 font-extrabold">
                                            <tr data-tw-merge="" class="">
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">AGENT</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">HORAIRE</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">ARRIVée</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">DEPART</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">DURée</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">IMG.ARV</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">IMG.DPT</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">DATE</th>
                                                <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-tw-merge="" class="intro-y" v-for="presence in filteredPresences" :key="presence.id">
                                                <td data-tw-merge="" :class="gps_site_id !== null && presence.site_id !== presence.gps_site_id ? 'warning-border' : ''" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                                    <div class="flex">
                                                        <div class="image-fit zoom-in h-9 w-9">
                                                            <img v-if="presence.agent.photo" data-action="zoom" data-placement="top" :src="presence.agent.photo" alt="photo" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                            <img v-else data-placement="top" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                        </div>
                                                        <div class="ml-4">
                                                            <a class="whitespace-nowrap font-medium" href="#">
                                                                @{{ presence.agent.fullname || 'N/A' }}
                                                            </a>
                                                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                                                @{{ presence.agent.matricule }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.agent.groupe.libelle }}</td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.started_at }}</td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.ended_at || '-' }}</td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.duree || '-' }}</td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                                    <div class="image-fit zoom-in h-9 w-9">
                                                        <img data-action="zoom" data-placement="top" :src="presence.photos_debut" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                    </div>
                                                </td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                                    <div class="image-fit zoom-in h-9 w-9">
                                                        <img data-action="zoom" data-placement="top" :src="presence.photos_fin ?? 'assets/images/loading.gif'" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                    </div>
                                                </td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.created_at }}</td>
                                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                                    <button @click="selectedPresence = presence" data-tw-toggle="modal" data-tw-target="#presence-details-modal" class="text-blue-500 underline hover:text-blue-800 tooltip" :title="presence.commentaires">
                                                        Lire details
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                          
                             <Pagination
                                :current-page="pagination.current_page"
                                :last-page="pagination.last_page"
                                :total-items="pagination.total"
                                :per-page="pagination.per_page"
                                @page-changed="changePage"
                                @per-page-changed="onPerPageChange"
                            ></Pagination>
                        </div>
                    </transition> -->
                </div>
            </div>
        </div>
        <Pagination
            :current-page="pagination1.current_page"
            :last-page="pagination1.last_page"
            :total-items="pagination1.total"
            :per-page="pagination1.per_page"
            @page-changed="changePage1"
            @per-page-changed="onPerPageChange1">
        </Pagination>
        <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="presence-details-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div data-tw-merge="" class="w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 dark:bg-darkmode-600 sm:w-[460px]"><a class="absolute inset-y-0 left-0 right-auto my-auto -ml-[60px] flex h-8 w-8 items-center justify-center rounded-full border border-white/90 bg-white/5 text-white/90 transition-all hover:rotate-180 hover:scale-105 hover:bg-white/10 focus:outline-none sm:-ml-[105px] sm:h-14 sm:w-14" data-tw-dismiss="modal" href="javascript:;">
                    <i data-tw-merge="" data-lucide="x" class="h-3 w-3 stroke-[1] sm:h-8 sm:w-8"></i>
                </a>
                <div data-tw-merge="" class="overflow-y-auto flex-1 p-0" v-if="selectedPresence">
                    <div class="flex flex-col border-b">
                        <div class="px-8 pt-6 pb-8">
                            <div class="text-base font-extrabold uppercase">Détails de la présence de l'agent</div>
                            <!-- <div class="mt-0.5 text-slate-500 flex items-center border-b border-slate-200/60 pb-4" v-if="selectedPatrol">
                                <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-primary"></i>
                                <span v-if="selectedPatrol.site">@{{ selectedPatrol.site.name }}</span>
                            </div> -->
                        </div>

                    </div>
                    <div class="flex items-start px-5 pt-5">
                        <div class="flex w-full flex-col items-center lg:flex-row">
                            <div class="relative h-16 w-16 mr-4">
                                <!-- Avatar -->
                                <div class="image-fit h-16 w-16">
                                    <a @click="openPhoto(selectedPresence.photos_debut)" href="#"><img class="rounded" :src="selectedPresence.photos_debut" alt="avatar"></a>
                                </div>

                                <!-- Badge -->
                                <div style="background-color: #059669; top:0; left: 0;" class="absolute text-white text-[8px] px-1.5 py-0.5 rounded shadow-lg">
                                    in
                                </div>
                            </div>
                            <div class="relative h-16 w-16 mr-4" v-if="selectedPresence.photos_fin">
                                <!-- Avatar -->
                                <div class="image-fit h-16 w-16">
                                    <a @click="openPhoto(selectedPresence.photos_fin)" href="#"><img class="rounded" :src="selectedPresence.photos_fin" alt="avatar"></a>
                                </div>

                                <!-- Badge -->
                                <div style="background-color:rgb(195, 75, 6);  top:0; left: 0;" class="absolute text-white text-[8px] px-1.5 py-0.5 rounded shadow-lg">
                                    out
                                </div>
                            </div>

                            <div class="mt-3 text-center lg:ml-4 lg:mt-0 lg:text-left">
                                <a v-if="selectedPresence.agent" class="font-extrabold text-[25px]" href="#">
                                    @{{ selectedPresence.agent.fullname }}
                                </a>
                                <div v-if="selectedPresence.agent" class="mt-0.5 text-xs text-slate-500">
                                    @{{ selectedPresence.agent.matricule }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 p-5">
                        <div class="flex py-2 border-b border-slate-200">
                            <div class="mr-auto">Date</div>
                            <div class="font-medium">@{{ selectedPresence.created_at }}</div>
                        </div>
                        <div class="flex py-2 border-b border-slate-200">
                            <div class="mr-auto uppercase">SITE AFFECTé</div>
                            <div class="font-extrabold" v-if="selectedPresence.agent">@{{ selectedPresence.agent.site.name}}</div>
                        </div>
                        <div class="flex py-2 border-b border-slate-200">
                            <div class="mr-auto uppercase">SITE DE PRésence détecté</div>
                            <div class="font-extrabold" :class="selectedPresence.site_id !== selectedPresence.gps_site_id ? 'text-danger' : 'text-success'" v-if="selectedPresence.site">@{{ selectedPresence.site.name}}</div>
                            <div class="font-medium text-pending" v-else>INCONNU</div>
                        </div>
                        <div class="mt-4 flex">
                            <div class="mr-auto">Horaire</div>
                            <div class="font-medium flex flex-col" v-if="selectedPresence.agent">
                                <span class="text-blue-500 fw-bold">@{{ selectedPresence.agent.groupe.libelle }}</span>
                               <!--  <small>@{{ selectedPresence.agent.groupe.horaire.started_at }} -- @{{ selectedPresence.agent.groupe.horaire.ended_at }}</small> -->
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <div class="mr-auto">Heure d'arrivée</div>
                            <div class="font-medium">@{{ selectedPresence.started_at || '-' }}</div>
                        </div>
                        <div class="mt-4 flex">
                            <div class="mr-auto">Heure de départ</div>
                            <div class="font-medium">@{{ selectedPresence.ended_at || '-' }}</div>
                        </div>
                        <div class="mt-4 flex">
                            <div class="mr-auto">Temps de travail</div>
                            <div class="font-medium">@{{ selectedPresence.duree || '-' }}</div>
                        </div>
                        <div class="mt-4 flex">
                            <div class="mr-auto">Statut de rétard</div>
                            <div class="font-medium" :class="selectedPresence.retard =='non' ? 'text-blue-500' : 'text-danger'">@{{ selectedPresence.retard }}</div>
                        </div>
                        <div class="mt-4 text-left border-t border-slate-200/60 pt-4 dark:border-darkmode-400">
                            <div class="mr-auto text-base font-medium">
                                Commentaire
                            </div>
                            <div class="text-blue-500 text-xs">@{{ selectedPresence.commentaires || '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div data-tw-backdrop=""
            aria-hidden="true"
            tabindex="-1"
            id="presence-view-modal"
            class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 
            [&:not(.show)]:duration-[0s,0.2s] 
            [&:not(.show)]:delay-[0.2s,0s] 
            [&:not(.show)]:invisible 
            [&:not(.show)]:opacity-0 
            [&.show]:visible [&.show]:opacity-100 
            [&.show]:duration-[0s,0.4s] flex items-end">
            
            <!-- Contenu du bottom sheet -->
            <div style="border-top-left-radius: 30px; border-top-right-radius: 30px;" class="w-full bg-slate-50 p-4 shadow-lg 
                translate-y-full group-[.show]:translate-y-0 
                transition-transform duration-300 ease-out">
                 <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium uppercase">
                        Liste des présences journalières
                    </h2>
                    <button
                        data-tw-merge
                        type="button"
                        data-tw-dismiss="modal"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                            data-tw-merge
                            data-lucide="x"
                            class="stroke-1.5 h-4 w-4"></i>
                    </button>
                </div>
                <div class="grid grid-cols-12 gap-6 bg-slate-50 border-t border-slate-200 px-6 py-5 overflow-auto 2xl:overflow-visible lg:overflow-visible">
                    <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center xl:flex-nowrap overflow-auto 2xl:overflow-visible">
                        <button v-show="presences.length !== 0"  @click.stop="exportToExcel" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary text-white dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            Exporter en Excel</button>
                        <div class="mx-auto hidden text-slate-500 xl:block">

                        </div>
                        <div class="relative w-56 text-slate-500 mr-1">
                            <input data-tw-merge="" type="date" v-model="filter_datep"
                                @change="viewPresenceBySite();" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                            <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                        </div>
                        <div v-show="presences.length !== 0" class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto gap-2">
                            <div class="relative w-56 text-slate-500">
                                <input data-tw-merge="" @input="pagination.current_page=1;viewPresenceBySite();" v-model="search2" type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                            </div>
                        </div>
                    </div>

                    <div v-if="isPresenceLoading" class="col-span-12 flex justify-center align-items-center">
                        <span class="h-16 w-16">
                            <svg class="h-full w-full" width="50" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748">
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(1 1)" stroke-width="4">
                                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                        <path d="M36 18c0-9.94-8.06-18-18-18">
                                            <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </div>

                    <div class="col-span-12 grid grid-cols-12 overflow-auto 2xl:overflow-visible lg:overflow-visible" v-else>
                        <div class="col-span-12" v-if="filteredPresences.length === 0">
                            <x-empty-state message="Aucun rapport de présence disponible"></x-empty-state>
                        </div>
                        <div v-else class="intro-y col-span-12 overflow-auto 2xl:overflow-visible lg:overflow-visible">
                            <table data-tw-merge="" class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                                <thead ata-tw-merge="" class="text-blue-500 font-extrabold">
                                    <tr data-tw-merge="" class="">
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">AGENT</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">HORAIRE</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">ARRIVée</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">DEPART</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">DURée</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 uppercase">IMG.ARV</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">IMG.DPT</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">DATE</th>
                                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-tw-merge="" class="intro-y" v-for="presence in filteredPresences" :key="presence.id">
                                        <td data-tw-merge="" :class="gps_site_id !== null && presence.site_id !== presence.gps_site_id ? 'warning-border' : ''" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                            <div class="flex">
                                                <div class="image-fit zoom-in h-9 w-9">
                                                    <img v-if="presence.agent.photo" data-action="zoom" data-placement="top" :src="presence.agent.photo" alt="photo" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                    <img v-else data-placement="top" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                                </div>
                                                <div class="ml-4">
                                                    <a class="whitespace-nowrap font-medium" href="#">
                                                        @{{ presence.agent.fullname || 'N/A' }}
                                                    </a>
                                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                                        @{{ presence.agent.matricule }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.agent.groupe.libelle }}</td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.started_at }}</td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.ended_at || '-' }}</td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.duree || '-' }}</td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                            <div class="image-fit zoom-in h-9 w-9">
                                                <img data-action="zoom" data-placement="top" :src="presence.photos_debut" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                            <div class="image-fit zoom-in h-9 w-9">
                                                <img data-action="zoom" data-placement="top" :src="presence.photos_fin ?? 'assets/images/loading.gif'" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">@{{ presence.created_at }}</td>
                                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                            <button @click="selectedPresence = presence" data-tw-toggle="modal" data-tw-target="#presence-details-modal" class="text-blue-500 underline hover:text-blue-800 tooltip" :title="presence.commentaires">
                                                Lire details
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- BEGIN: Pagination -->
                    <Pagination
                        :current-page="pagination.current_page"
                        :last-page="pagination.last_page"
                        :total-items="pagination.total"
                        :per-page="pagination.per_page"
                        @page-changed="changePage"
                        @per-page-changed="onPerPageChange"
                    ></Pagination>
                    <!-- END: Pagination -->
                </div>
            </div>
        </div>
        <!-- END: Pagination -->
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")

<script type="module" src="{{ asset("assets/js/scripts/areas_manager.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


@endpush

@push("styles")
<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.2s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(-5px);
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.3s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(-5px);
    }

    .warning-border {
        border: 1px solid red;
        animation: clignoter 1s infinite;
    }

    @keyframes clignoter {
        0%, 100% { border-color: red; }
        50% { border-color: transparent; }
    }
</style>
@endpush