<script setup>
defineProps({
  block: {
    type: Object,
    default: null,
  },
  brand: {
    type: Object,
    required: true,
  },
});

defineEmits(['update:block', 'update:brand', 'insertVariable']);

const mergeTags = [
  { label: 'Recipient Name', tag: '{{name}}' },
  { label: 'Recipient Email', tag: '{{email}}' },
  { label: 'Company Name', tag: '{{company}}' },
  { label: 'Order ID', tag: '{{order_id}}' },
  { label: 'Unsubscribe URL', tag: '{{unsubscribe_url}}' },
  { label: 'Current Year', tag: '{{current_year}}' },
];
</script>

<template>
  <div class="h-full flex flex-col bg-white border-l border-gray-200">
    <!-- Header -->
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
        {{ block ? `${block.type} Settings` : 'Global Brand Kit' }}
      </h3>
      <span v-if="block" class="text-xs px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded font-mono">
        #{{ block.id }}
      </span>
    </div>

    <!-- Scrollable Settings Body -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6 text-sm">
      <!-- 1. MERGE TAGS QUICK PICKER -->
      <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
        <label class="block text-xs font-semibold text-gray-600 mb-2">⚡ Quick Insert Variable</label>
        <div class="flex flex-wrap gap-1.5">
          <button
            v-for="tag in mergeTags"
            :key="tag.tag"
            type="button"
            @click="$emit('insertVariable', tag.tag)"
            class="px-2 py-1 text-xs bg-white hover:bg-indigo-50 border border-gray-300 hover:border-indigo-400 text-gray-700 rounded transition font-mono shadow-sm"
            :title="`Click to insert ${tag.tag}`"
          >
            {{ tag.tag }}
          </button>
        </div>
      </div>

      <!-- 2. SELECTED BLOCK PROPERTIES -->
      <div v-if="block" class="space-y-4">
        <!-- HEADING BLOCK -->
        <template v-if="block.type === 'heading'">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Heading Text</label>
            <input
              v-model="block.props.text"
              type="text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Heading Level</label>
              <select
                v-model="block.props.level"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              >
                <option value="h1">H1 (Large)</option>
                <option value="h2">H2 (Medium)</option>
                <option value="h3">H3 (Small)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Alignment</label>
              <select
                v-model="block.props.align"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              >
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Text Color</label>
            <div class="flex items-center space-x-2">
              <input v-model="block.props.color" type="color" class="h-8 w-12 border border-gray-300 rounded p-1 cursor-pointer" />
              <input v-model="block.props.color" type="text" class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-md" />
            </div>
          </div>
        </template>

        <!-- TEXT BLOCK -->
        <template v-else-if="block.type === 'text'">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Content (HTML Supported)</label>
            <textarea
              v-model="block.props.content"
              rows="6"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md font-mono focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Alignment</label>
              <select
                v-model="block.props.align"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              >
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Font Size</label>
              <input
                v-model="block.props.fontSize"
                type="text"
                placeholder="16px"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              />
            </div>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Text Color</label>
            <div class="flex items-center space-x-2">
              <input v-model="block.props.color" type="color" class="h-8 w-12 border border-gray-300 rounded p-1 cursor-pointer" />
              <input v-model="block.props.color" type="text" class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-md" />
            </div>
          </div>
        </template>

        <!-- BUTTON BLOCK -->
        <template v-else-if="block.type === 'button'">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Button Label</label>
            <input
              v-model="block.props.text"
              type="text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Target URL</label>
            <input
              v-model="block.props.url"
              type="text"
              placeholder="https://example.com"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Button Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="block.props.bgColor" type="color" class="h-8 w-10 border rounded p-0.5 cursor-pointer" />
                <input v-model="block.props.bgColor" type="text" class="w-full px-2 py-1 text-xs border rounded" />
              </div>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Text Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="block.props.textColor" type="color" class="h-8 w-10 border rounded p-0.5 cursor-pointer" />
                <input v-model="block.props.textColor" type="text" class="w-full px-2 py-1 text-xs border rounded" />
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Corner Radius</label>
              <input
                v-model="block.props.borderRadius"
                type="text"
                placeholder="6px"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Alignment</label>
              <select
                v-model="block.props.align"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              >
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
          </div>
        </template>

        <!-- IMAGE BLOCK -->
        <template v-else-if="block.type === 'image'">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Image URL</label>
            <input
              v-model="block.props.src"
              type="text"
              placeholder="https://..."
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Alt Text</label>
            <input
              v-model="block.props.alt"
              type="text"
              placeholder="Descriptive text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Max Width</label>
              <input
                v-model="block.props.maxWidth"
                type="text"
                placeholder="600px or 100%"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Alignment</label>
              <select
                v-model="block.props.align"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              >
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Link URL (Optional)</label>
            <input
              v-model="block.props.url"
              type="text"
              placeholder="https://example.com"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
        </template>

        <!-- DIVIDER & SPACER -->
        <template v-else-if="block.type === 'divider'">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Line Color</label>
              <input v-model="block.props.color" type="color" class="h-8 w-full border rounded p-1 cursor-pointer" />
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1">Line Height</label>
              <input v-model="block.props.height" type="text" placeholder="1px" class="w-full px-3 py-1.5 text-sm border rounded-md" />
            </div>
          </div>
        </template>

        <template v-else-if="block.type === 'spacer'">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Height</label>
            <input
              v-model="block.props.height"
              type="text"
              placeholder="24px"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
        </template>

        <!-- COMMON BLOCK PADDING & BG -->
        <div class="pt-4 border-t border-gray-200">
          <label class="block text-xs font-semibold text-gray-700 mb-2">Block Spacing & Background</label>
          <div class="space-y-3">
            <div>
              <label class="block text-xs text-gray-500 mb-1">Padding (e.g. 16px 24px)</label>
              <input
                v-model="block.props.padding"
                type="text"
                placeholder="16px 24px"
                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">Background Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="block.props.bgColor" type="color" class="h-8 w-10 border rounded p-0.5 cursor-pointer" />
                <input v-model="block.props.bgColor" type="text" placeholder="transparent" class="flex-1 px-3 py-1.5 text-xs border rounded-md" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. BRAND KIT / GLOBAL SETTINGS (Shown when no block is selected or at the bottom) -->
      <div v-else class="space-y-4">
        <div class="p-3 bg-blue-50 text-blue-800 rounded-md text-xs">
          💡 Click any block on the canvas to configure its specific styling, or adjust your overall brand theme below.
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Company / Project Name</label>
          <input
            v-model="brand.company"
            type="text"
            placeholder="Acme Corp"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Primary Brand Color</label>
          <div class="flex items-center space-x-2">
            <input v-model="brand.primaryColor" type="color" class="h-8 w-12 border rounded p-1 cursor-pointer" />
            <input v-model="brand.primaryColor" type="text" class="flex-1 px-3 py-1.5 text-sm border rounded-md" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Content Background Color</label>
          <div class="flex items-center space-x-2">
            <input v-model="brand.canvasBg" type="color" class="h-8 w-12 border rounded p-1 cursor-pointer" />
            <input v-model="brand.canvasBg" type="text" class="flex-1 px-3 py-1.5 text-sm border rounded-md" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Outer Email Background</label>
          <div class="flex items-center space-x-2">
            <input v-model="brand.bodyBg" type="color" class="h-8 w-12 border rounded p-1 cursor-pointer" />
            <input v-model="brand.bodyBg" type="text" class="flex-1 px-3 py-1.5 text-sm border rounded-md" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Font Family</label>
          <select
            v-model="brand.fontFamily"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
          >
            <option value="Arial, Helvetica, sans-serif">Arial / Helvetica (Default Clean)</option>
            <option value="'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">Segoe UI / Modern</option>
            <option value="Georgia, Times, 'Times New Roman', serif">Georgia / Editorial Serif</option>
            <option value="'Courier New', Courier, monospace">Courier / Monospace</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Canvas Width</label>
          <select
            v-model="brand.canvasWidth"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
          >
            <option value="540px">540px (Compact Notice)</option>
            <option value="600px">600px (Standard Email Width)</option>
            <option value="640px">640px (Wide Editorial)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Global Button Radius</label>
          <input
            v-model="brand.buttonRadius"
            type="text"
            placeholder="6px"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
          />
        </div>
      </div>
    </div>
  </div>
</template>
