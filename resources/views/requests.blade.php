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
                    <a href="#">Alertes panique</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="mt-8 grid grid-cols-12 gap-6" id="App" v-cloak>
        <div class="col-span-12 lg:col-span-12 2xl:col-span-12">
            <!-- BEGIN: Inbox Filter -->
            <div class="intro-y flex flex-col-reverse justify-between items-center sm:flex-row">
                <div class="flex gap-2">
                    
                    <div class="relative mr-auto mt-3 w-full sm:mt-0 sm:w-auto">
                        <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 text-slate-500"></i>
                        <input data-tw-merge="" type="text" placeholder="Recherche par agent" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box px-10 sm:w-64">
                    </div>

                    <div class="relative mr-auto mt-3 w-full sm:mt-0 sm:w-auto">
                        <input data-tw-merge="" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box sm:w-64">
                    </div>
                </div>
                <div class="flex w-full sm:w-auto">
                    <button data-tw-merge=""
                        class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed !box flex text-slate-600 dark:text-slate-300"><i
                            data-tw-merge="" data-lucide="file-text"
                            class="stroke-1.5 mr-2 hidden h-4 w-4 sm:block"></i>
                        Export to Excel</button>
                    <button data-tw-merge=""
                        class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed !box ml-3 flex text-slate-600 dark:text-slate-300"><i
                            data-tw-merge="" data-lucide="file-text"
                            class="stroke-1.5 mr-2 hidden h-4 w-4 sm:block"></i>
                        Export to PDF</button>
                </div>
            </div>
            <!-- END: Inbox Filter -->
            <!-- BEGIN: Inbox Content -->
            <div class="intro-y box mt-5" v-if="allRequests.length > 0">
                <div class="flex flex-col-reverse border-b border-slate-200/60 p-5 text-slate-500 sm:flex-row" >
                    <div class="-mx-5 mt-3 flex items-center border-t border-slate-200/60 px-5 pt-5 sm:mx-0 sm:mt-0 sm:border-0 sm:px-0 sm:pt-0">
                        <div data-tw-merge="" data-tw-placement="bottom-start" class="dropdown relative ml-1"><button data-tw-toggle="dropdown" aria-expanded="false" class="cursor-pointer block h-5 w-5"><i data-tw-merge="" data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                            </button>
                            <div data-transition="" data-selector=".show" data-enter="transition-all ease-linear duration-150" data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1" data-enter-to="!mt-1 visible opacity-100 translate-y-0" data-leave="transition-all ease-linear duration-150" data-leave-from="!mt-1 visible opacity-100 translate-y-0" data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1" class="dropdown-menu absolute z-[9999] hidden">
                                <div data-tw-merge="" class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 w-32 text-slate-800 dark:text-slate-300">
                                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">Tout</a>
                                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">Lu</a>
                                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">Non lu</a>
                                </div>
                            </div>
                        </div>
                        <a class="ml-5 flex h-5 w-5 items-center justify-center" href="#">
                            <i data-tw-merge="" data-lucide="refresh-cw" class="stroke-1.5 h-4 w-4"></i>
                        </a>
                    </div>
                    <div class="flex items-center sm:ml-auto">
                        <div class="">1 - 50 of 5,238</div>
                        <a class="ml-5 flex h-5 w-5 items-center justify-center" href="#">
                            <i data-tw-merge="" data-lucide="chevron-left" class="stroke-1.5 h-4 w-4"></i>
                        </a>
                        <a class="ml-5 flex h-5 w-5 items-center justify-center" href="#">
                            <i data-tw-merge="" data-lucide="chevron-right" class="stroke-1.5 h-4 w-4"></i>
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto sm:overflow-x-visible">
                    <template v-for="(data, i) in allRequests">
                        <div class="intro-y"  data-tw-toggle="modal" data-tw-target="#request-modal" @click="selectedRequest = data">
                            <div class="transition duration-200 ease-in-out transform cursor-pointer inline-block sm:block border-b border-slate-200/60 dark:border-darkmode-400 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border-0 hover:rounded bg-white text-slate-800 dark:text-slate-300 dark:bg-darkmode-600">
                                <div class="flex px-5 py-3">
                                    <div class="mr-5 flex w-72 flex-none items-center">
                                        <div class="image-fit relative ml-5 h-6 w-6 flex-none">
                                            <img class="rounded-full" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar">
                                        </div>
                                        <div class="ml-3 truncate">
                                            <span class="font-medium" v-if="data.agent">@{{ data.agent.fullname }}</span> <br>
                                            <span class="text-xs" v-if="data.agent">@{{data.agent.matricule}}</span>
                                        </div>
                                    </div>
                                    <div class="w-64 truncate sm:w-auto">
                                        <span class="mr-3 truncate font-medium text-blue-500">
                                            @{{ data.object }}.
                                        </span>
                                        @{{ data.description }}
                                    </div>
                                    <div class="pl-10 ml-auto whitespace-nowrap font-medium">
                                        @{{ data.created_at }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <!-- END: Inbox Content -->
            <div v-else>
                <div v-if="isDataLoading">
                    <x-dom-loader></x-dom-loader>
                </div>
                <div v-else class="relative mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                    <div class="box">
                        <x-empty-state message="Aucune requête disponible pour l'instant."></x-empty-state>
                    </div>
                </div>
            </div>
        </div>

        <div
            data-tw-backdrop=""
            aria-hidden="true"
            tabindex="-1"
            id="request-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div 
                data-tw-merge id="form-site"
                class="w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px]">
                <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-extrabold uppercase">
                        Requête détail
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
                <div class="bg-slate-50 rounded-md" v-if="selectedRequest">
                    <div class="flex items-start px-5 pt-5">
                        <div class="flex w-full flex-col items-center lg:flex-row">
                            <div class="image-fit h-10 w-10">
                                <img class="rounded-full" src="{{ asset("assets/images/profil-2.png") }}" alt="avatar">
                            </div>
                            <div class="mt-3 text-center lg:ml-4 lg:mt-0 lg:text-left">
                                <a class="font-medium" href="#" v-if="selectedRequest.agent">
                                    @{{ selectedRequest.agent.fullname }}
                                </a>
                                <div v-if="selectedRequest.agent" class="mt-0.5 text-xs text-slate-500">
                                    @{{ selectedRequest.agent.matricule }}
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="p-5 text-center lg:text-left">
                        <div class="mb-2 font-bold">@{{  selectedRequest.object }}.</div>
                        <div>@{{ selectedRequest.description }}</div>
                    </div>
                    <div class="p-5 text-right font-medium text-blue-500">
                        @{{ selectedRequest.created_at }}
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
<script type="module" src="{{ asset("assets/js/scripts/requests.js") }}"></script>
@endpush