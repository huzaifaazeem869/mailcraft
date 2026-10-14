<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-8 sm:px-6 lg:px-8 font-sans">
        <!-- MailCraft Branded Header -->
        <div class="mb-6">
            <Link :href="route('templates.index')" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-100 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                        MailCraft
                    </span>
                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">
                        Email Template Builder
                    </span>
                </div>
            </Link>
        </div>

        <!-- Main Card Container -->
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50">
            <slot />
        </div>

        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; 2026 MailCraft • Free Open Source Email Template Builder
        </div>
    </div>
</template>
