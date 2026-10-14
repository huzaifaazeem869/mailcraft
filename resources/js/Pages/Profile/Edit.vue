<script setup>
import { Head, Link } from '@inertiajs/vue3';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Account Settings - MailCraft" />

    <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans">
        <!-- TOP NAVBAR (MATCHING MAIRCRAFT THEME) -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <Link :href="route('templates.index')" class="flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                            MailCraft
                        </span>
                    </Link>
                </div>

                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('templates.index')"
                        class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-100 transition"
                    >
                        ← Back to Templates
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full space-y-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Account Settings</h1>
                <p class="text-xs text-slate-500 mt-1">Manage your profile details and security credentials.</p>
            </div>

            <!-- Profile Details Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <!-- Update Password Card with OTP -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                <UpdatePasswordForm class="max-w-xl" />
            </div>
        </main>
    </div>
</template>
