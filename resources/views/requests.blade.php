@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Liste des requêtes
        </h2>
    </div>
    <div id="App" v-cloak>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                <div class="hidden xl:block mx-auto text-slate-500"></div>
                <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 mr-3">
                    <div class="w-56 relative text-slate-500">
                        <input type="text" v-model="search" @input="filter_date=''" class="form-control w-56 box pr-10" placeholder="Par nom ou matricule agent...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                    </div>
                </div>
                <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 mr-3">
                    <div class="w-56 relative text-slate-500">
                        <input type="date" v-model="filter_date" @input="search=''"  class="form-control w-56 box pr-10">
                    </div>
                    <button class="btn btn-outline-pending ml-2" v-if="filter_date !==''" @click="filter_date=''"><i data-lucide="x"> </i></button>
                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">DATE & HEURE</th>
                            <th class="text-center whitespace-nowrap">OBJET DE LA REQUETE</th>
                            <th class="text-center whitespace-nowrap">AGENT</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="intro-x" v-for="(data, index) in allRequests" :key="data.id">
                            <td class="concat"> @{{ data.created_at }}</td>
                            <td class="concat text-center"> @{{ data.object }}</td>
                            <td class="!py-3.5">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <a href="javascript:void(0);" class="font-medium whitespace-nowrap"><span v-if="data.agent">@{{ data.agent.fullname }}</span> </a>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            <span v-if="data.agent">@{{ data.agent.matricule }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-primary" href="javascript:;" @click="selectedRequest = data" data-tw-toggle="modal" data-tw-target="#modal-infos"> <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>Voir détails </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div id="modal-infos" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-slate-100">
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body">
                        <div class="grid grid-cols-12 gap-2 gap-y-8" v-if="selectedRequest">
                            <!--
                            <div class="col-span-12">
                                <div class="w-full h-64 my-5 image-fit"> <img alt="Midone - HTML Admin Template" src="assets/images/preview-7.jpg" data-action="zoom" class="w-full rounded-md"> </div>
                            </div>
                            -->

                            <div class="col-span-12 md:col-span-12">
                                <h2 class="text-2xl font-semibold leading-none mb-2">@{{ selectedRequest.object }}</h2>
                                <p>@{{ selectedRequest.description }}</p>
                            </div>

                            <div class="col-span-12">
                                <div style="width: 100%; justify-content: end; display: flex;">
                                    <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                    <p class="text-slate-500 mr-2">@{{ selectedRequest.agent.matricule }}</p>
                                    <h4 class="text-start text-primary font-semibold">@{{ selectedRequest.agent.fullname }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button id="btn-reset" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Fermer</button>
                    </div>
                    <!-- END: Modal Footer -->
                </div>
            </div>
        </div>
    </div>

    <div class="h-full flex items-center" id="loader">
        <div class="mx-auto text-center">
            <div>
                <img src="{{ asset('assets/images/loading.gif') }}" class="w-12 h-12" />
            </div>
        </div>
    </div>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/requests.js") }}"></script>
@endpush
