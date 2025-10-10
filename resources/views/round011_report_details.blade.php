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
                    <a href="#">Détails de la ronde sélectionnée</a>
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
            <h2 class="mr-auto text-lg font-medium">Details de la ronde & supervision</h2>
            <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
                <button data-tw-merge="" onclick="history.back()" class="transition duration-200 border inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary mr-2 shadow-md">Retour</button>
            </div>
        </div>
        <!-- BEGIN: Transaction Details -->
        <div class="intro-y mt-5 grid grid-cols-11 gap-5">
            <div class="lg:col-span-6 2xl:col-span-6">
                <div class="grid grid-cols-11 gap-5">
                    <div class="col-span-12 ">
                        <div class="intro-y box p-5">
                            <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                                <div class="truncate text-base uppercase font-medium">
                                    Détails de la supervision
                                </div>
                            </div>
                            <div class="mb-2 border-b border-slate-200/60 pb-3 dark:border-darkmode-400">
                                <div class="w-100 text-lg font-medium sm:whitespace-normal">
                                    @{{ roundDetails.supervisor.fullname }}
                                </div>
                                <div class="text-slate-500">Superviseur</div>
                            </div>
                            <div
                                class="-mx-5 flex flex-col border-b border-slate-200/60 pb-5 dark:border-darkmode-400 lg:flex-row">
                                <div class="flex flex-1 items-center justify-center px-5 lg:justify-start">
                                    <div class="image-fit relative h-20 w-20 flex-none sm:h-24 sm:w-24 lg:h-32 lg:w-32 mr-2">
                                        <div style="position: absolute; top:0; z-index:999" class="cursor-pointer rounded-2 bg-success px-2 py-1 text-xs font-medium text-white">
                                            Photo debut
                                        </div>
                                        <img  data-action="zoom" data-placement="top" class="rounded-full border-2 border-white" :src="roundDetails.photo_debut"
                                            alt="photo début">
                                    </div>
                                    <div class="image-fit relative h-20 w-20 flex-none sm:h-24 sm:w-24 lg:h-32 lg:w-32">
                                        <div style="position: absolute; top:0; z-index:999" class="cursor-pointer rounded-2 bg-dark px-2 py-1 text-xs font-medium text-white">
                                            Photo fin
                                        </div>
                                        <img  data-action="zoom" data-placement="top" class="rounded-full border-2 border-white" :src="roundDetails.photo_fin ?? 'assets/images/profil-2.png'"
                                            alt="photo fin">
                                    </div>
                                    
                                </div>
                                <div
                                    class="mt-6 flex-1 border-l border-r border-t border-slate-200/60 px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-t-0 lg:pt-0">
                                    <div class="text-center font-medium lg:mt-3 lg:text-left">
                                        Supervision infos.
                                    </div>
                                    <div class="mt-4 flex flex-col items-center justify-center lg:items-start">
                                        <div class="flex items-center truncate sm:whitespace-normal">
                                            <i data-tw-merge="" data-lucide="home" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Station : @{{ roundDetails.site.name }}
                                        </div>
                                        <div class="mt-3 flex items-center truncate sm:whitespace-normal">
                                            <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Début : @{{ roundDetails.started_at }}
                                        </div>
                                        <div class="mt-3 flex items-center truncate sm:whitespace-normal">
                                            <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Fin : @{{ roundDetails.ended_at }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-5 2xl:col-span-5">
                <div class="box rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base uppercase font-medium">
                            AGENTS présents supervisés
                        </div>
                    </div>
                    <div class="-mt-3 overflow-auto lg:overflow-visible">
                        <table data-tw-merge="" class="w-full text-left">
                            <thead data-tw-merge="" class="">
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap !py-5">
                                        AGENT
                                    </th>
        
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        ACTIONS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in roundDetails.agents" data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-action="zoom" data-placement="top" title="photo agent" :src="data.photo ?? 'assets/images/profil-2.png'" alt="photo agent" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
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
                                       <button class="text-blue-500 underline hover:text-blue-800 tooltip"   data-tw-toggle="modal" data-tw-target="#round-detail-modal" @click="selectedAgent = data">
                                            Voir notes
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Transaction Details -->

        <div data-tw-backdrop="" aria-hidden="true" tabindex="-1" id="round-detail-modal" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <div data-tw-merge="" class="w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 dark:bg-darkmode-600 sm:w-[460px]"><a class="absolute inset-y-0 left-0 right-auto my-auto -ml-[60px] flex h-8 w-8 items-center justify-center rounded-full border border-white/90 bg-white/5 text-white/90 transition-all hover:rotate-180 hover:scale-105 hover:bg-white/10 focus:outline-none sm:-ml-[105px] sm:h-14 sm:w-14" data-tw-dismiss="modal" href="javascript:;">
                    <i data-tw-merge="" data-lucide="x" class="h-3 w-3 stroke-[1] sm:h-8 sm:w-8"></i>
                </a>
                <div data-tw-merge="" class="overflow-y-auto flex-1 p-0">
                    <div class="flex flex-col">
                        <div class="px-8 pt-6 pb-8">
                            <div class="text-base font-extrabold">Elements de supervision prélèvés </div>
                            <div class="mt-0.5 font-medium text-slate-500 flex items-center border-b border-slate-200/60 pb-4" v-if="selectedAgent">
                                <i data-lucide="user" class="w-3 h-3 mr-1 text-primary"></i>
                                <span v-if="selectedAgent.agent">@{{ selectedAgent.agent.matricule }} @{{ selectedAgent.agent.fullname }}</span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-4" v-if="selectedAgent">
                                <div class="col-span-12">
                                    <a class="flex cursor-pointer items-center rounded-md bg-white p-3 border-b dark:border-darkmode-300 transition duration-300 ease-in-out hover:bg-slate-100 dark:bg-darkmode-600 dark:hover:bg-darkmode-400" href="#">
                                        <div class="mr-1 font-extrabold uppercase max-w-[50%] truncate">
                                            Elément
                                        </div>

                                        <div class="ml-auto font-extrabold uppercase">
                                            Note
                                        </div>
                                    </a>
                                    <a v-for="(item, index) in selectedAgent.notes" class="flex cursor-pointer items-center rounded-md bg-white p-3 border-b dark:border-darkmode-300 transition duration-300 ease-in-out hover:bg-slate-100 dark:bg-darkmode-600 dark:hover:bg-darkmode-400" href="#">
                                        <div class="mr-1 font-italic max-w-[50%] truncate">
                                        @{{ item.element.libelle }}
                                        </div>

                                        <div class="ml-auto font-medium">
                                            @{{ item.note }}
                                        </div>
                                    </a>
                                </div>
                            </div>
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
<script type="module" src="{{ asset("assets/js/scripts/rounds.js") }}"></script>
@endpush