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
                    <a href="#">Pointages des agents</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-2" id="App" v-cloak>
        <div class="col-span-12">
            <div class="intro-y box mt-5 p-5">
                <div class="flex flex-col sm:flex-row sm:items-end xl:items-start flex-wrap">
                    <div class="sm:mr-auto xl:flex flex-wrap">
                        <div class="items-center sm:mr-4 sm:flex">
                            <select data-tw-merge="" @change="loadPresenceReports" v-model="currentMonth" id="tabulator-html-filter-field" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto 2xl:w-full">
                                <option v-for="month in months" :value="month.id">@{{ month.libelle }}</option>
                            </select>
                        </div>
                        <div class="items-center sm:mr-4 sm:flex">
                            <select v-model="currentYear" @change="loadPresenceReports" data-tw-merge="" id="tabulator-html-filter-field" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto 2xl:w-full">
                                <option v-for="year in years" :value="year">@{{ year }}</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <select data-tw-merge="" id="tabulator-html-filter-type" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto">
                                <option selected hidden value="">Statut</option>
                                <option value="deces">Décès</option>
                                <option value="absences">absences</option>
                                <option value="demission">Démission</option>
                                <option value="licenciement">Licenciement</option>
                                <option value="conge">Congé / Maladie</option>
                                <option value="mise_a_pied">Mise à pied</option>
                                <option value="absence_autorisee">Absence autorisée</option>
                                <option value="deserteur">Déserteur</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <select data-tw-merge="" id="tabulator-html-filter-type" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 sm:mt-0 sm:w-auto">
                                <option value="">Les retards </option>
                                <option value="moins3">Moins de 3 retards</option>
                                <option value="plus3">3 retards ou plus</option>
                            </select>
                        </div>

                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                           <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-48 ml-2">
                                <option value="" selected hidden>Filtrez par site</option>
                            </select>
                        </div>
                        <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                            <input data-tw-merge="" v-model="searchMatricule" id="tabulator-html-filter-value" type="text" placeholder="Recherche Par matricule..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 mt-2 sm:mt-0 sm:w-40 2xl:w-full">
                        </div>

                    </div>
                    <div class="mt-5 flex flex-wrap sm:mt-0 ml-2">
                        <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative w-1/2 sm:w-auto">
                            <button @click="exportToExcel" data-tw-merge="" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 w-full sm:w-auto">
                                <i data-tw-merge="" data-lucide="download" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12" v-if="!isDataLoading">
            <div class="box py-3 px-10">
                <h5 class="mb-2 underline font-extrabold text-blue-500">Légende des codes</h5>
                <div class="grid grid-cols-12">
                    <div class="col-span-12 lg:col-span-6">
                        <p class="text-xs"><strong>PP</strong> = Présences (1)<br>
                            <strong>A</strong> = Absences (0)<br>
                            <strong>M</strong> = Maladies<br>
                            <strong>C</strong> = Congés<br>
                            <strong>MP</strong> = Mises à pied<br>
                            <strong>AU</strong> = Absences autorisées
                        </p>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <p class="text-xs">
                            <strong>C1</strong> = Retards<br>
                            <strong>A1 / A2 / A3</strong> = Appels (1, 2 ou 3)<br>
                            <strong>CA1 / CA2 / CA3</strong> = Retard + Appels<br>
                            <strong>L</strong> = Licencié | <strong>D</strong> = Décédé<br>
                            <strong>DM</strong> = Démission | <strong>DS</strong> = Déserteur
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-12 2xl:col-span-12">
            <div v-if="isDataLoading">
                <x-dom-loader></x-dom-loader>
            </div>
            <!-- BEGIN: Inbox Content -->
            <div class="intro-y box px-4 py-2" v-else>
                <!-- TITRE -->
                <h4 class="text-center mb-3 font-extrabold text-xl">POINTAGES MENSUELS DES AGENTS - @{{ currentMonthName }}
                    @{{ currentYear }}</h4>
                <!-- TABLEAU -->
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th class="status-col">#</th>
                                <th class="status-col">Matricule</th>
                                <th class="status-col">Nom et Post-nom</th>
                                <th class="status-col">Poste</th>
                                <template v-for="day in daysInMonth">
                                    <th class="status-day">@{{ day }}</th>
                                </template>
                                <th class="status-col">PP</th>
                                <th class="status-col">A</th>
                                <th class="status-col">M</th>
                                <th class="status-col">C</th>
                                <th class="status-col">MP</th>
                                <th class="status-col">AU</th>
                                <th class="status-col">C1</th>
                                <th class="status-col">A1</th>
                                <th class="status-col">CA1</th>
                                <th class="status-col">L</th>
                                <th class="status-col">D</th>
                                <th class="status-col">DM</th>
                                <th class="status-col">DS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(agent, index) in displayAgents" :key="index">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ agent.matricule }}</td>
                                <td class="agent-name">@{{ agent.fullname }}</td>
                                <td>@{{ agent.poste }}</td>
                                <template v-for="day in daysInMonth">
                                    <td>@{{ agent.days[day] || '' }}</td>
                                </template>
                                <td class="clickable">@{{ agent.stats.pp }}</td>
                                <td class="clickable">@{{ agent.stats.a }}</td>
                                <td class="clickable" @click="openModal(agent, 'M','CONGES MALADIES')">
                                    @{{ agent.stats.m }}</td>
                                <td class="clickable" @click="openModal(agent, 'C','CONGES ANNUEL')">
                                    @{{ agent.stats.c }}</td>
                                <td class="clickable" @click="openModal(agent, 'MP','MISE A PIED')">
                                    @{{ agent.stats.mp }}</td>
                                <td class="clickable"
                                    @click="openModal(agent, 'AU','ABSENCE AUTORISEE')">
                                    @{{ agent.stats.au }}</td>
                                <td class="clickable">@{{ agent.stats.c1 }}</td>
                                <td class="clickable" @click="openModal(agent, 'A1','NOMBRE D APPEL')">
                                    @{{ agent.stats.a1 }}</td>
                                <td class="clickable"
                                    @click="openModal(agent, 'CA','NBRE APPEL POUR RETARD')">
                                    @{{ agent.stats.ca1 }}</td>
                                <td class="clickable" @click="openModal(agent, 'L','LICENCIEMENT')">
                                    @{{ agent.stats.l }}</td>
                                <td class="clickable" @click="openModal(agent, 'D','DECES')">
                                    @{{ agent.stats.d }}</td>
                                <td class="clickable" @click="openModal(agent, 'DM','DEMISSION')">
                                    @{{ agent.stats.dm }}</td>
                                <td class="clickable">@{{ agent.stats.ds }}</td>
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

    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection
@push("styles")
<style scoped>

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        min-width: 100%;
        border-collapse: collapse;

    }

    .table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        color: #ffffff;
        text-align: center;
        padding: 8px;
        border: 1px solid #dee2e6;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .table tbody td {
        text-align: center;
        padding: 8px;
        border: 1px solid #dee2e6;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .table tbody tr:nth-child(even) td {
        background-color: #f1f3f5;
    }

    .agent-name {
        text-align: left;
        font-weight: 600;
        color: #343a40;
    }

    .status-col {
        background-color: #8e1926;

        font-weight: 600;
    }

    .status-day {
        background-color: #0d6efd;
        ;
        font-weight: 600;
    }

    .clickable {
        cursor: pointer;
    }

    .clickable:hover {
        background-color: #dbeafe;
    }

    h4 {
        font-weight: 600;
        color: #0d6efd;
    }

    h5 {
        font-weight: 500;
        color: #495057;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .col-md-6 {
        flex: 1 1 45%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 1 1 100%;
        }

        .table thead th,
        .table tbody td {
            font-size: 0.7rem;
        }

        h4,
        h5 {
            font-size: 1rem;
        }
    }
</style>
@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/presence.js") }}"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
@endpush