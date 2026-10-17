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
const mobileTab = ref('canvas'); // 'canvas' | 'blocks' | 'settings'
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
    label: 'Brand Logo',
    icon: '🏷️',
    defaultProps: { src: 'https://dummyimage.com/160x40/4f46e5/ffffff&text=YOUR+LOGO', alt: 'Company Logo', align: 'center', width: '160px', maxWidth: '160px', borderRadius: '0px', padding: '24px 24px 12px 24px' },
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
  mobileTab.value = 'canvas';
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
      if (res.data.template?.slug) {
        window.location.href = `/editor/${res.data.template.slug}`;
      }
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
    <header class="h-14 bg-white border-b border-slate-200 px-2.5 sm:px-4 flex items-center justify-between z-30 shrink-0">
      <!-- Left: Logo & Template Title -->
      <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
        <Link
          :href="route('templates.index')"
          class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition shrink-0 shadow-xs"
          title="Back to Templates Gallery"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </Link>

        <div class="h-5 w-px bg-slate-200 hidden xs:block"></div>

        <div class="flex items-center">
          <input
            v-model="templateName"
            type="text"
            class="font-bold text-xs sm:text-sm text-slate-800 border-none hover:bg-slate-50 focus:bg-white rounded px-1.5 py-1 focus:ring-1 focus:ring-indigo-500 w-24 xs:w-36 sm:w-56 truncate"
            placeholder="Template Name..."
          />
        </div>
      </div>

      <!-- Center: Undo/Redo & Viewport Mode -->
      <div class="flex items-center space-x-2">
        <!-- History Controls -->
        <div class="flex items-center space-x-0.5 border border-slate-200 rounded-lg p-0.5 bg-slate-50 shrink-0">
          <button
            @click="undo"
            :disabled="historyIndex <= 0"
            class="p-1 sm:p-1.5 rounded hover:bg-white text-slate-600 disabled:opacity-30 disabled:hover:bg-transparent"
            title="Undo (Ctrl+Z)"
          >
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
          </button>
          <button
            @click="redo"
            :disabled="historyIndex >= history.length - 1"
            class="p-1 sm:p-1.5 rounded hover:bg-white text-slate-600 disabled:opacity-30 disabled:hover:bg-transparent"
            title="Redo (Ctrl+Y)"
          >
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg>
          </button>
        </div>

        <!-- Viewport Toggle (Desktop only) -->
        <div class="hidden md:flex items-center space-x-1 border border-slate-200 rounded-lg p-0.5 bg-slate-50 shrink-0">
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
      <div class="flex items-center space-x-1.5 sm:space-x-2 shrink-0">
        <button
          @click="isSaveModalOpen = true"
          class="h-8 sm:h-9 px-2 sm:px-3 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg shadow-xs transition flex items-center justify-center whitespace-nowrap"
        >
          <span>Save</span>
        </button>

        <button
          @click="openExportModal"
          class="h-8 sm:h-9 px-2.5 sm:px-3.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs shadow-indigo-100 transition flex items-center justify-center space-x-1 whitespace-nowrap"
        >
          <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
          <span class="hidden xs:inline">Export HTML</span>
          <span class="xs:hidden">Export</span>
        </button>
      </div>
    </header>

    <!-- MOBILE NAVIGATION TAB BAR (< lg) -->
    <div class="lg:hidden bg-slate-100/90 backdrop-blur border-b border-slate-200 px-2 py-1.5 flex items-center justify-between shrink-0 z-20">
      <div class="grid grid-cols-3 gap-1.5 w-full">
        <button
          @click="mobileTab = 'canvas'"
          class="py-1.5 px-2 text-xs font-bold rounded-lg flex items-center justify-center space-x-1 transition"
          :class="mobileTab === 'canvas' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
        >
          <span>📱 Canvas</span>
        </button>
        <button
          @click="mobileTab = 'blocks'"
          class="py-1.5 px-2 text-xs font-bold rounded-lg flex items-center justify-center space-x-1 transition"
          :class="mobileTab === 'blocks' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
        >
          <span>➕ Elements</span>
        </button>
        <button
          @click="mobileTab = 'settings'"
          class="py-1.5 px-2 text-xs font-bold rounded-lg flex items-center justify-center space-x-1 transition"
          :class="mobileTab === 'settings' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
        >
          <span>⚙️ {{ selectedBlock ? 'Edit' : 'Brand' }}</span>
        </button>
      </div>
    </div>

    <!-- WORKSPACE 3-COLUMN / TABBED LAYOUT -->
    <div class="flex-1 flex overflow-hidden relative">
      <!-- 1. LEFT TOOLBAR: BLOCKS -->
      <aside
        class="w-full lg:w-64 bg-white border-r border-slate-200 flex flex-col shrink-0"
        :class="{ 'hidden lg:flex': mobileTab !== 'blocks' }"
      >
        <div class="p-3 sm:p-4 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Block Elements</span>
          <span class="text-[10px] text-slate-400">Tap or Drag to Add</span>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2">
          <div
            v-for="b in availableBlocks"
            :key="b.type"
            @click="addBlock(b)"
            class="flex items-center space-x-3 p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 rounded-lg cursor-pointer transition group shadow-xs active:scale-98"
          >
            <div class="w-8 h-8 rounded-md bg-white border border-slate-200 group-hover:border-indigo-300 flex items-center justify-center text-sm text-indigo-600 font-bold shrink-0">
              {{ b.icon }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-900 truncate">{{ b.label }}</p>
              <p class="text-[10px] text-slate-400 font-mono">{{ b.type }}</p>
            </div>
            <span class="text-[11px] text-indigo-600 font-bold lg:hidden shrink-0 bg-white px-2 py-1 rounded border border-indigo-100 shadow-xs">+ Add</span>
          </div>
        </div>

        <!-- Global Settings Quick Trigger -->
        <div class="p-3 border-t border-slate-200 bg-slate-50">
          <button
            @click="selectBlock(null); mobileTab = 'settings'"
            class="w-full py-2 px-3 text-xs font-bold rounded-lg border text-slate-700 bg-white hover:bg-slate-100 flex items-center justify-center space-x-2 transition shadow-xs"
          >
            <span>🎨 Global Brand Kit</span>
          </button>
        </div>
      </aside>

      <!-- 2. CENTER CANVAS PREVIEW -->
      <main
        class="flex-1 overflow-y-auto p-2 sm:p-6 lg:p-8 flex justify-center items-start transition-colors relative"
        :class="{ 'hidden lg:flex': mobileTab !== 'canvas' }"
        :style="{ backgroundColor: brand.bodyBg }"
        @click="selectBlock(null)"
      >
        <!-- Canvas Frame Container -->
        <div
          class="bg-white shadow-xl rounded-md transition-all duration-200 overflow-hidden my-2 sm:my-4 w-full"
          :style="{
            width: previewMode === 'mobile' ? '375px' : (brand.canvasWidth || '600px'),
            maxWidth: brand.canvasWidth || '600px',
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
            class="p-8 sm:p-12 text-center border-2 border-dashed border-slate-300 m-4 sm:m-6 rounded-lg text-slate-400"
          >
            <p class="text-sm font-semibold">Your canvas is empty</p>
            <p class="text-xs mt-1">Add elements to design your email.</p>
            <button
              @click="mobileTab = 'blocks'"
              class="mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center space-x-1.5"
            >
              <span>➕ Add Elements</span>
            </button>
          </div>
        </div>

        <!-- Mobile Floating Pill when block is selected -->
        <div
          v-if="selectedBlock"
          class="lg:hidden fixed bottom-4 inset-x-3 sm:inset-x-6 bg-slate-900/95 backdrop-blur-md text-white rounded-xl shadow-2xl p-2.5 sm:p-3 flex items-center justify-between z-30 border border-slate-700 animate-in fade-in slide-in-from-bottom-2 duration-150"
          @click.stop
        >
          <div class="flex items-center space-x-2 truncate">
            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 bg-indigo-500 rounded text-white shrink-0">
              {{ selectedBlock.type }}
            </span>
            <span class="text-xs text-slate-200 truncate font-medium">Selected</span>
          </div>
          <div class="flex items-center space-x-2 shrink-0">
            <button
              @click="mobileTab = 'settings'"
              class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center space-x-1"
            >
              <span>⚙️ Edit</span>
            </button>
            <button
              @click="duplicateBlock(selectedBlock)"
              class="p-1 text-slate-300 hover:text-white"
              title="Duplicate"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </button>
            <button
              @click="deleteBlock(selectedBlock.id)"
              class="p-1 text-red-400 hover:text-red-300"
              title="Delete"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
            <button
              @click="selectedBlockId = null"
              class="p-1 text-slate-400 hover:text-white"
              title="Deselect"
            >
              ✕
            </button>
          </div>
        </div>
      </main>

      <!-- 3. RIGHT SIDEBAR: PROPERTIES & BRAND KIT -->
      <aside
        class="w-full lg:w-80 bg-white border-l border-slate-200 shrink-0 flex flex-col"
        :class="{ 'hidden lg:flex': mobileTab !== 'settings' }"
      >
        <!-- Mobile Top Back to Canvas button -->
        <div class="lg:hidden p-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
          <button
            @click="mobileTab = 'canvas'"
            class="flex items-center space-x-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800"
          >
            <span>← Back to Canvas</span>
          </button>
          <span class="text-xs text-slate-400 font-medium">Done editing? Tap back</span>
        </div>

        <div class="flex-1 overflow-hidden">
          <PropertyPanel
            :block="selectedBlock"
            :brand="brand"
            @insert-variable="insertVariableIntoBlock"
          />
        </div>
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

        <div v-if="!auth.user" class="p-3.5 bg-amber-50 border border-amber-200 text-amber-900 text-xs rounded-xl space-y-2">
          <p class="font-medium">⚠️ <strong>Guest Mode:</strong> To permanently organize and manage your custom templates across devices, please Sign Up or Sign In.</p>
          <div class="flex items-center space-x-2 pt-1">
            <Link :href="route('register')" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded font-bold transition">
              Sign Up
            </Link>
            <Link :href="route('login')" class="px-2.5 py-1 bg-white hover:bg-amber-100 border border-amber-300 text-amber-800 rounded font-semibold transition">
              Sign In
            </Link>
          </div>
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
