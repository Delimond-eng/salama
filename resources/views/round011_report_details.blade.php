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
            <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
                <div class="box rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Transaction Details
                        </div>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            Change Status
                        </a>
                    </div>
                    <div class="flex items-center">
                        <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Invoice:
                        <a class="ml-1 underline decoration-dotted" href="#">
                            INV/20220217/MPL/2053411933
                        </a>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Purchase Date: 24 March 2022
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="clock" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Transaction Status:
                        <span class="ml-1 rounded bg-success/20 px-2 text-success">
                            Completed
                        </span>
                    </div>
                </div>
                <div class="box mt-5 rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Buyer Details
                        </div>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            View Details
                        </a>
                    </div>
                    <div class="flex items-center">
                        <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Name:
                        <a class="ml-1 underline decoration-dotted" href="#">
                            Christian Bale
                        </a>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Phone Number: +71828273732
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="map-pin" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Address: 260 W. Storm Street New York, NY 10025.
                    </div>
                </div>
                <div class="box mt-5 rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Payment Details
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Payment Method:
                        <div class="ml-auto">Direct bank transfer</div>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="credit-card" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Total Price (2 items):
                        <div class="ml-auto">$12,500.00</div>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="credit-card" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Total Shipping Cost (800 gr):
                        <div class="ml-auto">$1,500.00</div>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="credit-card" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Shipping Insurance:
                        <div class="ml-auto">$600.00</div>
                    </div>
                    <div class="mt-5 flex items-center border-t border-slate-200/60 pt-5 font-medium dark:border-darkmode-400">
                        <i data-tw-merge="" data-lucide="credit-card" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Grand Total:
                        <div class="ml-auto">$15,000.00</div>
                    </div>
                </div>
                <div class="box mt-5 rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Shipping Information
                        </div>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="map-pin" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            Tracking Info
                        </a>
                    </div>
                    <div class="flex items-center">
                        <i data-tw-merge="" data-lucide="clipboard" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Courier: Left4code Express
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="calendar" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Tracking Number: 003005580322
                        <i data-tw-merge="" data-lucide="copy" class="stroke-1.5 ml-2 h-4 w-4 text-slate-500"></i>
                    </div>
                    <div class="mt-3 flex items-center">
                        <i data-tw-merge="" data-lucide="map-pin" class="stroke-1.5 mr-2 h-4 w-4 text-slate-500"></i>
                        Address: 260 W. Storm Street New York, NY 10025.
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
                <div class="box rounded-md p-5">
                    <div class="mb-5 flex items-center border-b border-slate-200/60 pb-5 dark:border-darkmode-400">
                        <div class="truncate text-base font-medium">
                            Order Details
                        </div>
                        <a class="ml-auto flex items-center text-primary" href="#">
                            <i data-tw-merge="" data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            Add Notes
                        </a>
                    </div>
                    <div class="-mt-3 overflow-auto lg:overflow-visible">
                        <table data-tw-merge="" class="w-full text-left">
                            <thead data-tw-merge="" class="">
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap !py-5">
                                        Product
                                    </th>
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Unit Price
                                    </th>
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Qty
                                    </th>
                                    <th data-tw-merge="" class="font-medium px-5 py-3 border-b-2 dark:border-darkmode-300 whitespace-nowrap text-right">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 5 March 2022" src="dist/images/fakers/preview-11.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Samsung Galaxy S20 Ultra
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $90,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $180,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 22 May 2021" src="dist/images/fakers/preview-4.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Nikon Z6
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $47,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $94,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 29 December 2022" src="dist/images/fakers/preview-14.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Apple MacBook Pro 13
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $47,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $94,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 9 July 2021" src="dist/images/fakers/preview-2.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Samsung Q90 QLED TV
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $118,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $236,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 7 February 2022" src="dist/images/fakers/preview-3.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Sony Master Series A9G
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $212,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $424,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 9 September 2021" src="dist/images/fakers/preview-13.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Nike Tanjun
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $82,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $164,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 6 March 2021" src="dist/images/fakers/preview-8.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Nike Tanjun
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $49,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $98,000.00
                                    </td>
                                </tr>
                                <tr data-tw-merge="" class="[&:nth-of-type(odd)_td]:bg-slate-100 [&:nth-of-type(odd)_td]:dark:bg-darkmode-300 [&:nth-of-type(odd)_td]:dark:bg-opacity-50">
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 !py-4">
                                        <div class="flex items-center">
                                            <div class="image-fit zoom-in h-10 w-10">
                                                <img data-placement="top" title="Uploaded at 29 May 2021" src="dist/images/fakers/preview-11.jpg" alt="Midone - Tailwind Admin Dashboard Template" class="tooltip cursor-pointer rounded-lg border-2 border-white shadow-md">
                                            </div>
                                            <a class="ml-4 whitespace-nowrap font-medium" href="#">
                                                Oppo Find X2 Pro
                                            </a>
                                        </div>
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $173,000.00
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        2
                                    </td>
                                    <td data-tw-merge="" class="px-5 py-3 border-b dark:border-darkmode-300 text-right">
                                        $346,000.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Transaction Details -->
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/rounds.js") }}"></script>
@endpush