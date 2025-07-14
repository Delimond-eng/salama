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
                    <a href="#">Gestion des éléments de contrôle</a>
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
        <div class="intro-y col-span-12 2xl:col-span-6 lg:col-span-6">
            <div class="box" v-if="allElements.length === 0">
                <div v-if="isDataLoading">
                    <x-dom-loader></x-dom-loader>
                </div>
                <div v-else>
                    <x-empty-state message="Aucun élément disponible."></x-empty-state>
                </div>
            </div>
            <div v-else>
                <div class="box">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="">
                                <tr class="">
                                    <th class="font-medium px-5 py-3 border-b-2 whitespace-nowrap dark:border-darkmode-400">
                                        LIBELLE & DESCRIPTION
                                    </th>
                                    <th class="font-medium px-5 py-3 border-b-2 whitespace-nowrap text-right dark:border-darkmode-400">
                                        ACTIONS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="" v-for="(data, index) in allElements" :key="index">
                                    <td class="px-5 py-3 border-b dark:border-darkmode-400">
                                        <div class="whitespace-nowrap font-medium">
                                          @{{ data.libelle }}
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-sm text-slate-500">
                                           @{{ data.description }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-3 border-b w-32 text-right font-medium dark:border-darkmode-400">
                                        <div class="flex items-center font-medium">

                                            <button @click="form.id=data.id; form.libelle = data.libelle;" class="text-blue-500 border border-slate-200 ml-1 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                                <i data-lucide="edit" class="w-3 h-3"></i>
                                            </button>

                                            <button @click="deleteElement(data)" class="text-danger border border-slate-200 ml-1 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                                <span class="h-3 w-3" v-if="delete_id == data.id">
                                                    <svg width="14" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="red">
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
                                                <i v-else data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>     
                            </tbody>
                        </table>
                    </div>
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

        <div class="intro-y col-span-12 2xl:col-span-6 lg:col-span-6">
            <div class="grid grid-cols-1">
                <div class="col-span-12">
                    <!-- BEGIN: Vertical Form -->
                    <div class="intro-x box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="mr-auto text-base font-medium">
                                Création élément de contrôle de supervision
                            </h2>
                        </div>
                        <div class="p-5">
                            <div v-if="error" role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                                <strong class="mr-2">Erreur : </strong> @{{ error }}
                                <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i data-tw-merge data-lucide="x" class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                            </div>
                            <form class="preview form-horaire relative [&.hide]:overflow-hidden [&.hide]:h-0" method="POST" @submit.prevent="createElement">
                                <div>
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Libellé *
                                    </label>
                                    <input v-model="form.libelle" id="vertical-form-1" type="text" placeholder="Libellé." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                </div>
                                <div class="mt-3">
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Description (optionnelle)
                                    </label>
                                    <textarea v-model="form.description" placeholder="Description..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10"></textarea>
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

        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">La création de l'élément effectuée ! </div>
            </div>
        </div>
    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/config.js") }}"></script>
@endpush