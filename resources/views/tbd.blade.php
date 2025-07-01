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
                    <a href="#">Vue globale des opérations</a>
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
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex h-10 items-center">
                        <h2 class="mr-5 truncate text-lg font-medium">Rapport général</h2>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="refresh-ccw"
                                class="stroke-1.5 mr-3 h-4 w-4"></i>
                            Actualiser les données
                        </a>
                    </div>
                    <div class="mt-5 grid grid-cols-12 gap-6">
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Nombre des sites"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="home"
                                            class="stroke-1.5 h-[28px] w-[28px] text-primary"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">0.00</div>
                                    <div class="mt-1 text-base text-slate-500">Nombre des sites</div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents présents dans les sites"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="users"
                                            class="stroke-1.5 h-[28px] w-[28px] text-success"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">0.00</div>
                                    <div class="mt-1 text-base text-slate-500">Agents présents</div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents absents"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="users"
                                            class="stroke-1.5 h-[28px] w-[28px] text-warning"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">0.00</div>
                                    <div class="mt-1 text-base text-slate-500">
                                        Agents absents
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
                            <div data-placement="top" title="Agents absents"
                                class="tooltip relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-tw-merge="" data-lucide="group"
                                            class="stroke-1.5 h-[28px] w-[28px] text-pending"></i>
                                    </div>
                                    <div class="mt-6 text-3xl font-medium leading-8">0.00</div>
                                    <div class="mt-1 text-base text-slate-500">
                                        Patrouilles en cours
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
                <div class="col-span-12 mt-6">
                    <div class="intro-y block h-10 items-center sm:flex">
                        <h2 class="mr-5 uppercase font-extrabold truncate text-lg text-blue-500">
                            Situation globale des présences par sites
                        </h2>
                        <div class="mt-3 flex items-center sm:ml-auto sm:mt-0">
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
                    <div class="intro-y mt-8 overflow-auto sm:mt-0 lg:overflow-visible">
                        <table data-tw-merge=""
                            class="w-full text-left border-separate border-spacing-y-[10px] sm:mt-2">
                            <thead data-tw-merge="" class="">
                                <tr data-tw-merge="" class="">
                                    <th data-tw-merge=""
                                        class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                                        IMAGES
                                    </th>
                                    <th data-tw-merge=""
                                        class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0">
                                        PRODUCT NAME
                                    </th>
                                    <th data-tw-merge=""
                                        class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                                        STOCK
                                    </th>
                                    <th data-tw-merge=""
                                        class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                                        STATUS
                                    </th>
                                    <th data-tw-merge=""
                                        class="font-medium px-5 py-3 dark:border-darkmode-300 whitespace-nowrap border-b-0 text-center">
                                        ACTIONS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-tw-merge="" class="intro-x">
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top"
                                                    title="Uploaded at 1 September 2021"
                                                    src="dist/images/fakers/preview-14.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top"
                                                    title="Uploaded at 22 October 2021"
                                                    src="dist/images/fakers/profile-15.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top"
                                                    title="Uploaded at 26 December 2021"
                                                    src="dist/images/fakers/profile-5.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <a class="whitespace-nowrap font-medium" href="#">
                                            Nikon Z6
                                        </a>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            Photography
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        119
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex items-center justify-center text-success">
                                            <i data-tw-merge="" data-lucide="check-square"
                                                class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Active
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            <a class="mr-3 flex items-center" href="#">
                                                <i data-tw-merge="" data-lucide="check-square"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Edit
                                            </a>
                                            <a class="flex items-center text-danger" href="#">
                                                <i data-tw-merge="" data-lucide="trash"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="intro-x">
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 20 March 2022"
                                                    src="dist/images/fakers/preview-4.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 12 June 2022"
                                                    src="dist/images/fakers/profile-4.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top"
                                                    title="Uploaded at 13 February 2021"
                                                    src="dist/images/fakers/profile-5.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <a class="whitespace-nowrap font-medium" href="#">
                                            Sony A7 III
                                        </a>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            Photography
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        82
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex items-center justify-center text-success">
                                            <i data-tw-merge="" data-lucide="check-square"
                                                class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Active
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            <a class="mr-3 flex items-center" href="#">
                                                <i data-tw-merge="" data-lucide="check-square"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Edit
                                            </a>
                                            <a class="flex items-center text-danger" href="#">
                                                <i data-tw-merge="" data-lucide="trash"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="intro-x">
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 19 April 2021"
                                                    src="dist/images/fakers/preview-9.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 28 August 2020"
                                                    src="dist/images/fakers/profile-8.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 15 June 2020"
                                                    src="dist/images/fakers/profile-7.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <a class="whitespace-nowrap font-medium" href="#">
                                            Nikon Z6
                                        </a>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            Photography
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        76
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex items-center justify-center text-success">
                                            <i data-tw-merge="" data-lucide="check-square"
                                                class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Active
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            <a class="mr-3 flex items-center" href="#">
                                                <i data-tw-merge="" data-lucide="check-square"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Edit
                                            </a>
                                            <a class="flex items-center text-danger" href="#">
                                                <i data-tw-merge="" data-lucide="trash"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="intro-x">
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 5 April 2021"
                                                    src="dist/images/fakers/preview-5.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 9 July 2020"
                                                    src="dist/images/fakers/profile-2.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                            <div class="image-fit zoom-in -ml-5 h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 13 April 2020"
                                                    src="dist/images/fakers/profile-1.jpg"
                                                    alt="Midone - Tailwind Admin Dashboard Template"
                                                    class="tooltip cursor-pointer rounded-full shadow-[0px_0px_0px_2px_#fff,_1px_1px_5px_rgba(0,0,0,0.32)] dark:shadow-[0px_0px_0px_2px_#3f4865,_1px_1px_5px_rgba(0,0,0,0.32)]">
                                            </div>
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <a class="whitespace-nowrap font-medium" href="#">
                                            Samsung Q90 QLED TV
                                        </a>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            Electronic
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        135
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                        <div class="flex items-center justify-center text-success">
                                            <i data-tw-merge="" data-lucide="check-square"
                                                class="stroke-1.5 mr-2 h-4 w-4"></i>
                                            Active
                                        </div>
                                    </td>
                                    <td data-tw-merge=""
                                        class="px-5 py-3 border-b dark:border-darkmode-300 box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            <a class="mr-3 flex items-center" href="#">
                                                <i data-tw-merge="" data-lucide="check-square"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Edit
                                            </a>
                                            <a class="flex items-center text-danger" href="#">
                                                <i data-tw-merge="" data-lucide="trash"
                                                    class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="intro-y mt-3 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
                        <nav class="w-full sm:mr-auto sm:w-auto">
                            <ul class="flex w-full mr-0 sm:mr-auto sm:w-auto">
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3"><i
                                            data-tw-merge="" data-lucide="chevrons-left"
                                            class="stroke-1.5 h-4 w-4"></i></a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3"><i
                                            data-tw-merge="" data-lucide="chevron-left"
                                            class="stroke-1.5 h-4 w-4"></i></a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3">...</a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3">1</a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3 !box dark:bg-darkmode-400">2</a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3">3</a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3">...</a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3"><i
                                            data-tw-merge="" data-lucide="chevron-right"
                                            class="stroke-1.5 h-4 w-4"></i></a>
                                </li>
                                <li class="flex-1 sm:flex-initial">
                                    <a data-tw-merge=""
                                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3"><i
                                            data-tw-merge="" data-lucide="chevrons-right"
                                            class="stroke-1.5 h-4 w-4"></i></a>
                                </li>
                            </ul>
                        </nav>
                        <select data-tw-merge=""
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 !box mt-3 w-20 sm:mt-0">
                            <option>10</option>
                            <option>25</option>
                            <option>35</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
            </div>
        </div>
    </div>

</div>
<!-- END: Content -->
@endsection