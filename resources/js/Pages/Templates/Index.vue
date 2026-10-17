<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  predefined: {
    type: Array,
    default: () => [],
  },
  userTemplates: {
    type: Array,
    default: () => [],
  },
  isGuest: {
    type: Boolean,
    default: true,
  },
});

const selectedCategory = ref('All');
const searchQuery = ref('');

const categories = computed(() => {
  const cats = new Set(props.predefined.map(t => t.category));
  return ['All', ...Array.from(cats)];
});

const filteredPredefined = computed(() => {
  return props.predefined.filter(t => {
    const matchesCat = selectedCategory.value === 'All' || t.category === selectedCategory.value;
    const matchesQuery = t.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         (t.description && t.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
    return matchesCat && matchesQuery;
  });
});

const duplicateTemplate = async (id) => {
  try {
    const res = await window.axios.post(`/api/templates/${id}/duplicate`);
    if (res.data.success) {
      window.location.reload();
    }
  } catch (err) {
    alert('Failed to duplicate template');
  }
};

const deleteTemplate = async (id) => {
  if (!confirm('Are you sure you want to delete this template?')) return;
  try {
    const res = await window.axios.delete(`/api/templates/${id}`);
    if (res.data.success) {
      window.location.reload();
    }
  } catch (err) {
    alert('Failed to delete template');
  }
};
</script>

<template>
  <Head title="Free Open-Source Email Template Builder" />

  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans overflow-x-hidden">
    <!-- TOP NAVIGATION -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center space-x-2.5 sm:space-x-3 shrink-0">
          <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          </div>
          <div class="flex items-center">
            <span class="font-extrabold text-base sm:text-lg tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
              MailCraft
            </span>
            <span class="hidden sm:inline-flex ml-2 text-[11px] font-semibold uppercase tracking-wider px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100 whitespace-nowrap">
              Open Source
            </span>
          </div>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
          <a
            href="https://huzaifaazeem.com"
            target="_blank"
            class="hidden lg:flex items-center space-x-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 px-3 h-9 rounded-lg border border-slate-200 hover:border-indigo-200 transition shrink-0"
          >
            <span>👨‍💻 By Huzaifa Azeem</span>
          </a>

          <template v-if="isGuest">
            <Link
              :href="route('login')"
              class="h-9 px-3 sm:px-3.5 text-xs font-semibold text-slate-700 hover:text-indigo-600 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-sm transition flex items-center justify-center whitespace-nowrap shrink-0"
            >
              Sign In
            </Link>
          </template>
          <template v-else>
            <Link
              :href="route('profile.edit')"
              class="h-9 px-2.5 sm:px-3 text-xs font-semibold text-slate-700 hover:text-slate-900 border border-slate-200 bg-white rounded-lg flex items-center justify-center space-x-1.5 whitespace-nowrap shadow-sm shrink-0"
            >
              <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
              <span>My Account</span>
            </Link>
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="h-9 px-2.5 text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition flex items-center justify-center whitespace-nowrap shrink-0"
            >
              Logout
            </Link>
          </template>

          <Link
            :href="route('editor.new')"
            class="h-9 px-3 sm:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-indigo-100 transition active:scale-95 flex items-center justify-center space-x-1.5 whitespace-nowrap shrink-0"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Blank Canvas</span>
          </Link>
        </div>
      </div>
    </header>

    <!-- HERO SECTION -->
    <section class="py-12 bg-white border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center space-x-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 text-xs font-semibold mb-4">
          <span>✨ 100% Free & Open-Source Email Builder</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
          Design Beautiful Emails.<br />
          <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
            Export Clean, Email-Safe HTML.
          </span>
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base text-slate-600">
          Create responsive email templates for your clients or agency with our visual drag-and-drop editor. Copy HTML or download ZIP packages compatible with Gmail, Outlook, Mailchimp, Resend, Brevo, and Laravel Mail.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-6 flex flex-wrap justify-center gap-3">
          <Link
            :href="route('editor.new')"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-100 transition active:scale-95 flex items-center space-x-2"
          >
            <span>Start Building for Free</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </Link>
          <a
            href="#predefined"
            class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition"
          >
            Explore Predefined Templates
          </a>
        </div>
      </div>
    </section>

    <!-- MAIN TEMPLATES CONTENT -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full space-y-12">
      <!-- USER SAVED TEMPLATES (If any) -->
      <section v-if="userTemplates.length > 0">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-slate-900">Your Saved Templates</h2>
          <span class="text-xs text-slate-500">{{ userTemplates.length }} templates</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="t in userTemplates"
            :key="t.id"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition"
          >
            <div class="p-5 flex-1 flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-[11px] font-semibold px-2 py-0.5 bg-slate-100 text-slate-700 rounded">
                    {{ t.category || 'Custom' }}
                  </span>
                  <span class="text-[11px] text-slate-400">
                    {{ new Date(t.updated_at).toLocaleDateString() }}
                  </span>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">{{ t.name }}</h3>
              </div>

              <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <Link
                  :href="route('editor.edit', t.slug)"
                  class="text-xs font-bold text-indigo-600 hover:text-indigo-800"
                >
                  Edit Template →
                </Link>
                <div class="flex items-center space-x-2">
                  <button
                    @click="duplicateTemplate(t.id)"
                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded hover:bg-slate-50"
                    title="Duplicate"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                  </button>
                  <button
                    @click="deleteTemplate(t.id)"
                    class="p-1.5 text-red-400 hover:text-red-600 rounded hover:bg-red-50"
                    title="Delete"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- PREDEFINED TEMPLATES GALLERY -->
      <section id="predefined">
        <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5 mb-8">
          <div class="min-w-0">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Predefined Email Templates</h2>
            <p class="text-sm text-slate-500 mt-1">Pick any ready-to-use template and customize it in the visual editor.</p>
          </div>

          <!-- Search & Filter Controls -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full xl:w-auto min-w-0">
            <div class="relative w-full sm:w-60 shrink-0">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search templates..."
                class="w-full h-9 pl-8 pr-3 text-xs border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
              />
              <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="flex items-center space-x-1.5 overflow-x-auto py-1 w-full min-w-0 max-w-full pb-2 sm:pb-1 scrollbar-none">
              <button
                v-for="cat in categories"
                :key="cat"
                @click="selectedCategory = cat"
                class="h-9 px-3.5 text-xs font-semibold rounded-lg transition whitespace-nowrap shrink-0 flex items-center justify-center shadow-sm"
                :class="selectedCategory === cat ? 'bg-indigo-600 text-white shadow-indigo-100 font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
              >
                {{ cat }}
              </button>
            </div>
          </div>
        </div>

        <!-- TEMPLATE CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="t in filteredPredefined"
            :key="t.id"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-lg transition-all duration-200 group"
          >
            <!-- Header preview graphic -->
            <div class="h-44 bg-gradient-to-br from-indigo-50/80 via-slate-50 to-purple-50/80 p-6 flex flex-col justify-center items-center text-center border-b border-slate-100 group-hover:scale-[1.01] transition-transform">
              <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-indigo-100/70 flex items-center justify-center text-indigo-600 mb-2.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              </div>
              <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 bg-white/90 backdrop-blur px-3 py-0.5 rounded-full border border-indigo-100 shadow-xs">
                {{ t.category }}
              </span>
              <p class="text-[11px] text-slate-500 mt-2 font-mono">
                {{ t.design_json?.blocks?.length || 0 }} Block Elements
              </p>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="font-bold text-slate-900 text-base mb-1.5 group-hover:text-indigo-600 transition">
                  {{ t.name }}
                </h3>
                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                  {{ t.description }}
                </p>
              </div>

              <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                <Link
                  :href="route('editor.edit', t.slug)"
                  class="w-full inline-flex items-center justify-center space-x-1.5 px-4 py-2 bg-slate-900 hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition"
                >
                  <span>Use This Template</span>
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-xs text-slate-500">
      <div class="max-w-7xl mx-auto px-4 space-y-2">
        <p>Built with ❤️ as a free & open-source project under MIT License.</p>
        <p>
          Developer: <strong>Huzaifa Azeem</strong> •
          <a href="https://huzaifaazeem.com/" target="_blank" class="text-indigo-600 underline">huzaifaazeem.com</a> •
          <a href="https://github.com/huzaifaazeem869" target="_blank" class="text-indigo-600 underline">GitHub</a>
        </p>
      </div>
    </footer>
  </div>
</template>
