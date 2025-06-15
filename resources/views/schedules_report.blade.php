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
                    <a href="#">Rapport de planning</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 lg:col-span-10">
                    <div class="relative mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                        <div class="box pt-2 px-2">
                            <div class="p-5">
                                <div>
                                    <ul
                                        data-tw-merge
                                        role="tablist"
                                        class="flex mx-auto mb-8 mx-5 rounded-md border border-dashed border-primary p-1 dark:border-darkmode-300">
                                        <li
                                            id="example-3-tab"
                                            data-tw-merge
                                            role="presentation"
                                            class="focus-visible:outline-none flex-1">
                                            <button
                                                data-tw-merge
                                                data-tw-target="#example-3"
                                                role="tab"
                                                class="cursor-pointer block appearance-none px-5 py-2.5 border border-transparent text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white shadow-[0px_3px_20px_#0000000b] rounded-md [&.active]:bg-primary [&.active]:text-white [&.active]:font-medium active w-full py-2">RAPPORT AGENTS</button>
                                        </li>
                                        <li
                                            id="example-4-tab"
                                            data-tw-merge
                                            role="presentation"
                                            class="focus-visible:outline-none flex-1">
                                            <button
                                                data-tw-merge
                                                data-tw-target="#example-4"
                                                role="tab"
                                                class="cursor-pointer block appearance-none px-5 py-2.5 border border-transparent text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white shadow-[0px_3px_20px_#0000000b] rounded-md [&.active]:bg-primary [&.active]:text-white [&.active]:font-medium w-full py-2">RAPPORT SUPERVISEURS</button>
                                        </li>
                                    </ul>
                                    <div
                                        class="tab-content mt-5">
                                        <div
                                            data-transition
                                            data-selector=".active"
                                            data-enter="transition-[visibility,opacity] ease-linear duration-150"
                                            data-enter-from="!p-0 !h-0 overflow-hidden invisible opacity-0"
                                            data-enter-to="visible opacity-100"
                                            data-leave="transition-[visibility,opacity] ease-linear duration-150"
                                            data-leave-from="visible opacity-100"
                                            data-leave-to="!p-0 !h-0 overflow-hidden invisible opacity-0"
                                            id="example-3" role="tabpanel" aria-labelledby="example-3-tab" class="tab-pane active leading-relaxed">
                                            <div id="App" v-cloak>
                                                <form @submit.prevent="triggerFilter">
                                                    <div class="grid grid-cols-12 gap-4">
                                                        <div class="col-span-12">
                                                            <label for="vertical-form-2" class="uppercase inline-block text-blue-500 font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Agent particulier <small>(Optionnel)</small>
                                                            </label>
                                                            <select v-model="filter.agent_id" class="tom-select w-full" data-placeholder="Sélectionnez un agent">
                                                                <option value="" selected hidden>--Sélectionnez un agent--</option>
                                                                @foreach($agents as $agent)
                                                                <option value="{{ $agent->id }}">{{ $agent->matricule }} | {{ $agent->fullname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-4">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Année
                                                            </label>
                                                            <select v-model="filter.year" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="2025">2025</option>
                                                                <option value="2026">2026</option>
                                                                <option value="2027">2027</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-8">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                SITE
                                                            </label>
                                                            <select v-model="filter.site_id" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="" selected hidden label="Sélectionnez site..."></option>
                                                                <option value="">Tous les sites</option>
                                                                @foreach($sites as $site)
                                                                <option value="{{ $site->id }}">{{ $site->code }} | {{ $site->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-8">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Par Date
                                                            </label>
                                                            <div class="flex gap-2 items-center">
                                                                <input id="vertical-form-1" v-model="filter.date_begin" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <span>--</span>
                                                                <input id="vertical-form-1" v-model="filter.date_end" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                            </div>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-4">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Par période
                                                            </label>
                                                            <select v-model="filter.period" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="" selected hidden label="Sélectionnez une période..."></option>
                                                                <option value="week">Hebdomadaire</option>
                                                                <option value="month">Mensuel</option>
                                                                <option value="quarter">Trimestriel</option>
                                                                <option value="year">Annuel</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center py-2 px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-primary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 relative mt-12 justify-start rounded-full"><span class="mr-6">Tirer le rapport</span>
                                                        <span class="absolute bottom-0 right-0 top-0 my-auto ml-auto mr-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">
                                                            <span class="h-4 w-4" v-if="isLoading">
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
                                                            <span v-else>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="arrow-right" class="lucide lucide-arrow-right stroke-1.5 h-4 w-4">
                                                                    <path d="M5 12h14"></path>
                                                                    <path d="m12 5 7 7-7 7"></path>
                                                                </svg>
                                                            </span>
                                                            
                                                        </span>
                                                        <div></div>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div
                                            data-transition
                                            data-selector=".active"
                                            data-enter="transition-[visibility,opacity] ease-linear duration-150"
                                            data-enter-from="!p-0 !h-0 overflow-hidden invisible opacity-0"
                                            data-enter-to="visible opacity-100"
                                            data-leave="transition-[visibility,opacity] ease-linear duration-150"
                                            data-leave-from="visible opacity-100"
                                            data-leave-to="!p-0 !h-0 overflow-hidden invisible opacity-0"
                                            id="example-4" role="tabpanel" aria-labelledby="example-4-tab" class="tab-pane leading-relaxed">
                                            <div id="App">
                                                <form @submit.prevent="triggerFilter">
                                                    <div class="grid grid-cols-12 gap-4">
                                                        <div class="col-span-12">
                                                            <label for="vertical-form-2" class="uppercase inline-block text-blue-500 font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Superviseur particulier <small>(Optionnel)</small>
                                                            </label>
                                                            <select v-model="filter.agent_id" class="tom-select w-full" data-placeholder="Sélectionnez un superviseur">
                                                                <option value="" selected hidden>--Sélectionnez un superviseur--</option>
                                                                @foreach($supervisors as $agent)
                                                                <option value="{{ $agent->id }}">{{ $agent->matricule }} | {{ $agent->fullname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-4">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Année
                                                            </label>
                                                            <select v-model="filter.year" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="2025">2025</option>
                                                                <option value="2026">2026</option>
                                                                <option value="2027">2027</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-8">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                SITE
                                                            </label>
                                                            <select v-model="filter.site_id" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="" selected hidden label="Sélectionnez site..."></option>
                                                                <option value="">Tous les sites</option>
                                                                @foreach($sites as $site)
                                                                <option value="{{ $site->id }}">{{ $site->code }} | {{ $site->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-8">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Par Date
                                                            </label>
                                                            <div class="flex gap-2 items-center">
                                                                <input id="vertical-form-1" v-model="filter.date_begin" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <span>--</span>
                                                                <input id="vertical-form-1" type="date" v-model="filter.date_end" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                            </div>
                                                        </div>
                                                        <div class="col-span-12 lg:col-span-4">
                                                            <label for="vertical-form-1" class="inline-block uppercase font-bold mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                                                Par période
                                                            </label>
                                                            <select v-model="filter.period" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                                <option value="" selected hidden label="Sélectionnez une période..."></option>
                                                                <option value="week">Hebdomadaire</option>
                                                                <option value="month">Mensuel</option>
                                                                <option value="quarter">Trimestriel</option>
                                                                <option value="year">Annuel</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center py-2 px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-primary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 relative mt-12 justify-start rounded-full"><span class="mr-6">Tirer le rapport</span>
                                                        <span class="absolute bottom-0 right-0 top-0 my-auto ml-auto mr-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">
                                                            <span class="h-4 w-4" v-if="isLoading">
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
                                                            <span v-else>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="arrow-right" class="lucide lucide-arrow-right stroke-1.5 h-4 w-4">
                                                                    <path d="M5 12h14"></path>
                                                                    <path d="m12 5 7 7-7 7"></path>
                                                                </svg>
                                                            </span>
                                                            
                                                        </span>
                                                        <div></div>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <x-dom-loader></x-dom-loader>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- END: General Report -->
            </div>
        </div>
    </div>

</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/filter.js") }}"></script>
@endpush