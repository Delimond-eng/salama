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
                    <a href="#">Historique détaillé de l'agent</a> <a href="{{ url('/agents.list') }}" class="bg-dark p-1 text-xs rounded-lg ml-2 text-white">Retour</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Historique détaillé de l'agent</h2>
    </div>
    <div id="App" v-cloak>
        <!-- BEGIN: Profile Info -->
        <div class="intro-y box mt-5 pt-5 border-b" v-if="agent">
            <div class="-mx-5 px-5 flex flex-col border-b border-slate-200/60 pb-5 dark:border-darkmode-400 lg:flex-row">
                <div class="flex flex-1 items-center justify-center px-5 lg:justify-start">
                    <div class="image-fit relative h-20 w-20 flex-none sm:h-24 sm:w-24 lg:h-32 lg:w-32">
                        <img v-if="agent.photo"  data-action="zoom" class="rounded-lg" :src="agent.photo" alt="profile">
                        <img v-else class="rounded-full" src="{{ asset("assets/images/profil-2.png") }}" alt="profile">
                        <!-- <div class="absolute bottom-0 right-0 mb-1 mr-1 flex items-center justify-center rounded-full bg-primary p-2">
                            <i data-tw-merge="" data-lucide="camera" class="stroke-1.5 h-4 w-4 text-white"></i>
                        </div> -->
                    </div>
                    <div class="ml-5" v-if="agent">
                        <div class="truncate text-lg font-medium sm:w-40 sm:whitespace-normal">
                            @{{ agent.fullname }}
                        </div>
                        <div class="text-slate-500">@{{ agent.matricule }}</div>
                    </div>
                </div>
                <div v-if="agent.site" class="mt-6 flex-1 flex items-center justify-center border-l border-r border-t border-slate-200/60 px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-t-0 lg:pt-0">
                    <div>
                        <div class="text-center font-medium lg:mt-3 lg:text-left">
                            Site courant affecté
                        </div>
                        <div class="mt-4 flex flex-col items-center justify-center lg:items-start">
                            <div class="flex items-center truncate sm:whitespace-normal">
                                <i data-tw-merge="" data-lucide="home" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                @{{ agent.site.name }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex-1 flex items-center justify-center border-t border-slate-200/60 px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-0 lg:pt-0">
                    <div>
                        <div class="text-center font-medium lg:mt-5 lg:text-left">
                            Status courant
                        </div>
                        <div class="mt-2 flex items-center justify-center lg:justify-start">
                            <div class="mr-2">
                                <span class="ml-1 text-xs rounded bg-success/20 p-1 text-primary">
                                    @{{ agent.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div  class="w-full px-5 flex flex-col justify-center sm:flex-row lg:justify-start bg-slate-100 rounded-b">
                <div class="flex flex-wrap items-center justify-between w-full xl:flex-nowrap my-2">
                    <h1 class="font-extrabold uppercase">Parcours de l'agent</h1>
                    <div class="mx-auto hidden text-slate-500 xl:block">

                    </div>
                    <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                        <select data-tw-merge="" class="tom-select select-site rounded-md bg-white w-48">
                            <option value="" selected hidden>Site</option>
                        </select>
                        <button data-tw-merge="" class="ml-2 transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-white text-slate-800 dark:border-darkmode-100 shadow-md"><i data-tw-merge="" data-lucide="file-text" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Exporter en Exel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3" v-if="agent">
            <div class="box rounded-mg overflow-auto lg:overflow-visible" v-if="agent.stories.length">
                <table data-tw-merge="" class="w-full text-left">
                    <thead data-tw-merge="" class="">
                        <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                            <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap !py-5">
                                Date
                            </th>
                            <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                Site provenance
                            </th>
                            <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                Site affecté
                            </th>
                            <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap">
                                status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in agent.stories" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                            <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300">
                                @{{ data.date }}
                            </td>
                            <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300">
                                <span v-if="data.from"> @{{ data.from.name }}</span>
                                <span v-else> Non défini</span>
                            </td>
                            <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300">
                                <span v-if="data.site"> @{{ data.site.name }}</span>
                            </td>
                            <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300">
                                 @{{ data.status }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box rounded-lg" v-else>
                <x-empty-state message="Aucune historique répertoriée !"></x-empty-state>
            </div>
        </div>
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/agent_manager.js") }}"></script>
@endpush