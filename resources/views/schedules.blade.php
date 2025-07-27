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
                    <a href="#">Planning de patrouille</a>
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
        <div class="col-span-12 2xl:col-span-8 lg:col-span-8">
            <div>
                <div class="box intro-y p-5">
                    <div class="flex w-full justify-between items-center dark:border-darkmode-400">
                        <div class="flex flex-wrap mx-4 gap-x-5">
                            <div class="flex items-center text-success border-b border-dashed">
                                <div class="mr-1 h-2 w-2 rounded-full bg-success">
                                </div>
                                Effectuée
                            </div>
                            <div class="flex items-center text-primary border-b border-dashed">
                                <div class="mr-1 h-2 w-2 rounded-full bg-primary">
                                </div>
                                Effectuée partiellement
                            </div>
                            <div class="flex items-center text-danger border-b border-dashed">
                                <div class="mr-1 h-2 w-2 rounded-full bg-danger">
                                </div>
                                Non Effectuée
                            </div>
                            <div class="flex items-center text-warning border-b border-dashed">
                                <div class="mr-1 h-2 w-2 rounded-full bg-warning">
                                </div>
                                Effectuée en avance
                            </div>
                            <div class="flex items-center text-pending border-b border-dashed">
                                <div class="mr-1 h-2 w-2 rounded-full bg-pending">
                                </div>
                                En attente
                            </div>
                        </div>
                        <div class="relative mt-3 w-full sm:mt-0 sm:w-auto flex items-center">
                            <input type="date" v-model="filter_date" @input="viewAllSchedules" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box sm:w-64">
                            <a href="#" class="ml-5 flex h-5 w-5 items-center justify-center" @click="filter_date=''; viewAllSchedules()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="refresh-cw" class="lucide lucide-refresh-cw stroke-1.5 h-4 w-4">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                    <path d="M21 3v5h-5"></path>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                    <path d="M8 16H3v5"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div data-tw-merge="" id="calendar-events" class="full-calendar-draggable mb-5 mt-6 border-t border-slate-200/60 py-3 dark:border-darkmode-400">
                        <div v-if="allSchedules.length === 0">
                            <div v-if="isDataLoading">
                                <x-dom-loader></x-dom-loader>
                            </div>
                            <div v-else>
                                <x-empty-state message="Aucun planning disponible." v-else></x-empty-state>
                            </div>
                        </div>

                        <div v-else class="flex justify-between border-b" v-for="(data, i) in allSchedules" :key="i">
                            <div class="event flex cursor-pointer items-center rounded-md p-3 transition duration-300 ease-in-out">
                                <div class="mr-3 h-2 w-2 rounded-full" :class="
                                    {
                                    'bg-success': data.status === 'success',
                                    'bg-danger': data.status === 'fail',
                                    'bg-primary': data.status === 'partial',
                                    'bg-warning': data.status === 'early',
                                    'bg-pending': data.status === 'actif'
                                }">
                                </div>
                                <div class="pr-10">
                                    <div class="font-extrabold mr-5 flex items-center justify-between w-full">@{{ data.libelle }}
                                        <div class="flex py-1 text-blue-500 ml-12 uppercase">
                                        @{{ data.site.name }}
                                        </div>
                                    </div>

                                    <div class="mt-2 text-xs text-slate-500">
                                        <span class="event__days">@{{ data.date }}</span>
                                        <span class="mx-1">•</span> @{{ data.start_time }} à @{{ data.end_time }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="flex items-center font-medium">
                                    <button data-tw-toggle="modal" data-tw-target="#planning-info-details" @click="selectedPlanning = data" class="text-primary border border-primary ml-3 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </button>

                                    <button @click="deletePlanning(data)" class="text-danger border border-slate-400 ml-1 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                        <span class="h-3 w-3" v-if="data.id === delete_id">
                                            <svg class="h-3 w-3" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="red">
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

                                    <button v-if="data.status==='actif'" @click="form.id = data.id; form.libelle=data.libelle; form.start_time = data.start_time; form.end_time=data.end_time; form.site_id= data.site_id" style="border-color: #5190f6;" class="text-blue-500 border ml-1 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                        <i data-lucide="edit" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-tw-merge="" class="flex items-center">
                        <!-- BEGIN: Pagination -->
                        <Pagination
                            :current-page="pagination.current_page"
                            :last-page="pagination.last_page"
                            :total-items="pagination.total"
                            :per-page="pagination.per_page"
                            @page-changed="changePage"
                            @per-page-changed="onPerPageChange">
                        </Pagination>
                        <!-- END: Pagination -->
                    </div>
                </div>
            </div>

            <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="planning-info-details" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
                <div data-tw-merge="" class="w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 dark:bg-darkmode-600 sm:w-[460px]"><a class="absolute inset-y-0 left-0 right-auto my-auto -ml-[60px] flex h-8 w-8 items-center justify-center rounded-full border border-white/90 bg-white/5 text-white/90 transition-all hover:rotate-180 hover:scale-105 hover:bg-white/10 focus:outline-none sm:-ml-[105px] sm:h-14 sm:w-14" data-tw-dismiss="modal" href="javascript:;">
                        <i data-tw-merge="" data-lucide="x" class="h-3 w-3 stroke-[1] sm:h-8 sm:w-8"></i>
                    </a>
                    <div data-tw-merge="" class="overflow-y-auto flex-1 p-0" v-if="selectedPlanning">
                        <div class="flex flex-col">
                            <div class="px-8 pt-6 pb-8">
                                <div class="text-base font-bold">Planning Infos</div>
                                <div class="mt-0.5 text-slate-500 flex items-center border-b border-slate-200/60 pb-4" v-if="selectedPlanning">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-1 text-blue-500"></i>
                                    <span v-if="selectedPlanning.site">@{{ selectedPlanning.site.name }}</span>
                                </div>

                                <div class="mt-5 mb-10 grid grid-cols-12 gap-2" v-if="selectedPlanning">
                                    <div class="col-span-12 2xl:col-span-8 lg:col-span-8">
                                        <div class="border-l-2 border-slate-400 pl-4 dark:border-primary">
                                            <a class="font-medium" href="#">
                                                Date & heure planning
                                            </a>
                                            <div class="text-slate-500"> @{{ selectedPlanning.date }} @{{ selectedPlanning.start_time }} - @{{ selectedPlanning.end_time }}</div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                                        <div class="border-l-2 pl-4 dark:border-primary" :class="{'border-primary': selectedPlanning.status==='actif', 'border-pending':selectedPlanning.status==='fail', 'border-success':selectedPlanning.status==='success', 'border-warning':selectedPlanning.status==='partial', 'border-pending':selectedPlanning.status==='early'}">
                                            <a class="font-medium" href="#">
                                                Status
                                            </a>
                                            <div :class="{'text-primary': selectedPlanning.status==='actif', 'text-danger':selectedPlanning.status==='fail', 'text-success':selectedPlanning.status==='success', 'text-pending':selectedPlanning.status==='partial', 'text-pending':selectedPlanning.status==='early'}">@{{ selectedPlanning.status==='fail' ? 'Non respecté' : selectedPlanning.status==='actif' ? 'Actif' : selectedPlanning.status==='early' ? "Effectuée avant"  : "Respecté" }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="selectedPlanning.patrol" class="text-center px-2 font-bold bg-slate-100 uppercase mt-5 py-2 border-t border-b border-dashed lg:mt-3 lg:text-left">
                                    Patrouille effectué infos
                                </div>

                                <div class="mt-5 mb-10 grid grid-cols-12 gap-2" v-if="selectedPlanning.patrol">
                                    <div class="col-span-12 2xl:col-span-8 lg:col-span-8">
                                        <div class="border-l-2 border-slate-400 pl-4 dark:border-primary border-dashed">
                                            <a class="font-medium" href="#">
                                                Date & heure patrouille
                                            </a>
                                            <div class="text-slate-400"> @{{ selectedPlanning.patrol.started_at }}</div>
                                            <div class="text-slate-600"> @{{ selectedPlanning.patrol.ended_at }}</div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                                        <div class="border-l-2 pl-4 dark:border-primary border-slate-400 border-dashed">
                                            <a class="font-medium" href="#">
                                                Status
                                            </a>
                                            <div class="text-blue-500">
                                                @{{ selectedPlanning.patrol.status }}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-span-12 md:col-span-12 2xl:col-span-12" v-if="selectedPlanning.patrol">
                                        <div v-if="selectedPlanning.patrol.scans" class="relative mt-5 before:absolute before:ml-5 before:mt-5 before:block before:h-[85%] before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                            <div class="intro-x relative mb-2 flex items-center" v-for="(data, i) in selectedPlanning.patrol.scans">
                                                <div class="before:absolute before:ml-5 before:mt-5 before:block before:h-px before:w-20 before:bg-slate-200 before:dark:bg-darkmode-400">
                                                    <div class="image-fit h-10 w-15">
                                                        <span class="text-white box border-0 flex items-center bg-[#4ab3f4] py-1 px-2"><i class="h-3 w-3 mr-1" data-lucide="clock"></i>@{{ data.time }}</span>
                                                    </div>
                                                </div>
                                                <div class="box ml-4 flex-1 px-3 py-2">
                                                    <div class="flex justify-between">
                                                        <div class="font-bold uppercase">
                                                            @{{ data.area.libelle }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-else>
                                    <x-empty-state message="Pas de patrouille effectuée !"></x-empty-state>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y col-span-12 2xl:col-span-4 lg:col-span-4">
            <div class="grid grid-cols-1">
                <div class="col-span-12">
                    <!-- BEGIN: Vertical Form -->
                    <div class="intro-x box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="mr-auto text-base font-medium">
                                Création planning de patrouille
                            </h2>
                        </div>
                        <div class="p-5">
                            <div v-if="error" role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                                <strong class="mr-2">Erreur : </strong> @{{ error }}
                                <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i data-tw-merge data-lucide="x" class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                            </div>
                            <form class="preview form-planning relative [&.hide]:overflow-hidden [&.hide]:h-0" method="POST" @submit.prevent="createSchedules">
                                <div>
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Libellé
                                    </label>
                                    <input id="vertical-form-1" v-model="form.libelle" type="text" placeholder="Libellé." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                </div>
                                <div class="mt-3">
                                    <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Date
                                    </label>
                                    <input id="vertical-form-1" v-model="form.date" type="date" placeholder="Libellé." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                </div>

                                <div class="grid grid-cols-12 gap-2 mt-3">
                                    <div class="col-span-6 2xl:col-span-6">
                                        <div>
                                            <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                Heure début
                                            </label>
                                            <input id="vertical-form-1" v-model="form.start_time" type="time" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                        </div>
                                    </div>
                                    <div class="col-span-6 2xl:col-span-6">
                                        <div class="mb-3">
                                            <label for="vertical-form-1" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                Heure Fin
                                            </label>
                                            <div class="flex gap-2">
                                                <input id="vertical-form-1" v-model="form.end_time" type="time" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2" v-for="(input, index) in form.sites">
                                    <label for="vertical-form-2" class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                        Site ciblé @{{ index + 1 }}
                                    </label>
                                    <div class="flex">
                                        <select
                                        class="tom-select w-full mr-1"
                                        ref="siteSelect"
                                        :data-index="index"
                                        ></select>
                                        <button @click="addSite" v-if="index===0" type="button" 
                                            data-tw-merge
                                            class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                                                data-tw-merge
                                                data-lucide="plus"
                                                class="stroke-1.5 text-blue-500 h-3 w-3"></i>
                                        </button>
                                        <button v-else type="button"  @click="removeSite(index)"
                                            class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                                                data-lucide="x"
                                                class="stroke-1.5 text-danger h-3 w-3"></i>
                                        </button>
                                    </div>
                                    <!-- <select v-model="form.site_id" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                        <option value="" selected hidden>--Sélectionnez un site--</option>
                                        <option v-for="(data, index) in allSites" :value="data.id">@{{ data.name }}</option>
                                    </select> -->
                                </div>

                                <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary mt-5">Enregister
                                    <span class="ml-2 h-3 w-3" v-if="isLoading">
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
                    <!-- END: Vertical Form -->
                </div>
            </div>
        </div>



        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">La création du planning de patrouille effectuée ! </div>
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
