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
                    <a href="#">Configuration plannings automatiques</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->


    <div id="App" v-cloak>
         <!-- END: Top Bar -->
        <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
            <h2 class="mr-auto text-lg font-medium">Configuration plannings automatiques</h2>
            <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
                <div class="relative w-56 text-slate-500">
                    <input v-model="search" type="text" @input="onSearchInput" placeholder="Recherche par site..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                    <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Transaction Details -->
        <div class="intro-y mt-5 grid grid-cols-11 gap-5">
            <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
                <div class="box rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Plannings automatiques
                        </div>
                    </div>
                    <div class="-mt-3 overflow-auto lg:overflow-visible" v-if="allSitePlannings.length">
                        <table data-tw-merge="" class="w-full text-left">
                            <thead data-tw-merge="" class="">
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap !py-5">
                                        Site 
                                    </th>
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Heure debut
                                    </th>
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Intervalle
                                    </th>
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Pause
                                    </th>
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Nbre total
                                    </th>
                                    <th data-tw-merge="" class="font-extrabold px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allSitePlannings" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        @{{ data.site.name }}
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        @{{ data.start_hour }}
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        @{{ data.interval }}
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        @{{ data.pause }}
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        @{{ data.number_of_plannings }}
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        <div class="flex">
                                            <input type="checkbox" @change="activateAutoPlanning($event,data.site_id)" :checked="data.activate == 1 ? true : false"
                                                class="transition-all mr-3 duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white ml-3 mr-0"
                                                id="show-example-1"> 
                                            <button @click.stop="setFormData(data)" class="text-blue-500 border-0"> <i class="w-4 h-4" data-lucide="edit"></i> </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div>
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
                    <div v-else>
                        <div v-if="isDataLoading">
                            <x-dom-loader></x-dom-loader>
                        </div>
                        <div v-else>
                            <x-empty-state message="Aucune configuration automatique disponible."></x-empty-state>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 2xl:col-span-3 lg:col-span-4">
                <div class="grid grid-cols-1">
                    <div class="col-span-12">
                        <!-- BEGIN: Vertical Form -->
                        <div class="intro-x box">
                            <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                                <h2 class="mr-auto text-base font-medium">
                                    Création nouveau secteur
                                </h2>
                            </div>
                            <div class="p-5">
                                <form class="preview form-horaire relative [&.hide]:overflow-hidden [&.hide]:h-0" method="POST" @submit.prevent="createPlanningConfiguration">
                                    <div>
                                        <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Site concerné
                                        </label>
                                        <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-full">
                                            <option value="" selected hidden>Sélectionnez un site</option>
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Heure début
                                        </label>
                                        <input v-model="form.start_hour" id="vertical-form-1" type="time" placeholder="Libellé." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    </div>
                                    <div class="mt-2">
                                        <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Intervalle(Fréquence)
                                        </label>
                                        <input v-model="form.interval" id="vertical-form-1" type="text" placeholder="intervalle ou fréquence..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    </div>
                                    <div class="mt-2">
                                        <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Pause (en heure)
                                        </label>
                                        <input v-model="form.pause" id="vertical-form-1" type="text" placeholder="Pause en heure. ex:1(heure)" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    </div>
                                    <div class="mt-2">
                                        <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Nombre total
                                        </label>
                                        <input v-model="form.number_of_plannings" id="vertical-form-1" type="text" placeholder="Nbre total de ronde. ex:5" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    </div>
                                    <button :disabled="isLoading" type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary mt-5">Enregister
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
                                    <button type="reset" @click.stop="reset" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-white text-slate-800 dark:border-darkmode-100 mr-2 shadow-md">
                                        Annuler</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Transaction Details -->

        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">@{{ result }} </div>
            </div>
        </div>
        <div id="error-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 text-danger"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">@{{ error }} </div>
            </div>
        </div>
    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/configs.js") }}"></script>
@endpush