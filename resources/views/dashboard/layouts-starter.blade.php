<x-app-layout title="Starter Blurred Header" is-sidebar-open="false" is-header-blur="true">
    <main class="main-content w-full px-[var(--margin-x)] pb-8">
        <div class="flex items-center space-x-4 py-5 lg:py-6">
            <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
                Reefside SMS
            </h2>
        </div>
        <div x-data="{activeTab:'tabHome'}" class="tabs flex flex-col">
            <div class="is-scrollbar-hidden overflow-x-auto">
                <div class="border-b-2 border-slate-150 dark:border-navy-500">
                    <div class="tabs-list flex">
                        <button
                                @click="activeTab = 'tabHome'"
                                :class="activeTab === 'tabHome' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                                class="btn shrink-0 rounded-none border-b-2 px-3 py-2 font-medium"
                        >
                            Upload File
                        </button>
                        <button
                                @click="activeTab = 'tabProfile'"
                                :class="activeTab === 'tabProfile' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                                class="btn shrink-0 rounded-none border-b-2 px-3 py-2 font-medium"
                        >
                            Send SMS
                        </button>
                    </div>
                </div>
            </div>
            <div class="tab-content pt-4">
                <div
                        x-show="activeTab === 'tabHome'"
                        x-transition:enter="transition-all duration-500 easy-in-out"
                        x-transition:enter-start="opacity-0 [transform:translate3d(0,1rem,0)]"
                        x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]"
                >
                    <div>
                        <p>
                        <div class="relative flex flex-col min-w-0 rounded break-words border bg-white border-1 border-gray-300">
                            <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-300 text-gray-900">
                                <b>Upload phone numbers</b>
                            </div>
                            <div class="flex-auto p-6">


                                <form action="{{ route('file.upload.post') }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex flex-wrap ">

                                        <div class="md:w-1/2 pr-4 pl-4">
                                            <input type="file" name="phones"
                                                   class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-gray-800 border border-gray-200 rounded">
                                        </div>

                                        <div class="md:w-1/2 pr-4 pl-4">
                                            <button type="submit"
                                                    class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap rounded py-1 px-3 leading-normal no-underline bg-green-500 text-white hover:green-600">
                                                Upload
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
                <div
                        x-show="activeTab === 'tabProfile'"
                        x-transition:enter="transition-all duration-500 easy-in-out"
                        x-transition:enter-start="opacity-0 [transform:translate3d(0,1rem,0)]"
                        x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]"
                >
                    <div>
                        <p>
                        <div class="relative flex flex-col min-w-0 rounded break-words border bg-white border-1 border-gray-300">
                            <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-300 text-gray-900">
                                <b>Add Phone Number</b>
                            </div>
                            <div class="flex-auto p-6">
                                <form method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label>Enter Phone Number</label>
                                        <input type="tel"
                                               class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-gray-800 border border-gray-200 rounded"
                                               name="phone_number"
                                               placeholder="Enter Phone Number">
                                    </div>
                                    <button type="submit"
                                            class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap rounded py-1 px-3 leading-normal no-underline bg-blue-600 text-white hover:bg-blue-600">
                                        Register User
                                    </button>
                                </form>
                            </div>
                        </div>
                        </p>
                    </div>
                    <div class="relative flex flex-col min-w-0 rounded break-words border bg-white border-1 border-gray-300">
                        <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-300 text-gray-900">
                            <b>Send SMS message</b>
                        </div>
                        <div class="flex-auto p-6">
                            <form method="POST" action="/custom">
                                @csrf
                                <div class="mb-4">
                                    <label>Select users to notify</label>
                                    <select name="users[]" multiple
                                            class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-gray-800 border border-gray-200 rounded">
                                        @foreach ($users as $user)
                                            <option>{{$user->phone_number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label>Notification Message</label>
                                    <textarea name="body"
                                              class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-gray-800 border border-gray-200 rounded"
                                              rows="3"></textarea>
                                </div>
                                <button type="submit"
                                        class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap rounded py-1 px-3 leading-normal no-underline bg-blue-600 text-white hover:bg-blue-600">
                                    Send Notification
                                </button>
                            </form>
                        </div>
                    </div>
                    </p>

                    <p class="pt-3 text-xs text-slate-400 dark:text-navy-300">
                        Send s single sms
                    </p>
                </div>
            </div>
        </div>
        </div>

    </main>
</x-app-layout>
