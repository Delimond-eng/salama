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
                    <a href="#">Rapport des patrouilles</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="mt-5 grid grid-cols-12 gap-6" id="App" v-cloak>
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center xl:flex-nowrap flex-wrap">
            <div class="flex gap-2">
                <button @click="downloadPatrolPDF" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-lg font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary text-white dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                    Exporer en PDF</button>
                <button data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-lg font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary text-white dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                    Exporer en Excel</button>
            </div>
            <div class="mx-auto hidden text-slate-500 xl:block">

            </div>
            <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto flex-wrap">
                <div class="relative w-56 text-slate-500">
                    <input data-tw-merge="" type="date" v-model="filter_date" @input="pagination.current_page= 1;viewAllReports()" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                    <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
                <!-- <select data-tw-merge="" @change="filter_date=''" v-model="filter_site" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box ml-2 w-56 xl:w-auto">
                    <option selected hidden value="">Par site</option>
                    <option value="">Tous les rapports</option>
                    <option v-for="item in allSites" :value="item.id">@{{ item.name }}</option>
                </select> -->
                <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-48 ml-2">
                    <option value="" selected hidden>Filtrez par site</option>
                </select>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
            <table data-tw-merge="" class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]" v-if="allPatrolReports.length">
                <thead data-tw-merge="" class="">
                    <tr data-tw-merge="" class="">
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            AGENT & PHOTO
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            NOM & CODE DU SITE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            DATE & HEURE DEBUT
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            DATE & HEURE FIN
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            STATUS
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-tw-merge="" class="intro-y" v-for="(data, index) in allPatrolReports" :key="index" >
                        <td  class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="image-fit zoom-in h-9 w-9">
                                    <img data-placement="top" data-action="zoom" :src="data.photo ?? 'assets/images/loading.gif'" alt="photo" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                </div>
                                <div class="ml-4" v-if="data.agent">
                                    <a class="whitespace-nowrap font-medium" href="#">
                                        @{{ data.agent.fullname }}
                                    </a>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        @{{ data.agent.fullname }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex" v-if="data.site">
                                <div class="ml-4">
                                    <a class="whitespace-nowrap font-medium" href="#">
                                        @{{ data.site.name }}
                                    </a>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        @{{ data.site.code }}
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            @{{data.started_at}}
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            @{{data.ended_at ?? '------'}}
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div v-if="data.ended_at !== null" class="flex items-center justify-center text-success">
                                <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Completed
                            </div>

                            <div v-else class="flex items-center justify-center text-pending">
                                <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Pending
                            </div>

                            <div class="mt-0.5 whitespace-nowrap text-xs text-red-600 text-slate-500">
                            <!-- <template v-if="data.zones_scanned < data.zones_expected">
                               ⚠️ Zone non scannée
                            </template>
                            <template  v-if="data.scans_stats.some(z => z.distance_meters > 150)">
                                <span  v-if="data.zones_scanned < data.zones_expected"> + </span>
                               ⚠️ QRCode déplacé
                            </template> -->
                            </div>


                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                            <div class="flex items-center">
                                <button @click="loadChart(data)"  data-tw-toggle="modal" data-tw-target="#patrol-info-details"
                                    class=" flex border-0 bg-[#4ab3f4] rounded-md px-2 py-1.5 text-xs hover:bg-[#4ab3f4]/20 text-white shadow-lg mr-2">
                                    Voir détails
                                </button>
                                <button :disabled="closed_id === data.id" v-if="data.ended_at === null"  @click="closePatrol(data)"
                                    class="bg-pending flex border-0 rounded-md px-2 py-1.5 text-xs hover:bg-pending/20 text-white shadow-lg">
                                    Clotûrer
                                    <span v-if="closed_id === data.id" class="ml-2 h-4 w-4">
                                        <svg class="h-full w-full" width="25" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF">
                                            <circle cx="15" cy="15" r="15">
                                                <animate values="15;9;15" attributeName="r" from="15" to="15" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                                <animate values="1;.5;1" attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                            </circle>
                                            <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                                <animate values="9;15;9" attributeName="r" from="9" to="9" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                                <animate values=".5;1;.5" attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                            </circle>
                                            <circle cx="105" cy="15" r="15">
                                                <animate values="15;9;15" attributeName="r" from="15" to="15" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                                <animate values="1;.5;1" attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" calcMode="linear" repeatCount="indefinite"></animate>
                                            </circle>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total-items="pagination.total"
                :per-page="pagination.per_page"
                @page-changed="changePage"
                @per-page-changed="onPerPageChange"
            ></Pagination>

            <div v-if="allPatrolReports.length === 0">
                <div v-if="isDataLoading">
                    <x-dom-loader></x-dom-loader>
                </div>
                <div v-else>
                    <x-empty-state message="Aucun rapport de patrouille disponible."></x-empty-state>
                </div>
            </div>
        </div>
        <!-- END: Data List -->



        <!-- END: Pagination -->

        <!-- BEGIN: Pagination -->

        <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="patrol-info-details" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div data-tw-merge="" class="w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 dark:bg-darkmode-600 sm:w-[460px]"><a class="absolute inset-y-0 left-0 right-auto my-auto -ml-[60px] flex h-8 w-8 items-center justify-center rounded-full border border-white/90 bg-white/5 text-white/90 transition-all hover:rotate-180 hover:scale-105 hover:bg-white/10 focus:outline-none sm:-ml-[105px] sm:h-14 sm:w-14" data-tw-dismiss="modal" href="javascript:;">
                    <i data-tw-merge="" data-lucide="x" class="h-3 w-3 stroke-[1] sm:h-8 sm:w-8"></i>
                </a>
                <div data-tw-merge="" class="overflow-y-auto flex-1 p-0">
                    <div class="flex flex-col">
                        <div class="px-8 pt-6 pb-8">
                            <div class="text-base font-bold">Patrouille Détails</div>
                            <div class="mt-0.5 text-slate-500 flex items-center border-b border-slate-200/60 pb-4" v-if="selectedPatrol">
                                <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-primary"></i>
                                <span v-if="selectedPatrol.site">@{{ selectedPatrol.site.name }}</span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-4">
                                <div class="col-span-12">
                                    <div class="grid grid-cols-12">
                                        <div class="relative col-span-6">
                                            <div class="w-auto h-[208px]">
                                                <canvas id="report-donut-chart" class="chart mt-3"></canvas>
                                            </div>
                                            <div class="absolute left-0 top-0 flex h-full w-full flex-col items-center justify-center">
                                                <div class="text-2xl font-medium donut-score">
                                                    <!-- Valeur dynamique injectée ici -->
                                                    0%
                                                </div>
                                                <div class="mt-0.5 text-slate-500">Efficacité</div>
                                            </div>
                                        </div>
                                        <div v-if="selectedPatrol" class="mx-auto mt-14 w-52 sm:w-auto col-span-6">
                                            <div class="mt-4 flex items-center">
                                                <div class="mr-3 h-2 w-2 rounded-full bg-primary"></div>
                                                <span class="truncate mr-2">Zones scannées : </span>
                                                <span class="ml-auto font-medium">@{{ selectedPatrol.zones_scanned }}</span>
                                            </div>
                                            <div class="mt-4 flex items-center">
                                                <div class="mr-3 h-2 w-2 rounded-full bg-warning"></div>
                                                <span class="truncate mr-2">Non scannées : </span>
                                                <span class="ml-auto font-medium">@{{ selectedPatrol.zones_expected - selectedPatrol.zones_scanned }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12" v-if="selectedPatrol">
                                    <div class="flex">
                                        <div class="mr-auto">Taux de couverture</div>
                                        <div>@{{selectedPatrol.coverage_rate }}%</div>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded dark:bg-black/20 mt-2 h-1">
                                        <div
                                        class="bg-primary h-full rounded w-full"
                                        :style="{ width:selectedPatrol.coverage_rate > 100 ? 100 : selectedPatrol.coverage_rate + '%' }"
                                        role="progressbar"
                                        :aria-valuenow="selectedPatrol.coverage_rate"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>

                                <div class="col-span-12"  v-if="selectedPatrol">
                                    <div class="flex">
                                        <div class="mr-auto flex align-items-center">Efficacité
                                            <div class="rounded-lg ml-2 bg-dark py-[3px] px-2 text-xs font-normal text-white">
                                                @{{ selectedPatrol.efficiency_label }}
                                            </div>
                                        </div>
                                        <div>@{{ selectedPatrol.efficiency_score.toFixed(1) }}%</div>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded dark:bg-black/20 mt-2 h-1">
                                        <div
                                        class="bg-warning h-full rounded"
                                        :style="{ width: selectedPatrol.efficiency_score > 100 ? 100 : selectedPatrol.efficiency_score + '%' }"
                                        role="progressbar"
                                        :aria-valuenow="selectedPatrol.efficiency_score"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>

                                <div class="col-span-12"  v-if="selectedPatrol">
                                    <div class="flex">
                                        <div class="mr-auto">Temps réel parcouru vs temps estimé</div>
                                        <div>@{{selectedPatrol.duration_minutes }} min / @{{ selectedPatrol.estimated_duration_minutes }} min</div>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded dark:bg-black/20 mt-2 h-1">
                                        <div
                                        class="bg-success h-full rounded"
                                        :style="{
                                            width: Math.min((selectedPatrol.estimated_duration_minutes / selectedPatrol.duration_minutes) * 100, 100) + '%'
                                        }"
                                        role="progressbar"
                                        ></div>
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 2xl:col-span-12" v-if="selectedPatrol">
                                    <div class="relative mt-5 before:absolute before:ml-5 before:mt-5 before:block before:h-[85%] before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="intro-x relative mb-2 flex items-center" v-for="(data, i) in selectedPatrol.scans_stats">
                                            <div class="before:absolute before:ml-5 before:mt-5 before:block before:h-px before:w-20 before:bg-slate-200 before:dark:bg-darkmode-400">
                                                <div class="image-fit h-10 w-15">
                                                    <span class="text-white box border-0 flex items-center bg-[#4ab3f4] py-1 px-2"><i class="h-3 w-3 mr-1" data-lucide="clock"></i>@{{ data.time }}</span>
                                                </div>
                                            </div>
                                            <div class="box ml-4 flex-1 px-3 py-2">
                                                <div class="flex justify-between">
                                                    <div class="font-bold uppercase">
                                                        @{{ data.area }}
                                                    </div>
                                                    <div>
                                                        <div class="ml-auto mt-1">
                                                            <div :class="data.distance_meters <= 150 ? 'bg-success' : 'bg-pending'" class="rounded py-[2px] px-2 text-xs font-medium text-white">
                                                                @{{ data.distance_meters <= 150 ? 'succès' : 'éloigné' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between mt-1">
                                                    <div class="text-blue-500 text-xs">Temps depuis le précédent @{{ data.duration_since_previous_seconds }} s</div>
                                                    <div class="ml-auto text-xs text-slate-500">Distance <span :class="data.distance_meters <=1 ? 'text-success' : ''">@{{ data.distance_meters <= 150 ? 0 : data.distance_meters }}m</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/reports.js") }}"></script>
@endpush

