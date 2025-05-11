@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Les signalements
        </h2>
    </div>
    <div id="App" v-cloak>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                <div class="hidden xl:block mx-auto text-slate-500"></div>
                <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                    <div class="w-56 relative text-slate-500">
                        <input type="date"  class="form-control w-56 box pr-10">
                    </div>
                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">DATE & HEURE</th>
                            <th class="text-center whitespace-nowrap">TITRE</th>
                            <th class="text-center whitespace-nowrap">AGENT</th>
                            <th class="text-center whitespace-nowrap">SITE</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="intro-x" v-for="(data, index) in allSignalements">
                            <td class="concat"> @{{ data.created_at }}</td>
                            <td class="concat"> @{{ data.title }}</td>
                            <td class="!py-3.5">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <a href="javascript:void(0);" class="font-medium whitespace-nowrap">@{{ data.agent.fullname }}</a>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">@{{ data.agent.matricule }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="!py-3.5">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <a href="javascript:void(0);" class="font-medium whitespace-nowrap">@{{ data.site.name }}</a>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">@{{ data.site.code }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-primary" href="javascript:;" @click="selectedSignalement=data" data-tw-toggle="modal" data-tw-target="#slider_infos"> <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>Voir détails </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="slider_infos" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" v-if="selectedSignalement">
                    <div class="modal-header p-5" style="display: flex; justify-content: space-between">
                        <div style="display: flex; flex-direction: column">
                            <small>Signalement titre</small>
                            <h2 class="font-semibold text-base mr-auto">@{{ selectedSignalement.title }}</h2>
                        </div>
                        <div class="text-start" style="display: flex;">
                            <i data-lucide="user" class="w-8 h-8 mr-1 text-primary"></i>
                            <div class="ml-2" style="display: flex; flex-direction: column">
                                <span class="font-semibold">@{{ selectedSignalement.agent.fullname }}</span>
                                <small>@{{ selectedSignalement.agent.matricule }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12">
                                <small>Vidéo ou image associée</small>
                                <div class="w-full rounded-md border border-slate-200/60 dark:border-darkmode-400 h-64 mb-5 image-fit" v-if="selectedSignalement.media.includes('.mp4')">
                                    <video class="w-full h-full rounded-md" controls>
                                        <source src="https://storage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <a v-else :href="selectedSignalement.media" target="_blank">
                                    <div class="w-full h-64 my-5 image-fit border border-slate-200/60 dark:border-darkmode-400 rounded-md">
                                        <img alt="media" :src="selectedSignalement.media" class="w-full rounded-md">
                                    </div>
                                </a>
                                <small>Description</small>
                                <p>@{{ selectedSignalement.description }}</p>
                            </div>
                        </div>
                    </div>
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
