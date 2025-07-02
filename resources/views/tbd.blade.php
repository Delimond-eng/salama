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
                    <a href="#">Vue globale des opérations</a>
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
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">

                <!-- BEGIN: General Report -->
                <div v-if="!isDataLoading" class="col-span-12">
                    <div class="intro-y flex h-10 items-center">
                        <h2 class="mr-5 truncate text-lg font-medium">Rapport général</h2>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="refresh-ccw"
                                class="stroke-1.5 mr-3 h-4 w-4"></i>
                            Actualiser les données
                        </a>
                    </div>
                    <div class="mt-5 grid grid-cols-12 gap-6">
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Nombre des sites"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="home"
                                            class="stroke-1.5 h-[28px] w-[28px] text-primary"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">@{{ count.sites }}</div>
                                    <div class="mt-1 text-base text-slate-500">Nombre des sites</div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents présents dans les sites"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="users"
                                            class="stroke-1.5 h-[28px] w-[28px] text-success"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">@{{ count.presences }} / @{{ count.agents }}</div>
                                    <div class="mt-1 text-base text-slate-500">Agents présents/agents totaux</div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents absents"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="calendar"
                                            class="stroke-1.5 h-[28px] w-[28px] text-blue-500"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">@{{ count.holidays }}</div>
                                    <div class="mt-1 text-base text-slate-500">
                                        Congés & absences autorisées
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents absents"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="group"
                                            class="stroke-1.5 h-[28px] w-[28px] text-pending"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">@{{ count.patrols }}</div>
                                    <div class="mt-1 text-base text-slate-500">
                                        Patrouilles en cours
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
                <div v-if="!isDataLoading" class="col-span-12">
                    <div class="intro-y block h-10 items-center sm:flex items-center">
                        <h2 class="mr-5 uppercase font-extrabold truncate text-lg text-blue-500">
                            Situation globale des présences par sites
                        </h2>
                        <div class="mt-3 flex items-center sm:ml-auto sm:mt-0">
                            <div class="relative w-56 text-slate-500">
                                <input data-tw-merge="" v-model="search" type="text" placeholder="Recherche par site..."
                                    class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                                <i data-tw-merge="" data-lucide="search"
                                    class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                            </div>
                            <button @click="exportToPdf" data-tw-merge=""
                                class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed !box ml-3 flex text-slate-600 dark:text-slate-300"><i
                                    data-tw-merge="" data-lucide="file-text"
                                    class="stroke-1.5 mr-2 hidden h-4 w-4 sm:block"></i>
                                Export to PDF</button>
                        </div>
                    </div>
                    <div class="box intro-y mt-5 overflow-auto lg:overflow-visible">
                        <div class="overflow-x-auto">
                            <table
                                data-tw-merge
                                class="w-full text-left">
                                <thead
                                    data-tw-merge
                                    class="">
                                    <tr
                                        data-tw-merge
                                        class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                        <th
                                            data-tw-merge
                                            class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                            SITE
                                        </th>
                                        <th
                                            data-tw-merge
                                            class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                            SECTEUR
                                        </th>
                                        <th
                                            data-tw-merge
                                            class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-center">
                                            PRESENCES/AGENTS
                                        </th>
                                        <th
                                            data-tw-merge
                                            class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                            STATUT
                                        </th>
                                        <th
                                            data-tw-merge
                                            class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, index) in allPresences" :key="index"
                                        data-tw-merge
                                        class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                        <td
                                            data-tw-merge
                                            class="px-5 py-3 border-b dark:border-darkmode-300">
                                            <span class="font-extrabold text-blue-500 mr-2">@{{ data.code }}</span>
                                            @{{ data.name }}
                                        </td>
                                        <td
                                            data-tw-merge
                                            class="px-5 py-3 border-b dark:border-darkmode-300" :class="{'text-pending': data.reason === 'shutdown', 'text-blue-500': data.reason === 'shutdown'}">
                                            <span v-if="data.secteur">@{{ data.secteur.libelle }}</span>
                                        </td>
                                        <td
                                            data-tw-merge
                                            class="px-5 py-3 border-b dark:border-darkmode-300">
                                            <div class="flex justify-center items-center">
                                                @{{ data.presences.length }}/@{{ data.agents.length }}
                                            </div>
                                        </td>
                                        <td
                                            data-tw-merge
                                            class="px-5 py-3 border-b dark:border-darkmode-300 font-medium" :class="data.presences.length !== data.agents.length ? 'text-pending' : data.agents.length === 0 ? 'text-blue-500' : 'text-success'">
                                            @{{ data.presences.length !== data.agents.length ? 'Non complet' : data.agents.length === 0 ? 'Pas d\'agents affectés' : 'Complet' }}
                                        </td>
                                        <td
                                            data-tw-merge
                                            class="px-5 py-3 border-b dark:border-darkmode-300">
                                            <div class="flex items-center justify-center">
                                                <a href="#" class="mr-3 flex items-center text-blue-500">
                                                    <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination -->
                     <!-- BEGIN: Pagination -->
                    <Pagination
                        :current-page="pagination.current_page"
                        :last-page="pagination.last_page"
                        :total-items="pagination.total"
                        :per-page="pagination.per_page"
                        @page-changed="changePage"
                        @per-page-changed="onPerPageChange">
                    </Pagination>
                    <!-- END: Pagination -->
                    <!-- end pagination -->
                </div>
                <!-- END: Weekly Top Products -->
                <div v-if="isDataLoading" class="col-span-12">
                    <x-dom-loader></x-dom-loader>
                </div>
            </div>
        </div>
    </div>

    <x-dom-loader></x-dom-loader>

</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/monitoring.js") }}"></script>
@endpush