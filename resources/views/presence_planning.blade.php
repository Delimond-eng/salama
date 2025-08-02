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
                    <a href="#">Plannings rotatifs des agents</a>
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
        <!-- Permet de faire un trigger picker du bouton import excel -->
        <input type="file" ref="excelInput" accept=".xls,.xlsx" style="display: none" @change="handleExcelFile"/>

        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center xl:flex-nowrap">
            <button data-tw-merge="" @click.stop="pickExcelFile" :disabled="isLoading" class="transition duration-200 border inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success text-white dark:border-primary mr-2 shadow-md"> 
                <i class="w-3 h-3 mr-2 stroke-1.5" data-lucide="upload"></i> Importer Excel
                <span class="h-3 w-3 ml-2" v-if="isLoading">
                    <svg class="h-3 w-3" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="a" x1="8.042%" y1="0%" x2="65.682%" y2="23.865%">
                                <stop stop-color="#FFFFFF" stop-opacity="0" offset="0%" />
                                <stop stop-color="#FFFFFF" offset="100%" />
                                <stop stop-color="#FFFFFF" stop-opacity=".631" offset="63.146%" />
                            </linearGradient>
                        </defs>
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)">
                                <path id="Oval-2" d="M36 18c0-9.94-8.06-18-18-18" stroke="url(#a)" stroke-width="4">
                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                </path>
                                <circle fill="#FFFFFF" cx="36" cy="18" r="1">
                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                </circle>
                            </g>
                        </g>
                    </svg>
                </span>
            </button>
            <div class="mx-auto hidden text-slate-500 xl:block">

            </div>
            <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-48">
                    <option value="" selected hidden>Site</option>
                </select>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="col-span-12">
            <div class="box rounded-md p-5">
                <div class="mb-5 flex items-center justify-between border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                    <div class="truncate text-base font-medium">
                        Plannings rotatifs des agents par poste
                    </div>
                    <select @change="weeklyPlannings=[];viewWeeklyPlannings();" v-model="offset" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box mt-3 sm:ml-auto sm:mt-0 sm:w-auto">
                        <option value="0" selected>Semaine en cours</option>
                        <option value="1">Semaine prochaine</option>
                        <option value="-1">Semaine précédente</option>
                    </select>
                </div>
                <div class="overflow-x-auto overflow-y-auto" style="height:500px !important" v-if="plannings.length">
                    <table data-tw-merge class="w-full text-left">
                        <thead data-tw-merge class="">
                            <tr data-tw-merge class="">
                                <th data-tw-merge class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 border-l border-r border-t whitespace-nowrap">
                                    AGENT
                                </th>
                                <th v-for="jour in jours" :key="`jdjd${jour}`" class="font-extrabold uppercase px-5 py-3 border-b-2 dark:border-darkmode-300 border-l border-r border-t whitespace-nowrap">
                                    @{{ jour }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(site, index) in plannings">
                            <!-- Ligne du nom du site avec colspan -->
                            <tr class="bg-gray-100 font-bold" :key="`dhfjf${index}`">
                                <td colspan="8" class="border px-4 py-2 uppercase">
                                @{{ site.name }}
                                </td>
                            </tr>
                            <tr data-tw-merge v-for="(agent, aIndex) in site.agents" :key="agent.id">
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300 border-l border-r border-t">
                                    <span class="font-extrabold mr-2">@{{ agent.matricule }}</span>@{{ agent.fullname }}
                                </td>
                                <template v-for="jour in jours">
                                    <td :key="`DKLSLL${jour}`" class="px-5 py-3 border-b dark:border-darkmode-300 border-l border-r border-t">
                                        @{{ formatHoraire(mapPlanningsByDay(agent.plannings)[jour]) }}
                                    </td>
                                </template>
                            </tr>
                            </template>
                            </template>
                        </tbody>
                    </table>
                </div>
                <!-- END: Data List -->
                <div class="col-span-12" v-else>
                    <div v-if="isDataLoading">
                        <x-dom-loader></x-dom-loader>
                    </div>
                    <div v-else>
                        <x-empty-state message="Aucun planning hebdomadaire disponible !"></x-empty-state>
                    </div>
                </div>
            </div>
        </div>
        
       
        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">Importation du planning hebdomadaire des agent effectués! </div>
            </div>
        </div>

    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/presence.js") }}"></script>
@endpush
