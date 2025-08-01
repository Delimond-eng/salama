@extends("layouts.app")

@section("content")
<!-- BEGIN: Content -->
<div class="md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
    <!-- BEGIN: Top Bar -->
    <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200 overflow-auto 2xl:overflow-visible">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex">
            <ol class="flex items-center text-theme-1 dark:text-slate-300">
                <li class="">
                    <a href="#">Salama</a>
                </li>
                <li class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 cursor-text dark:text-slate-400">
                    <a href="#">Historique de mouvement des agents</a>
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

        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center xl:flex-nowrap">
            <button @click.stop="exportToExcel" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-white text-slate-800 dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                Exporter en Exel</button>
            <div class="mx-auto hidden text-slate-500 xl:block">

            </div>
            <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                <div class="relative w-56 text-slate-500 mr-2">
                    <input v-model="search" @input="onSearchInputed" type="text" placeholder="Matricule ou nom..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                    <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
                <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-48">
                    <option value="" selected hidden>Site</option>
                </select>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <!-- <div class="col-span-12 overflow-auto 2xl:overflow-visible intro-y" v-if="allHistories.length">
            <div class="overflow-x-auto box">
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
                                NOM & MATRICULE
                            </th>
                            <th
                                data-tw-merge
                                class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                DATE D'AFFECTATION
                            </th>
                            <th
                                data-tw-merge
                                class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                SITE AFFECTé
                            </th>
                            <th
                                data-tw-merge
                                class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                SITE PROVENANCE
                            </th>
                            <th
                                data-tw-merge
                                class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                STATUT
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in allHistories" :key="index"
                            data-tw-merge
                            class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                            <td
                                data-tw-merge
                                class="px-5 py-3 border-b dark:border-darkmode-300">
                                <span v-if="data.agent" class="font-extrabold text-primary">@{{ data.agent.matricule }}</span> | @{{ data.agent.fullname }}
                            </td>
                            <td
                                data-tw-merge
                                class="px-5 py-3 border-b dark:border-darkmode-300">
                                @{{ data.created_at }}
                            </td>
                            <td
                                data-tw-merge
                                class="px-5 py-3 border-b dark:border-darkmode-300">
                                @{{ data.site.code }} | @{{ data.site.name }}
                            </td>
                            <td
                                data-tw-merge
                                class="px-5 py-3 border-b dark:border-darkmode-300">
                                <span v-if="data.from">
                                    @{{ data.from.code }} | @{{ data.from.name }}
                                </span>
                                <span v-else class="text-decoration-underline">
                                    Non défini
                                </span>
                            </td>
                            <td
                                data-tw-merge
                                class="px-5 py-3 border-b dark:border-darkmode-300">
                                @{{ data.status }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
        <div class="col-span-12"  v-if="allHistories.length">
            <div class="box rounded-md p-5">
                <div class="-mt-3 overflow-auto lg:overflow-visible">
                    <table data-tw-merge="" class="w-full text-left">
                        <thead data-tw-merge="" class="">
                            <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap !py-5">
                                    AGENT
                                </th>
                                <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                   DATE
                                </th>
                                <th data-tw-merge="" class="uppercase font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                    SITE AFFECTé
                                </th>
                                <th data-tw-merge="" class="uppercase font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                    SITE PROVENANCE
                                </th>
                                <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                    STATUS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in allHistories" :key="index" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                    <div class="flex items-center" v-if="data.agent">
                                        <div class="image-fit zoom-in h-9 w-9">
                                            <img v-if="data.agent.photo" data-action="zoom" data-placement="top" title="Photo" :src="data.agent.photo" alt="photo" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            <img v-else data-placement="top" title="Avatar" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                        </div>
                                        <div class="ml-4">
                                            <a class="whitespace-nowrap font-medium" href="#">
                                                @{{ data.agent.fullname }}
                                            </a>
                                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                                @{{ data.agent.matricule }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                    @{{ data.date }}
                                </td>
                                <td data-tw-merge="" class="px-5 text-blue-500 font-bold py-3 border-b dark:border-darkmode-300 text-right">
                                     @{{ data.site.name }}
                                </td>
                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                    <span class="text-primary font-bold" v-if="data.from">
                                     @{{ data.from.name }}
                                    </span>
                                    <span v-else class="text-decoration-underline">
                                        Non défini
                                    </span>
                                </td>
                                <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                    <span class="ml-1 text-xs rounded bg-success/20 p-1 text-primary">
                                        @{{ data.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END: Data List -->

        <div class="col-span-12" v-else>
            <div v-if="isDataLoading">
                <x-dom-loader></x-dom-loader>
            </div>
            <div v-else>
                <div class="box">
                    <x-empty-state message="Aucun agent répertorié !"></x-empty-state>
                </div>
            </div>
        </div>

        <div class="col-span-12">
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

        <!-- BEGIN: Pagination -->
        <!-- END: Pagination -->
    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/agent_manager.js") }}"></script>
@endpush