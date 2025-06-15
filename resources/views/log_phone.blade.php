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
                    <a href="#">Rapport logs Téléphone</a>
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
        <!-- BEGIN: Weekly Top Products -->
        <div class="col-span-12 lg:col-span-10 mt-3">
            <div class="intro-y block h-10 items-center sm:flex">
                <h2 class="mr-5 truncate text-lg font-medium">
                    Liste de Rapport Logs
                </h2>
                <div class="mt-3 flex items-center sm:ml-auto sm:mt-0">
                    <div class="relative w-56 text-slate-500 ">
                        <input data-tw-merge="" v-model="filter_date" @input="viewPhoneLogs" type="date"  class="border border-slate-400 disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                        <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                    <button data-tw-merge=""
                        class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed !box ml-3 flex text-slate-600 dark:text-slate-300"><i
                            data-tw-merge="" data-lucide="file-text"
                            class="stroke-1.5 mr-2 hidden h-4 w-4 sm:block"></i>
                        Export to PDF</button>
                </div>
            </div>

            <div class="box mt-3" v-if="phoneLogs.length > 0">
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
                                    DATE & HEURE
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    RAISON/MOTIF
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    NIVEAU BATTERIE
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    AGENT
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    SITE
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in phoneLogs" :key="index"
                                data-tw-merge
                                class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.date_and_time }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300" :class="{'text-pending': data.reason === 'shutdown', 'text-blue-500': data.reason === 'shutdown'}">
                                    @{{ data.reason }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.battery_level }} %
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <div v-if="data.agent">@{{ data.agent.matricule }} | @{{ data.agent.fullname }}</div>
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <div v-if="data.site">@{{ data.site.code }} | @{{ data.site.name }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else>
               <div v-if="isDataLoading">
                   <x-dom-loader></x-dom-loader>
               </div>
               <div class="box mt-4" v-else>
                    <x-empty-state message="Aucune rapport de log disponible pour l'instant."></x-empty-state>
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
            />
            <!-- END: Pagination -->

        </div>
        <!-- END: Weekly Top Products -->
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/logs.js") }}"></script>
@endpush