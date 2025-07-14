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
                    <a href="#">Rapport de planning superviseurs</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <div class="grid grid-cols-12 gap-3" id="App" v-cloak>
        <div class="col-span-12">
            <div class="intro-y box mt-5 p-5">
                <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form class="sm:mr-auto xl:flex">
                        <div class="items-center sm:mr-4 sm:flex">
                            <label class="mr-2 w-12 uppercase flex-none xl:w-auto xl:flex-initial">
                                Secteur
                            </label>
                            <select data-tw-merge="" @change="onSectorChanged" id="tabulator-html-filter-field" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto 2xl:w-full">
                                <option value="" hidden selected>Secteur</option>
                                <option v-for="(data, index) in allSectors" :value="data">@{{ data.libelle }}</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <label class="mr-2 w-30 uppercase flex-none xl:w-auto xl:flex-initial">
                               Site
                            </label>
                            <select data-tw-merge="" id="tabulator-html-filter-type" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto">
                                <option value="" selected hidden>Site</option>
                                <option v-for="(data, index) in allSites" :value="data.id">@{{ data.name }}</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <label class="mr-2 w-12 uppercase flex-none xl:w-auto xl:flex-initial">
                               Période
                            </label>
                            <select data-tw-merge="" id="tabulator-html-filter-type" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto">
                                <option value="" selected hidden>Période</option>
                                <option value="=">Journalière</option>
                                <option value="&lt;">Hébdo</option>
                                <option value="&lt;=">Mensuelle</option>
                                <option value="&gt;">Annuelle</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <label class="mr-2 w-12 uppercase flex-none xl:w-auto xl:flex-initial">
                                Agent
                            </label>
                            <input data-tw-merge="" id="tabulator-html-filter-value" type="text" placeholder="Matricule ou nom..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 mt-2 sm:mt-0 sm:w-40 2xl:w-full">
                        </div>
                        <div class="mt-2 items-center sm:mr-2 sm:flex xl:mt-0">
                            <label class="mr-2 w-12 uppercase flex-none xl:w-auto xl:flex-initial">
                                Date
                            </label>
                            <input data-tw-merge="" id="tabulator-html-filter-value" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 mt-2 sm:mt-0 sm:w-40 2xl:w-full">
                        </div>
                        <div class="mt-2 xl:mt-0">
                            <button data-tw-merge="" id="tabulator-html-filter-go" type="button" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary"> 
                                <i class="stroke-1.5 ml-auto h-5 w-4" data-lucide="search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="mt-5 flex sm:mt-0 ml-2">
                        <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative w-1/2 sm:w-auto">
                            <button data-tw-merge="" data-tw-toggle="dropdown" aria-expanded="false" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 w-full sm:w-auto"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Export
                                <i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 ml-auto h-4 w-4 sm:ml-2"></i></button>
                            <div data-transition="" data-selector=".show" data-enter="transition-all ease-linear duration-150" data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1" data-enter-to="!mt-1 visible opacity-100 translate-y-0" data-leave="transition-all ease-linear duration-150" data-leave-from="!mt-1 visible opacity-100 translate-y-0" data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1" class="dropdown-menu absolute z-[9999] hidden">
                                <div data-tw-merge="" class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 w-40">
                                    <a id="tabulator-export-csv" class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        Export CSV</a>
                                    <a id="tabulator-export-json" class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        Export
                                        JSON</a>
                                    <a id="tabulator-export-xlsx" class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        Export
                                        XLSX</a>
                                    <a id="tabulator-export-html" class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        Export
                                        HTML</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Weekly Top Products -->
        <div class="col-span-12 lg:col-span-12">
            <div class="box -intro-y" v-if="allReports.length">
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
                                    DATE PLANNING
                                </th>
                                <th
                                    data-tw-merge
                                    class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap ">
                                    LIBELLé
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    SUPERVISEUR
                                </th>
                                <th
                                    data-tw-merge
                                    class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    SITES à SUPERVISER
                                </th>
                                <th
                                    data-tw-merge
                                    class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    SITES Supervisés
                                </th>
                                <th
                                    data-tw-merge
                                    class="uppercase font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    STATUS
                                </th>
                                <th
                                    data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-center">
                                    ACTIONS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in allReports"
                                data-tw-merge
                                class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    @{{ data.date }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    @{{ data.title }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    @{{ data.agent.matricule }} @{{ data.agent.fullname }}
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center">
                                        @{{ data.sites.length }}
                                    </div>
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center">
                                        @{{ data.presences.length }}
                                    </div>
                                </td>

                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center font-medium" :class="{'text-blue-500': status(data) === 'En attente', 'text-pending': status(data)==='Partielle', 'text-danger': status(data)==='Non effectuée', 'text-success': status(data)==='Effectuée'}">
                                        <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        @{{ status(data) }}
                                    </div>
                                </td>
                                <td
                                    data-tw-merge
                                    class="px-3 py-2 border-b dark:border-darkmode-300">
                                    <div class="flex justify-center items-center">
                                        <button @click="selectedReport = data" data-tw-toggle="modal" data-tw-target="#details-modal"
                                            class="bg-primary text-white border border-primary rounded-lg px-2 py-2 text-sm hover:bg-primary/20 hover:border-primary/50">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
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
                    <x-empty-state message="Aucun rapport de supervision répertorié !"></x-empty-state>
                </div>
            </div>

            <!-- BEGIN: Pagination -->
            <Pagination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total-items="pagination.total"
                :per-page="pagination.per_page"
                @page-changed="changePage"
                @per-page-changed="onPerPageChange">
            </Pagination>
        </div>
        <!-- END: Weekly Top Products -->

        <div data-tw-backdrop="" aria-hidden="false" tabindex="-1" id="details-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] overflow-y-auto" style="padding-left: 0px; margin-top: 0px; margin-left: 0px; z-index: 10000;">
            <div  v-if="selectedReport" class="w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[800px] lg:w-[900px]">
                <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium uppercase">
                        Détails de la supervision
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
                <div data-tw-merge="" forminline="formInline" class="block sm:flex group form-inline flex-col items-start pt-5 first:mt-0 first:pt-0 xl:flex-row p-5">
                    <div class="w-full flex-1 xl:mt-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border border-slate-300" v-if="selectedReport.presences.length">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-700">
                                        <th class="px-5 py-3 border-b-2">INFOS</th>
                                        <th class="px-5 py-3 border-b-2">AGENT</th>
                                        <th class="px-5 py-3 border-b-2">ÉLÉMENT</th>
                                        <th class="px-5 py-3 border-b-2">NOTE</th>
                                    </tr>
                                </thead>
                                <tbody v-if="selectedReport.presences.length">
                                    <template v-for="(presence, pIndex) in selectedReport.presences">
                                        <template v-if="presence.elements.length">
                                            <tr v-for="(element, eIndex) in presence.elements" :key="eIndex">
                                                <!-- Infos générales (1 fois seulement) -->
                                                <td v-if="pIndex === 0 && eIndex === 0"
                                                    :rowspan="totalElements"
                                                    class="px-5 py-3 border-b border-r align-top text-sm text-dark bg-slate-100">
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Site :</strong> @{{ presence.site.name }}</div>
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Date :</strong> @{{ presence.date }}</div>
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Heure début :</strong> @{{ presence.started_at }}</div>
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Heure fin :</strong> @{{ presence.ended_at }}</div>
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Durée :</strong> @{{ presence.duree }}</div>
                                                    <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Distance status :</strong> <span class="italic" :class="presence.distance <= 500 ? 'text-success' : 'text-pending'">@{{ presence.distance <= 500 ? 'Dans le site' : 'Hors site' }}</span> </div>
                                                </td>

                                                <!-- Nom de l’agent (1 fois par agent) -->
                                                <td v-if="eIndex === 0"
                                                    :rowspan="presence.elements.length"
                                                    class="px-5 py-3 border-b border-r font-bold align-middle">
                                                    <div class="flex flex-column justify-center items-center text-blue-500">
                                                        @{{ element.agent.matricule }}<br>
                                                        @{{ element.agent.fullname }}
                                                    </div>
                                                </td>
                                                <!-- Élément & Note -->
                                                <td class="px-5 py-3 border-b">@{{ element.element.libelle }}</td>
                                                <td class="px-5 py-3 border-b font-extrabold" :class="colored(element.note)">@{{ element.note }}</td>
                                            </tr>
                                        </template>

                                        <tr v-else>
                                            <td v-if="pIndex === 0"
                                                :rowspan="totalElements || 1"
                                                class="px-5 py-3 border-b border-r align-top text-sm text-slate-700 bg-slate-100">
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Site :</strong> @{{ presence.site.name }}</div>
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Date :</strong> @{{ presence.date }}</div>
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Heure début :</strong> @{{ presence.started_at }}</div>
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Heure fin :</strong> @{{ presence.ended_at }}</div>
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Durée :</strong> @{{ presence.duree }}</div>
                                                <div class="flex justify-between py-2 border-b border-dashed border-gray-300"><strong>Distance status :</strong> <span class="italic" :class="presence.distance <= 500 ? 'text-success' : 'text-pending'">@{{ presence.distance <= 500 ? 'Dans le site' : 'Hors site' }}</span> </div>
                                            </td>

                                            <!-- Nom de l'agent -->
                                            <td class="px-5 py-3 border-b border-r italic font-bold align-middle text-pending">
                                               Aucun agent
                                            </td>

                                            <!-- Message "non supervisé" -->
                                            <td colspan="2" class="px-5 py-3 border-b italic text-pending">
                                                Aucun élément supervisé
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <div class="box mt-4" v-else>
                                <x-empty-state message="Aucune supervision effectué !"></x-empty-state>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="selectedReport.presences.length"
                    class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400">
                    <button
                        data-tw-merge
                        data-tw-dismiss="modal" type="button" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-1 w-20 mr-1 w-20">Fermer</button>
                    <button
                        data-tw-merge
                        type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary w-auto" :disabled="isLoading">
                        Exporter en PDF
                        <span class="ml-2 h-4 w-4" v-if="isLoading">
                            <svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="white">
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
                    </button>
                </div>
            </div>
        </div>
    </div>
    <x-dom-loader></x-dom-loader>
    <!-- END: Top Bar -->
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/planning.js") }}"></script>
@endpush