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
                    <a href="#">Liste des agents</a>
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
            <button data-tw-merge="" onclick="location.href='/agent.create'" class="transition duration-200 border inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary mr-2 shadow-md"> <i class="w-3 h-3" data-lucide="plus"></i> Nouveau agent</button>
            <button @click.stop="exportToExcel" data-tw-merge="" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-white text-slate-800 dark:border-darkmode-100 mr-2 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                Exporter en Exel</button>
            <div class="mx-auto hidden text-slate-500 xl:block">

            </div>
            <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                <div class="relative w-56 text-slate-500">
                    <input data-tw-merge="" v-model="search" type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                    <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
                <select v-model="filter_status" @change="viewAllAgents" data-tw-merge="" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box ml-2 w-56 xl:w-auto">
                    <option value="" selected hidden>Status</option>
                    <option value="">Tout</option>
                    <option value="permenant">Permenant</option>
                    <option value="dispo">Dispo</option>
                </select>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible" v-if="allAgents.length > 0">
            <table data-tw-merge="" class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                <thead data-tw-merge="" class="">
                    <tr data-tw-merge="" class="">
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            NOM COMPLET & MATRICULE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            ROLE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            SITE AFFECTE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            GROUPE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            MOT DE PASSE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            STATUS
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-1">
                            ACTIONS
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr data-tw-merge="" class="intro-y" v-for="(data, index) in allAgents" :key="index">
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 !py-3.5 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex">
                                <div class="image-fit zoom-in h-9 w-9">
                                    <img v-if="data.photo" data-action="zoom" data-placement="top" title="Uploaded at 29 September 2020" :src="data.photo" alt="photo" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                    <img v-else data-placement="top" title="Uploaded at 29 September 2020" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar" class="tooltip cursor-pointer rounded-lg border-white shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                </div>
                                <div class="ml-4">
                                    <a class="whitespace-nowrap font-medium" href="#">
                                        @{{ data.fullname }}
                                    </a>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        @{{ data.matricule }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <a class="flex underline decoration-dotted" href="#">
                                @{{ getRole(data.role) }}
                            </a>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 text-center shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <a v-if="data.site" class="whitespace-nowrap font-medium" href="#">
                                @{{ data.site.name }}
                            </a>
                            <div v-if="data.site" class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                @{{ data.site.code }}
                            </div>
                            <span v-else>Non assigné.</span>
                        </td>

                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 text-center shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <span v-if="data.groupe">@{{data.groupe.libelle}}</span>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 text-center shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            @{{data.password}}
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex text-success">
                                <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Actif
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                            <div class="flex items-center justify-center">
                                <a href="#" class="mr-3 flex items-center text-blue-500" data-tw-toggle="modal" data-tw-target="#agent-edit-modal" @click="editAgent(data)">
                                    <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                    Editer
                                </a>
                                <a class="flex text-danger" href="#" @click="deleteAgent(data)">
                                    <span class="ml-2 h-4 w-4" v-if="data.id === delete_id">
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
                                    </span>
                                    <i v-else data-tw-merge="" data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- BEGIN: Pagination -->
        <Pagination
            :current-page="pagination.current_page"
            :last-page="pagination.last_page"
            :total-items="pagination.total"
            :per-page="pagination.per_page"
            @page-changed="changePage"
            @per-page-changed="onPerPageChange"></Pagination>
        <!-- END: Pagination -->
        </div>
        <!-- END: Data List -->
        

        <div class="col-span-12" v-else>
            <div v-if="isDataLoading">
                <x-dom-loader></x-dom-loader>
            </div>
            <div v-else class="relative mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="box">
                    <x-empty-state message="Aucun agent répertorié !"></x-empty-state>
                </div>
            </div>

        </div>

        <!-- BEGIN: Modal Content -->
        <div
            data-tw-backdrop=""
            aria-hidden="true"
            tabindex="-1"
            id="agent-edit-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <form method="POST" action="{{ route("agent.create") }}" @submit.prevent="createAgent" enctype="multipart/form-data"
                data-tw-merge
                class="form-agent w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px] lg:w-[900px]">
                <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium uppercase">
                        Modification agent
                    </h2>
                    <button id="btn-reset"
                        data-tw-merge
                        type="button"
                        data-tw-dismiss="modal"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                            data-tw-merge
                            data-lucide="x"
                            class="stroke-1.5 h-4 w-4"></i>
                    </button>
                </div>
                <div
                    data-tw-merge
                    class="p-5 grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <div v-if="error" role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                            @{{ error }}
                            <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i data-tw-merge data-lucide="x" class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 col-span-12">
                        <div class="col-span-12 lg:col-span-4">
                            <div class="w-52">
                                <div class="rounded-md border-2 border-dashed border-slate-200/60 p-5 shadow-sm dark:border-darkmode-400">
                                    <div class="image-fit zoom-in relative mx-auto h-40 cursor-pointer">
                                        <img v-if="preview" :src="preview" class="rounded-md object-cover h-full w-full" alt="Aperçu">
                                        <img v-else class="rounded-md" src="{{ asset('assets/images/profil-2.png') }}" alt="Photo par défaut">
                                        <div v-if="preview"
                                            data-placement="top"
                                            class="tooltip cursor-pointer absolute right-0 top-0 -mr-2 -mt-2 flex h-5 w-5 items-center justify-center rounded-full bg-danger text-white"
                                            @click="removePhoto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-x stroke-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M18 6 6 18" />
                                                <path d="m6 6 12 12" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="relative mx-auto mt-5 cursor-pointer">
                                        <button type="button" class="bg-primary border-primary text-white w-full rounded-md py-2 px-3 font-medium">
                                            Charger photo
                                        </button>
                                        <input
                                            type="file"
                                            ref="photoInput"
                                            @change="onFileChange"
                                            class="absolute left-0 top-0 h-full w-full opacity-0 cursor-pointer"
                                            accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-8">
                            <div>
                                <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Nom complet *
                                </label>
                                <input id="vertical-form-1" v-model="form.fullname" type="text" placeholder="Nom de l'agent" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            </div>

                            <div class="mt-3">
                                <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Matricule *
                                </label>
                                <input id="vertical-form-2" v-model="form.matricule" type="text" placeholder="Matricule" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            </div>

                            <div class="mt-3">
                                <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Mot de passe *
                                </label>
                                <input id="vertical-form-2" v-model="form.password" type="text" placeholder="Mot de passe." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                            Rôle *
                        </label>
                        <select v-model="form.role" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            <option value="" selected hidden>--Sélectionnez un rôle--</option>
                            <option value="guard">Agent de sécurité</option>
                            <option value="supervisor">Superviseur</option>
                        </select>
                    </div>
                    <div class="col-span-12" v-if="form.role !=='supervisor' && form.role !==''">
                        <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                            Site affecté *
                        </label>
                        <select v-model="form.site_id" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            <option value="" selected hidden>--Sélectionnez un site--</option>
                            @foreach ($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-12">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="mt-3 col-span-12 lg:col-span-8">
                                <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Groupe *
                                </label>
                                <select v-model="form.groupe_id" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    <option value="" selected hidden>--Sélectionnez un groupe--</option>
                                    <option v-for="(data, index) in allGroups" :key="index" :value="data.id">
                                        @{{ data.libelle }}
                                    </option>
                                </select>
                            </div>
                            <div class="mt-3 col-span-12 lg:col-span-4">
                                <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Status *
                                </label>
                                <select v-model="form.status" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                    <option value="permenant" selected>Permenant</option>
                                    <option value="dispo">Dispo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400">
                    <button
                        data-tw-merge
                        data-tw-dismiss="modal" type="button" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-1 w-20 mr-1 w-20">Fermer</button>
                    <button
                        data-tw-merge
                        type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary w-auto" :disabled="isLoading">Soumettre la modification
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
            </form>
        </div>
        <!-- END: Modal Content -->
       


    </div>

    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/agent_manager.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush