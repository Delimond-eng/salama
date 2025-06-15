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
                    <a href="#">Utilisateurs</a>
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
                    Liste des utilisateurs
                </h2>

                <div class="mx-auto hidden text-slate-500 xl:block">

                </div>
                <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                    <div class="relative w-56 text-slate-500">
                        <input data-tw-merge="" v-model="search"  type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                        <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                </div>
            </div>

            <div class="box mt-3" v-if="allUsers.length > 0">
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
                                    NOM
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    EMAIL
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    ROLE
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
                            <tr v-for="(data, index) in allUsers" :key="index"
                                data-tw-merge
                                class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.name }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300" :class="{'text-pending': data.reason === 'shutdown', 'text-blue-500': data.reason === 'shutdown'}">
                                    @{{ data.email }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.role }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.status }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center">
                                        <a href="#" class="mr-3 flex items-center text-blue-500">
                                            <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                        </a>
                                        <a class="flex text-danger" href="#" @click="deleteAgent(data)">
                                            <!-- <span class="ml-2 h-4 w-4" v-if="data.id === delete_id">
                                                <svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="red">
                                                    <g fill="none" fill-rule="evenodd">
                                                        <g transform="translate(1 1)" stroke-width="4">
                                                            <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                                            <path d="M36 18c0-9.94-8.06-18-18-18">
                                                                <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </span> -->
                                            <i data-tw-merge="" data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                        </a>
                                    </div>
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
                    <x-empty-state message="Aucun utilisateur répertorié pour l'instant."></x-empty-state>
                </div>
            </div>

            <!-- BEGIN: Pagination -->
            <Pagination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total-items="pagination.total"
                :per-page="pagination.per_page"
                @page-changed="changePage"
                @per-page-changed="onPerPageChange" />
            <!-- END: Pagination -->

        </div>
        <!-- END: Weekly Top Products -->
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/auth.js") }}"></script>
@endpush