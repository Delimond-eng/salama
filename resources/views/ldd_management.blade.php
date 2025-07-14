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
                    <a href="#">Gestion des ruptures de contrat</a>
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
                    Liste des ruptures de contrat
                </h2>

                <div class="mx-auto hidden text-slate-500 xl:block">

                </div>
                <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                    <div class="relative w-56 text-slate-500">
                        <input data-tw-merge="" v-model="search" type="text" placeholder="Matricule agent..."
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                        <i data-tw-merge="" data-lucide="search"
                            class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                    <select data-tw-merge=""
                        class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box ml-2 w-56 xl:w-auto">
                        <option selected hidden value="">Type</option>
                        <option>Licencement</option>
                        <option>Decès</option>
                    </select>

                    <button data-tw-toggle="modal" data-tw-target="#modal-ldd"
                        class="bg-primary ml-2 text-white transition duration-200 border border-primary shadow-sm inline-flex items-center justify-center py-2 px-2 rounded-lg font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 disabled:opacity-70 disabled:cursor-not-allowed hover:bg-opacity-90 hover:border-opacity-90">
                        <span class="flex h-4 w-4 items-center justify-center">
                            <i class="w-3 h-3" data-lucide="plus"></i>
                        </span>
                    </button>
                </div>
            </div>

            <div class="box mt-3">
                <div class="overflow-x-auto">
                    <table data-tw-merge class="w-full text-left">
                        <thead data-tw-merge class="">
                            <tr data-tw-merge
                                class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    AGENT
                                </th>
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    TYPE
                                </th>
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    DATE
                                </th>
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    CAUSE
                                </th>
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                    STATUT
                                </th>
                                <th data-tw-merge
                                    class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in cessations" :key="index" data-tw-merge
                                class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <span class="font-extrabold">@{{ data.agent.matricule }}</span> @{{ data.agent.fullname }}
                                </td>
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300"
                                    :class="{'text-pending': data.reason === 'shutdown', 'text-blue-500': data.reason === 'shutdown'}">
                                    @{{ data.type }}
                                </td>

                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.date }}
                                </td>
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300">
                                    @{{ data.cause }}
                                </td>
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center font-medium text-primary">
                                        <i data-tw-merge="" data-lucide="check-square"
                                            class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        @{{ data.status }}
                                    </div>
                                </td>
                                <td data-tw-merge class="px-5 py-3 border-b dark:border-darkmode-300">
                                    <div class="flex items-center justify-center">
                                        <a href="#" class="mr-3 flex items-center text-blue-500">
                                            <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                        </a>
                                        <a class="flex text-danger" href="#">
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

            <!-- <div v-else>
                <div v-if="isDataLoading">
                    <x-dom-loader></x-dom-loader>
                </div>
                <div class="box mt-4" v-else>
                    <x-empty-state message="Aucun utilisateur répertorié pour l'instant."></x-empty-state>
                </div>
            </div> -->

            <!-- BEGIN: Pagination -->
            <Pagination :current-page="pagination.current_page" :last-page="pagination.last_page"
                :total-items="pagination.total" :per-page="pagination.per_page" @page-changed="changePage"
                @per-page-changed="onPerPageChange"></Pagination>
            <!-- END: Pagination -->

        </div>

        <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="modal-ldd"
            class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <form method="POST" @submit.prevent="createCessation" data-tw-merge
                class="form-agent w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px] lg:w-[600px]">
                <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium uppercase">
                        Enregistrement Rupture de contrat
                    </h2>
                    <button id="btn-reset" data-tw-merge type="button" data-tw-dismiss="modal" @click.prevent="reset"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                            data-tw-merge data-lucide="x" class="stroke-1.5 h-4 w-4"></i>
                    </button>
                </div>
                <div data-tw-merge class="p-5 grid grid-cols-12 gap-4">
                    <div class="col-span-12">
                        <div v-if="error" role="alert"
                            class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center">
                            <i data-tw-merge data-lucide="alert-circle"
                                class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                            @{{ error }}
                            <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button"
                                aria-label="Close"
                                class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i
                                    data-tw-merge data-lucide="x"
                                    class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="vertical-form-1"
                                class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Agent concerné *
                            </label>
                            <select data-placeholder="Sélectionnez un superviseur" v-model="form.agent_id"
                                class="tom-select w-full" required>
                                <option value="">Sélectionnez un agent</option> <!-- Placeholder visible -->
                                @foreach ($agents as $sup )
                                <option value="{{ $sup->id }}">{{ $sup->matricule  }} | {{ $sup->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="vertical-form-1"
                                class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Type Cessations *
                            </label>
                            <select v-model="form.type"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10"
                                required>
                                <option value="" selected hidden>Sélectionnez un type</option>
                                <!-- Placeholder visible -->
                                <option value="Licenciement">Licenciement</option>
                                <option value="Deces">Decès</option>
                                <option value="Deces">Demission</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <label for="vertical-form-1"
                            class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                            Date *
                        </label>
                        <input id="vertical-form-1" type="date" v-model="form.date"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                    </div>

                    <div class="col-span-12">
                        <label for="vertical-form-1"
                            class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                            Cause(optionnel)
                        </label>
                        <textarea v-model="form.cause" placeholder="Cause de la cessation(optionnel)"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10"></textarea>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400">
                    <button @click.prevent="reset" data-tw-merge data-tw-dismiss="modal" type="button"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-1 w-20 mr-1 w-20">Fermer</button>
                    <button data-tw-merge type="submit"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary w-auto"
                        :disabled="isLoading">Soumettre la modification
                        <span class="ml-2 h-4 w-4" v-if="isLoading">
                            <svg class="h-full w-full" width="25" viewBox="-2 -2 42 42"
                                xmlns="http://www.w3.org/2000/svg" stroke="white">
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(1 1)" stroke-width="4">
                                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                        <path d="M36 18c0-9.94-8.06-18-18-18">
                                            <animateTransform type="rotate" attributeName="transform" from="0 18 18"
                                                to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- END: Weekly Top Products -->
        <div id="success-notification-content"
            class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">Création de l'utilisateur effectuée! </div>
            </div>
        </div>


        <div id="failed-notification-content"
            class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="x-circle" class="stroke-1.5 w-5 h-5 text-danger"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Echec de traitement !</div>
                <div class="text-slate-500 mt-1">@{{ errorA }}.</div>
            </div>
        </div>
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/rh.js") }}"></script>
@endpush
