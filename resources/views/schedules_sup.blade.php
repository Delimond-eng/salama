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
                    <a href="#">Planning de superviseurs</a>
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
            <div class="flex w-full sm:w-auto">
                <div class="relative w-48 text-slate-500">
                    <input  v-model="search" @input="searchSite=''" type="text" placeholder="Recherche par nom..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-48 pr-10">
                    <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
                <select v-model="searchStatus" @change="search=''" class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box ml-2 w-48 xl:w-auto">
                    <option selected hidden value="">Par status</option>
                    <option value="">Tout</option>
                    <option value="En attente">En attente</option>
                    <option value="Effectuée">Effectuée</option>
                    <option value="Partielle">Partielle</option>
                    <option value="Non effectuée">Non effectuée</option>
                </select>   
            </div>
            <div class="mx-auto hidden text-slate-500 xl:block">
                <div
                    data-tw-backdrop=""
                    aria-hidden="true"
                    tabindex="-1"
                    id="modal-add-schedule" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
                    <form method="POST" @submit.prevent="createSupervisorSchedule"
                        data-tw-merge
                        class="form-agent w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px] lg:w-[900px]">
                        <div
                            class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="mr-auto text-base font-medium uppercase">
                                Création planning superviseur
                            </h2>
                            <button id="btn-reset"
                                data-tw-merge
                                type="button"
                                data-tw-dismiss="modal"
                                @click.prevent="reset"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                                    data-tw-merge
                                    data-lucide="x"
                                    class="stroke-1.5 h-4 w-4"></i>
                            </button>
                        </div>
                        <div
                            data-tw-merge
                            class="p-5 grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <div v-if="error" role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                                    @{{ error }}
                                    <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i data-tw-merge data-lucide="x" class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <div>
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Libellé *
                                    </label>
                                    <input id="vertical-form-1" type="text" v-model="formSup.title" placeholder="Nom de l'agent" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10" required>
                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-8">
                                <div>
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Superviseur concerné *
                                    </label>
                                    <select data-placeholder="Sélectionnez un superviseur" class="tom-select w-full" required>
                                        <option value="">Sélectionnez un superviseur</option> <!-- Placeholder visible -->
                                        @foreach ($supervisors as $sup )
                                        <option value="{{ $sup->id }}">{{ $sup->matricule  }} | {{ $sup->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-4">
                                <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                    Date *
                                </label>
                                <input id="vertical-form-1" type="date" v-model="formSup.date" placeholder="Libellé." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                            </div>

                            <div class="col-span-12">
                                <div class="grid grid-cols-12 mb-4" v-for="(input, index) in formSup.sites" :key="index">
                                    <div class="col-span-12">
                                        <label for="vertical-form-2" class="inline-block text-blue-500 font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                            Sites à superviser *
                                        </label>
                                        <div class="flex gap-2">
                                            <select @change="onChangeSite($event, index)" v-model="input.site_id" class="mr-1 disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                <option value="" selected hidden>--Sélectionnez un site--</option>
                                                <option v-for="(data, index) in allSites" :value="data.id">@{{ data.name }}</option>
                                            </select>

                                            <button v-if="index===0" type="button" @click.prevent="addSupField"
                                                data-tw-merge
                                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                                                    data-tw-merge
                                                    data-lucide="plus"
                                                    class="stroke-1.5 text-blue-5000 h-4 w-4"></i>
                                            </button>
                                            <button v-else type="button" @click.prevent="deleteSupField(input)"
                                                data-tw-merge
                                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                                                    data-tw-merge
                                                    data-lucide="x"
                                                    class="stroke-1.5 text-danger h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400">
                            <button @click.prevent="reset"
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
            </div>
            <div class="mt-3 flex w-full flex-wrap items-center gap-y-3 xl:mt-0 xl:w-auto xl:flex-nowrap">
                <button data-tw-merge="" data-tw-toggle="modal" data-tw-target="#modal-add-schedule" class="transition duration-200 border inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary mr-2 shadow-md">
                    <i data-tw-merge="" data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i> Nouveau planning
                </button>
                <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative"><button data-tw-merge="" data-tw-toggle="dropdown" aria-expanded="false" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed !box px-2"><span class="flex h-5 w-5 items-center justify-center">
                            <i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 h-4 w-4"></i>
                        </span></button>
                    <div data-transition="" data-selector=".show" data-enter="transition-all ease-linear duration-150" data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1" data-enter-to="!mt-1 visible opacity-100 translate-y-0" data-leave="transition-all ease-linear duration-150" data-leave-from="!mt-1 visible opacity-100 translate-y-0" data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1" class="dropdown-menu absolute z-[9999] hidden">
                        <div data-tw-merge="" class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 w-40">
                            <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Excel Export</a>
                            <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                PDF Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 mt-2 flex justify-center flex-wrap items-center xl:flex-nowrap" v-if="allSchedules.length === 0">
            <div v-if="isDataLoading">
                <x-dom-loader></x-dom-loader>
            </div>
            <div v-else class="relative w-full mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="box intro-y">
                    <x-empty-state message="Aucun planning de superviseurs répertorié !"></x-empty-state>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div v-else class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
            <table data-tw-merge="" class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                <thead data-tw-merge="" class="">
                    <tr data-tw-merge="" class="">
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            DATE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            LIBELLE
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                            SUPERVISEUR
                        </th>
                        <th data-tw-merge="" class="font-medium uppercase px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                            SITES SUPERVISés
                        </th>
                        <th data-tw-merge="" class="font-medium uppercase px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                            Créer par
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                            STATUS
                        </th>
                        <th data-tw-merge="" class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-tw-merge="" class="intro-x" v-for="(data, index) in allSchedules">
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 !py-4 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                    @{{ data.date }}
                                </a>
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <a class="flex items-center underline decoration-dotted" href="#">
                                @{{ data.title }}
                            </a>
                        </td>

                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <a v-if="data.agent" class="whitespace-nowrap font-medium" href="#">
                                @{{ data.agent.fullname }}
                            </a>
                            <div v-if="data.agent" class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                @{{ data.agent.matricule }}
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box whitespace-nowrap rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="text-xs text-slate-500">(@{{ data.presences.length }}/@{{ data.sites.length }})</div>
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-2 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex items-center justify-center text-slate-800" v-if="data.user">
                                <i data-tw-merge="" data-lucide="user" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                @{{ data.user.name }}
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <div class="flex items-center justify-center font-medium" :class="{'text-blue-500': status(data) === 'En attente', 'text-pending': status(data)==='Partielle', 'text-danger': status(data)==='Non effectuée', 'text-success': status(data)==='Effectuée'}">
                                <i data-tw-merge="" data-lucide="check-square" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                @{{ status(data) }}
                            </div>
                        </td>
                        <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                            <div class="flex items-center justify-center">
                                <a @click="selectSupSchedule(data)" class="flex items-center whitespace-nowrap text-blue-500 mr-2" href="#">
                                    <i data-tw-merge="" data-tw-toggle="modal" data-tw-target="#schedule-details-modal" data-lucide="eye" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                </a>

                                <a data-tw-toggle="modal" data-tw-target="#modal-add-schedule" @click="editSupSchedule(data)" class="flex items-center whitespace-nowrap text-primary mr-2" href="#">
                                    <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                </a>

                                <a @click.prevent="deleteSupPlanning(data)" class="flex items-center whitespace-nowrap text-danger" href="#">
                                    <span class="h-3 w-3" v-if="data.id === delete_id">
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
                                    <i v-else data-tw-merge="" data-lucide="trash-2" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">La création du planning de patrouille effectuée ! </div>
            </div>
        </div>

       
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <Pagination
            :current-page="pagination.current_page"
            :last-page="pagination.last_page"
            :total-items="pagination.total"
            :per-page="pagination.per_page"
            @page-changed="changePage"
            @per-page-changed="onPerPageChange"></Pagination>
        <!-- END: Pagination -->


         <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="schedule-details-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div data-tw-merge="" class="w-[90%] ml-auto h-screen flex flex-col bg-slate-100 relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 dark:bg-darkmode-600 sm:w-[460px]"><a class="absolute inset-y-0 left-0 right-auto my-auto -ml-[60px] flex h-8 w-8 items-center justify-center rounded-full border border-white/90 bg-white/5 text-white/90 transition-all hover:rotate-180 hover:scale-105 hover:bg-white/10 focus:outline-none sm:-ml-[105px] sm:h-14 sm:w-14" data-tw-dismiss="modal" href="javascript:;">
                    <i data-tw-merge="" data-lucide="x" class="h-3 w-3 stroke-[1] sm:h-8 sm:w-8"></i>
                </a>
                <div data-tw-merge="" class="overflow-y-auto flex-1 p-0" v-if="selectedSchedule">
                    <div class="flex items-center justify-between border-b">
                        <div class="px-8 pt-6 pb-8">
                            <div class="text-base font-bold uppercase">Détails de la supervision</div>
                            <!-- <div class="mt-0.5 text-slate-500 flex items-center border-b border-slate-200/60 pb-4" v-if="selectedPatrol">
                                <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-primary"></i>
                                <span v-if="selectedPatrol.site">@{{ selectedPatrol.site.name }}</span>
                            </div> -->
                        </div>
                        <button v-if="selectedSchedule.presences.length" class="transition duration-200 shadow-sm inline-flex items-center py-2 mr-5 px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-primary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 relative justify-start rounded-full bg-primary text-white"><span class="mr-6">Tirer le rapport</span>
                            <span class="absolute bottom-0 right-0 top-0 my-auto ml-auto mr-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="arrow-right" class="lucide lucide-arrow-right stroke-1.5 h-4 w-4">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </span>
                            <div></div>
                        </button>
                    </div>
                    <div class="mt-2 p-5">
                        <div class="box rounded-md p-5">
                            <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                                <div class="truncate text-base font-medium">
                                    Infos du planning
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold flex items-center uppercase"> <i class="w-3 h-3 mr-1" data-lucide="clipboard"></i>Titre</span>
                                <a class="ml-1 underline decoration-dotted" href="#">
                                    @{{ selectedSchedule.title }}
                                </a>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="font-bold flex items-center uppercase"> <i class="w-3 h-3 mr-1" data-lucide="calendar"></i> Date </span>

                                <a class="ml-1 underline decoration-dotted" href="#">
                                    @{{ selectedSchedule.date }}
                                </a>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="font-bold flex items-center uppercase"> <i class="w-3 h-3 mr-1" data-lucide="user"></i>Agent</span>

                                <a class="ml-1 underline decoration-dotted" href="#">
                                    @{{ selectedSchedule.agent.matricule }} | @{{ selectedSchedule.agent.fullname }}
                                </a>
                            </div>
                        </div>
                        <div class="box p-5 mt-5">
                            <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                                <div class="truncate text-base font-medium">
                                    Liste des sites à superviser
                                </div>
                            </div>
                            <div v-for="(data, i) in selectedSchedule.sites" class="flex items-center border-b border-slate-300 border-dashed  bg-white p-3 transition duration-300 ease-in-out hover:bg-slate-100 dark:bg-darkmode-600 dark:hover:bg-darkmode-400">
                                <div class="mr-1 max-w-[50%] truncate">
                                    @{{ data.site.code }} | @{{ data.site.name }}
                                </div>
                            </div>
                        </div>
                        <div class="box p-5 mt-5" v-if="selectedSchedule.presences.length">
                            <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                                <div class="truncate text-base font-medium">
                                    Rapport de supervision
                                </div>
                            </div>
                            <div v-for="(data, i) in selectedSchedule.presences" class="flex items-center border-b pb-2 border-dashed rounded-md bg-white transition duration-300 ease-in-out dark:bg-darkmode-600 dark:hover:bg-darkmode-400">
                                <div class="w-full flex-1 rounded-md border-2 border-dashed p-2 dark:border-darkmode-400">
                                    <div class="grid grid-cols-12 gap-5">
                                        <div class="col-span-6 lg:col-span-4 flex items-center">
                                            <div class="mx-2 h-12 w-px border border-r border-dashed border-primary dark:border-darkmode-300">
                                            </div>
                                            <div>
                                                <div class="font-medium text-primary">
                                                    SITE
                                                </div>
                                                <div class="mt-0.5 text-slate-500 text-xs">@{{ data.site.code }} | @{{ data.site.name }}</div>
                                                <small class="mt-1 text-xs flex items-center" :class="data.distance > 300 ? 'text-pending' : 'text-success'"> <i class="w-3 h-3 mr-2" data-lucide="map-pin"></i> @{{ data.distance <= 300 ? 'Dans le site' : 'Hors site' }}</small>
                                            </div>
                                        </div>
                                        <div class="image-fit zoom-in relative col-span-6 lg:col-span-4 h-28 cursor-pointer"  @click="viewPhoto(data.end_photo)">
                                            <img class="rounded-md w-20 h-20" :src="data.start_photo ?? 'assets/images/loading.gif'" alt="In">
                                            <span data-placement="top" class="tooltip text-xs absolute right-0 top-0 -mr-2 -mt-2 flex h-6 w-6 items-center justify-center rounded-full bg-primary text-white">
                                                In
                                            </span>
                                        </div>
                                        <div class="image-fit zoom-in relative col-span-6 lg:col-span-4 h-28 cursor-pointer" @click="viewPhoto(data.end_photo)">
                                            <img class="rounded-md w-20 h-20" :src="data.end_photo ?? 'assets/images/loading.gif'" alt="Out">
                                            <span data-placement="top" class="tooltip absolute right-0 top-0 -mr-2 -mt-2 flex h-6 w-6 items-center justify-center rounded-full text-xs bg-danger text-white">
                                                Out
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex relative mt-2 px-4 w-full justify-between">
                                        <div>
                                            <div class="font-medium text-primary dark:text-slate-300">
                                                Date & Heure debut
                                            </div>
                                            <div class="mt-0.5 text-slate-500">@{{ data.date }} @{{ data.started_at }}</div>
                                        </div>
                                        <div class="mx-2 h-12 w-px border border-r border-dashed border-slate-200 dark:border-darkmode-300">
                                        </div>
                                        <div>
                                            <div class="font-medium text-primary">
                                                Date & Heure Fin
                                            </div>
                                            <div class="mt-0.5 text-slate-500">@{{ data.date }} @{{ data.ended_at }}</div>
                                        </div>
                                        <div class="mx-2 h-12 w-px border border-r border-dashed border-slate-200 dark:border-darkmode-300">
                                        </div>
                                        <div>
                                            <div class="font-medium text-primary">
                                                Durée
                                            </div>
                                            <div class="mt-0.5 text-slate-500">@{{ data.duree }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <x-empty-state message="Aucune supervision répertoriée !"></x-empty-state>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dom-loader></x-dom-loader>

</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/planning.js") }}"></script>
@endpush