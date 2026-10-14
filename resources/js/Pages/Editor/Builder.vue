<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, reactive, computed, onMounted, watch } from 'vue';
import draggable from 'vuedraggable';
import CanvasBlock from '@/Components/Editor/CanvasBlock.vue';
import PropertyPanel from '@/Components/Editor/PropertyPanel.vue';

const props = defineProps({
  initialTemplate: {
    type: Object,
    default: null,
  },
  auth: {
    type: Object,
    default: () => ({ user: null }),
  },
});

// Editor State
const templateName = ref(props.initialTemplate?.name || 'Untitled Email Template');
const category = ref(props.initialTemplate?.category || 'Custom');
const previewMode = ref('desktop'); // 'desktop' or 'mobile'
const selectedBlockId = ref(null);
const isExportModalOpen = ref(false);
const isSaveModalOpen = ref(false);
const exportHtmlOutput = ref('');
const isRendering = ref(false);
const copySuccess = ref(false);

// Undo / Redo history
const history = ref([]);
const historyIndex = ref(-1);
const isHistoryNavigating = ref(false);

// Brand & Design Data
const brand = reactive({
  canvasWidth: props.initialTemplate?.design_json?.brand?.canvasWidth || '600px',
  canvasBg: props.initialTemplate?.design_json?.brand?.canvasBg || '#ffffff',
  bodyBg: props.initialTemplate?.design_json?.brand?.bodyBg || '#f3f4f6',
  fontFamily: props.initialTemplate?.design_json?.brand?.fontFamily || 'Arial, Helvetica, sans-serif',
  textColor: props.initialTemplate?.design_json?.brand?.textColor || '#1f2937',
  primaryColor: props.initialTemplate?.design_json?.brand?.primaryColor || '#4f46e5',
  buttonRadius: props.initialTemplate?.design_json?.brand?.buttonRadius || '6px',
  company: props.initialTemplate?.design_json?.brand?.company || 'Your Agency',
});

const blocks = ref(props.initialTemplate?.design_json?.blocks || [
  {
    id: 'block-' + Date.now(),
    type: 'heading',
    props: {
      text: 'Start Designing Your Email Template',
      level: 'h1',
      align: 'center',
      color: '#111827',
      fontWeight: 'bold',
      padding: '24px 24px 8px 24px',
    },
  },
  {
    id: 'block-' + (Date.now() + 1),
    type: 'text',
    props: {
      content: '<p style="text-align: center;">Drag blocks from the left sidebar to add more content. Click on any block to change colors, fonts, and properties!</p>',
      align: 'center',
      fontSize: '15px',
      lineHeight: '1.6',
      color: '#4b5563',
      padding: '8px 24px 20px 24px',
    },
  },
  {
    id: 'block-' + (Date.now() + 2),
    type: 'button',
    props: {
      text: 'Get Started Today →',
      url: 'https://example.com',
      align: 'center',
      bgColor: '#4f46e5',
      textColor: '#ffffff',
      borderRadius: '6px',
      padding: '12px 24px 24px 24px',
      paddingY: '12px',
      paddingX: '28px',
    },
  },
]);

// Available blocks toolbox
const availableBlocks = [
  {
    type: 'heading',
    label: 'Heading',
    icon: 'H',
    defaultProps: { text: 'New Heading', level: 'h1', align: 'left', color: '#111827', fontWeight: 'bold', padding: '16px 24px' },
  },
  {
    type: 'text',
    label: 'Text Paragraph',
    icon: 'T',
    defaultProps: { content: '<p>Edit this text paragraph with your content...</p>', align: 'left', fontSize: '15px', lineHeight: '1.6', color: '#374151', padding: '16px 24px' },
  },
  {
    type: 'button',
    label: 'Call to Action',
    icon: '🔘',
    defaultProps: { text: 'Click Here', url: 'https://example.com', align: 'center', bgColor: '#4f46e5', textColor: '#ffffff', borderRadius: '6px', padding: '16px 24px', paddingY: '12px', paddingX: '24px' },
  },
  {
    type: 'image',
    label: 'Image Banner',
    icon: '🖼️',
    defaultProps: { src: 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=800&auto=format&fit=crop', alt: 'Email Graphic', align: 'center', width: '100%', maxWidth: '100%', borderRadius: '4px', padding: '16px 24px' },
  },
  {
    type: 'divider',
    label: 'Divider Line',
    icon: '➖',
    defaultProps: { color: '#e5e7eb', height: '1px', style: 'solid', padding: '16px 24px' },
  },
  {
    type: 'spacer',
    label: 'Blank Spacer',
    icon: '↕️',
    defaultProps: { height: '24px' },
  },
  {
    type: 'social',
    label: 'Social Icons',
    icon: '🔗',
    defaultProps: {
      align: 'center',
      iconSize: '24px',
      spacing: '8px',
      padding: '16px 24px',
      items: [
        { platform: 'Twitter', url: 'https://x.com', icon: 'https://cdn-icons-png.flaticon.com/512/733/733579.png' },
        { platform: 'LinkedIn', url: 'https://linkedin.com', icon: 'https://cdn-icons-png.flaticon.com/512/733/733561.png' },
        { platform: 'Instagram', url: 'https://instagram.com', icon: 'https://cdn-icons-png.flaticon.com/512/733/733558.png' },
      ],
    },
  },
  {
    type: 'footer',
    label: 'Footer & Unsubscribe',
    icon: '📝',
    defaultProps: { address: '123 Business Street, Suite 100, City, Country', unsubscribeText: 'Unsubscribe', unsubscribeUrl: '{{unsubscribe_url}}', color: '#9ca3af', fontSize: '12px', padding: '24px' },
  },
];

// Computed active block for properties panel
const selectedBlock = computed(() => {
  return blocks.value.find(b => b.id === selectedBlockId.value) || null;
});

// Selection & Block Operations
const selectBlock = (block) => {
  selectedBlockId.value = block ? block.id : null;
};

const addBlock = (blockDef) => {
  const newBlock = {
    id: 'block-' + Date.now(),
    type: blockDef.type,
    props: JSON.parse(JSON.stringify(blockDef.defaultProps)),
  };
  blocks.value.push(newBlock);
  selectedBlockId.value = newBlock.id;
  pushHistory();
};

const duplicateBlock = (block) => {
  const index = blocks.value.findIndex(b => b.id === block.id);
  if (index !== -1) {
    const copy = {
      id: 'block-' + Date.now(),
      type: block.type,
      props: JSON.parse(JSON.stringify(block.props)),
    };
    blocks.value.splice(index + 1, 0, copy);
    selectedBlockId.value = copy.id;
    pushHistory();
  }
};

const deleteBlock = (id) => {
  blocks.value = blocks.value.filter(b => b.id !== id);
  if (selectedBlockId.value === id) {
    selectedBlockId.value = null;
  }
  pushHistory();
};

const insertVariableIntoBlock = (tag) => {
  if (!selectedBlock.value) return;
  if (selectedBlock.value.type === 'heading') {
    selectedBlock.value.props.text = (selectedBlock.value.props.text || '') + ' ' + tag;
  } else if (selectedBlock.value.type === 'text') {
    selectedBlock.value.props.content = (selectedBlock.value.props.content || '') + ' ' + tag;
  }
};

// Undo / Redo System
const pushHistory = () => {
  if (isHistoryNavigating.value) return;
  const snapshot = JSON.stringify({
    blocks: blocks.value,
    brand: brand,
  });

  // truncate forward history if we are in the middle
  if (historyIndex.value < history.value.length - 1) {
    history.value = history.value.slice(0, historyIndex.value + 1);
  }

  history.value.push(snapshot);
  historyIndex.value = history.value.length - 1;
};

const undo = () => {
  if (historyIndex.value > 0) {
    isHistoryNavigating.value = true;
    historyIndex.value--;
    const state = JSON.parse(history.value[historyIndex.value]);
    blocks.value = state.blocks;
    Object.assign(brand, state.brand);
    isHistoryNavigating.value = false;
  }
};

const redo = () => {
  if (historyIndex.value < history.value.length - 1) {
    isHistoryNavigating.value = true;
    historyIndex.value++;
    const state = JSON.parse(history.value[historyIndex.value]);
    blocks.value = state.blocks;
    Object.assign(brand, state.brand);
    isHistoryNavigating.value = false;
  }
};

// Export Handlers
const openExportModal = async () => {
  isRendering.value = true;
  isExportModalOpen.value = true;
  try {
    const res = await window.axios.post('/api/templates/render-html', {
      design_json: { brand, blocks: blocks.value },
    });
    exportHtmlOutput.value = res.data.html;
  } catch (err) {
    exportHtmlOutput.value = '<!-- Error rendering HTML preview -->';
  } finally {
    isRendering.value = false;
  }
};

const copyHtmlToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(exportHtmlOutput.value);
    copySuccess.value = true;
    setTimeout(() => { copySuccess.value = false; }, 2500);
  } catch (err) {
    alert('Failed to copy to clipboard');
  }
};

const downloadSingleHtml = async () => {
  try {
    const response = await window.axios.post('/api/templates/export-html', {
      name: templateName.value,
      design_json: { brand, blocks: blocks.value },
    }, { responseType: 'blob' });

    const blob = new Blob([response.data], { type: 'text/html' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `${templateName.value.toLowerCase().replace(/[^a-z0-9]/g, '-')}.html`;
    link.click();
  } catch (err) {
    alert('Failed to download HTML');
  }
};

const downloadZip = async () => {
  try {
    const response = await window.axios.post('/api/templates/export-zip', {
      name: templateName.value,
      design_json: { brand, blocks: blocks.value },
    }, { responseType: 'blob' });

    const blob = new Blob([response.data], { type: 'application/zip' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `${templateName.value.toLowerCase().replace(/[^a-z0-9]/g, '-')}.zip`;
    link.click();
  } catch (err) {
    alert('Failed to download ZIP');
  }
};

const saveTemplateToServer = async () => {
  try {
    const payload = {
      name: templateName.value,
      category: category.value,
      design_json: { brand, blocks: blocks.value },
    };

    let res;
    if (props.initialTemplate && !props.initialTemplate.is_predefined) {
      res = await window.axios.post(`/api/templates/${props.initialTemplate.id}/update`, payload);
    } else {
      res = await window.axios.post('/api/templates/save', payload);
    }

    if (res.data.success) {
      alert(res.data.message);
      isSaveModalOpen.value = false;
    }
  } catch (err) {
    alert('Failed to save template. ' + (err.response?.data?.message || ''));
  }
};

onMounted(() => {
  pushHistory();
});
</script>

<template>
  <Head :title="`${templateName} - Visual Email Builder`" />

  <div class="h-screen flex flex-col bg-slate-100 overflow-hidden text-slate-800 font-sans select-none">
    <!-- TOP NAVBAR -->
    <header class="h-14 bg-white border-b border-slate-200 px-4 flex items-center justify-between z-30 shrink-0">
      <!-- Left: Logo & Template Title -->
      <div class="flex items-center space-x-3">
        <Link
          :href="route('templates.index')"
          class="flex items-center space-x-2 text-slate-700 hover:text-indigo-600 font-bold transition"
          title="Back to Templates Gallery"
        >
          <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          </div>
        </Link>

        <div class="h-6 w-px bg-slate-200"></div>

        <div>
          <input
            v-model="templateName"
            type="text"
            class="font-bold text-sm text-slate-800 border-none hover:bg-slate-50 focus:bg-white rounded px-2 py-1 focus:ring-1 focus:ring-indigo-500 w-52 sm:w-72"
            placeholder="Template Name..."
          />
        </div>
      </div>

      <!-- Center: Undo/Redo & Device Mode Toggle -->
      <div class="flex items-center space-x-4">
        <!-- History Controls -->
        <div class="flex items-center space-x-1 border border-slate-200 rounded-lg p-0.5 bg-slate-50">
          <button
            @click="undo"
            :disabled="historyIndex <= 0"
            class="p-1.5 rounded hover:bg-white text-slate-600 disabled:opacity-30 disabled:hover:bg-transparent"
            title="Undo (Ctrl+Z)"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
          </button>
          <button
            @click="redo"
            :disabled="historyIndex >= history.length - 1"
            class="p-1.5 rounded hover:bg-white text-slate-600 disabled:opacity-30 disabled:hover:bg-transparent"
            title="Redo (Ctrl+Y)"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg>
          </button>
        </div>

        <!-- Viewport Toggle -->
        <div class="hidden sm:flex items-center space-x-1 border border-slate-200 rounded-lg p-0.5 bg-slate-50">
          <button
            @click="previewMode = 'desktop'"
            class="px-2.5 py-1 text-xs font-semibold rounded flex items-center space-x-1.5 transition"
            :class="previewMode === 'desktop' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>Desktop</span>
          </button>
          <button
            @click="previewMode = 'mobile'"
            class="px-2.5 py-1 text-xs font-semibold rounded flex items-center space-x-1.5 transition"
            :class="previewMode === 'mobile' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            <span>Mobile</span>
          </button>
        </div>
      </div>

      <!-- Right: Action Buttons -->
      <div class="flex items-center space-x-2">
        <button
          @click="isSaveModalOpen = true"
          class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg shadow-sm transition"
        >
          Save Template
        </button>

        <button
          @click="openExportModal"
          class="px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm shadow-indigo-200 transition flex items-center space-x-1.5"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
          <span>Export HTML</span>
        </button>
      </div>
    </header>

    <!-- WORKSPACE 3-COLUMN LAYOUT -->
    <div class="flex-1 flex overflow-hidden">
      <!-- 1. LEFT TOOLBAR: BLOCKS -->
      <aside class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Block Elements</span>
          <span class="text-[10px] text-slate-400">Click or Drag</span>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2">
          <div
            v-for="b in availableBlocks"
            :key="b.type"
            @click="addBlock(b)"
            class="flex items-center space-x-3 p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 rounded-lg cursor-pointer transition group shadow-sm active:scale-98"
          >
            <div class="w-8 h-8 rounded-md bg-white border border-slate-200 group-hover:border-indigo-300 flex items-center justify-center text-sm text-indigo-600 font-bold shrink-0">
              {{ b.icon }}
            </div>
            <div>
              <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-900">{{ b.label }}</p>
              <p class="text-[10px] text-slate-400 font-mono">{{ b.type }}</p>
            </div>
          </div>
        </div>

        <!-- Global Settings Quick Trigger -->
        <div class="p-3 border-t border-slate-200 bg-slate-50">
          <button
            @click="selectBlock(null)"
            class="w-full py-2 px-3 text-xs font-bold rounded-lg border text-slate-700 bg-white hover:bg-slate-100 flex items-center justify-center space-x-2 transition"
          >
            <span>🎨 Global Brand Kit</span>
          </button>
        </div>
      </aside>

      <!-- 2. CENTER CANVAS PREVIEW -->
      <main
        class="flex-1 overflow-y-auto p-4 sm:p-8 flex justify-center items-start transition-colors"
        :style="{ backgroundColor: brand.bodyBg }"
        @click="selectBlock(null)"
      >
        <!-- Canvas Frame Container -->
        <div
          class="bg-white shadow-xl rounded-md transition-all duration-200 overflow-hidden my-4"
          :style="{
            width: previewMode === 'mobile' ? '375px' : (brand.canvasWidth || '600px'),
            maxWidth: '100%',
            backgroundColor: brand.canvasBg,
            fontFamily: brand.fontFamily,
          }"
          @click.stop
        >
          <!-- Draggable Block Canvas -->
          <draggable
            v-model="blocks"
            item-key="id"
            handle=".group"
            animation="200"
            ghost-class="opacity-40"
            class="min-h-[300px] flex flex-col"
            @end="pushHistory"
          >
            <template #item="{ element }">
              <CanvasBlock
                :block="element"
                :is-selected="selectedBlockId === element.id"
                :brand="brand"
                @select="selectBlock"
                @duplicate="duplicateBlock"
                @delete="deleteBlock"
              />
            </template>
          </draggable>

          <!-- Empty Canvas Drop Indicator -->
          <div
            v-if="blocks.length === 0"
            class="p-12 text-center border-2 border-dashed border-slate-300 m-6 rounded-lg text-slate-400"
          >
            <p class="text-sm font-semibold">Your canvas is empty</p>
            <p class="text-xs mt-1">Click blocks in the left sidebar to add elements.</p>
          </div>
        </div>
      </main>

      <!-- 3. RIGHT SIDEBAR: PROPERTIES & BRAND KIT -->
      <aside class="w-80 bg-white border-l border-slate-200 shrink-0 flex flex-col">
        <PropertyPanel
          :block="selectedBlock"
          :brand="brand"
          @insert-variable="insertVariableIntoBlock"
        />
      </aside>
    </div>

    <!-- MODAL: EXPORT HTML & ZIP -->
    <div
      v-if="isExportModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
      @click.self="isExportModalOpen = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full flex flex-col overflow-hidden max-h-[90vh]">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <span class="text-lg">📦</span>
            <h3 class="font-extrabold text-slate-900 text-base">Export Ready Email HTML</h3>
          </div>
          <button
            @click="isExportModalOpen = false"
            class="text-slate-400 hover:text-slate-600 p-1 rounded-lg"
          >
            ✕
          </button>
        </div>

        <div class="p-6 overflow-y-auto space-y-4">
          <p class="text-xs text-slate-500">
            This clean, table-based HTML is optimized with inline styles, Outlook MSO tags, and responsive CSS. Paste directly into Mailchimp, Klaviyo, Resend, Brevo, or Laravel Mail.
          </p>

          <!-- Action buttons for export options -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <button
              @click="copyHtmlToClipboard"
              class="p-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl text-center transition"
            >
              <div class="text-sm font-bold text-indigo-700">📋 Copy HTML</div>
              <div class="text-[11px] text-indigo-500 mt-0.5">
                {{ copySuccess ? '✓ Copied to clipboard!' : 'Click to copy' }}
              </div>
            </button>

            <button
              @click="downloadSingleHtml"
              class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-center transition"
            >
              <div class="text-sm font-bold text-slate-700">💾 Download .HTML</div>
              <div class="text-[11px] text-slate-500 mt-0.5">Standalone file</div>
            </button>

            <button
              @click="downloadZip"
              class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-center transition"
            >
              <div class="text-sm font-bold text-slate-700">📦 Download ZIP</div>
              <div class="text-[11px] text-slate-500 mt-0.5">HTML + JSON config</div>
            </button>
          </div>

          <!-- HTML Code preview -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Generated Output</label>
            <div class="relative">
              <textarea
                readonly
                rows="10"
                class="w-full text-xs font-mono bg-slate-900 text-emerald-400 p-3 rounded-lg border border-slate-800 focus:outline-none"
                :value="exportHtmlOutput"
              ></textarea>
            </div>
          </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
          <button
            @click="isExportModalOpen = false"
            class="px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL: SAVE TEMPLATE -->
    <div
      v-if="isSaveModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
      @click.self="isSaveModalOpen = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-lg">Save Email Template</h3>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Template Name</label>
          <input
            v-model="templateName"
            type="text"
            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
          <input
            v-model="category"
            type="text"
            placeholder="e.g. Marketing, Onboarding, Billing"
            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg"
          />
        </div>

        <div v-if="!auth.user" class="p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-lg">
          ⚠️ You are currently using guest mode. Template will be saved locally or in the public session. <Link :href="route('login')" class="underline font-bold">Sign in</Link> to permanently attach templates to your account.
        </div>

        <div class="pt-2 flex justify-end space-x-2">
          <button
            @click="isSaveModalOpen = false"
            class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg"
          >
            Cancel
          </button>
          <button
            @click="saveTemplateToServer"
            class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm"
          >
            Confirm Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
