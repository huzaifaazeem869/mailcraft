<script setup>
import { computed } from 'vue';

const props = defineProps({
  block: {
    type: Object,
    required: true,
  },
  isSelected: {
    type: Boolean,
    default: false,
  },
  brand: {
    type: Object,
    default: () => ({}),
  },
});

defineEmits(['select', 'delete', 'duplicate']);
</script>

<template>
  <div
    class="relative group cursor-pointer transition-all duration-150"
    :class="[
      isSelected ? 'ring-2 ring-indigo-500 shadow-md' : 'hover:ring-1 hover:ring-indigo-300'
    ]"
    @click.stop="$emit('select', block)"
  >
    <!-- Block Actions Toolbar -->
    <div
      v-if="isSelected"
      class="absolute -top-7 right-2 flex items-center space-x-1 bg-indigo-600 text-white text-xs px-2 py-1 rounded shadow-lg z-20"
      @click.stop
    >
      <span class="font-semibold uppercase tracking-wider text-[10px] pr-1.5 border-r border-indigo-400">
        {{ block.type }}
      </span>
      <button
        @click.stop="$emit('duplicate', block)"
        class="hover:text-indigo-200 px-1"
        title="Duplicate Block"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
      </button>
      <button
        @click.stop="$emit('delete', block.id)"
        class="hover:text-red-300 px-1"
        title="Delete Block"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
      </button>
    </div>

    <!-- Block Visual Rendering -->
    <div :style="{ backgroundColor: block.props.bgColor || 'transparent' }">
      <!-- HEADING -->
      <div
        v-if="block.type === 'heading'"
        :style="{
          padding: block.props.padding || '16px 24px',
          textAlign: block.props.align || 'left',
          color: block.props.color || brand.textColor || '#111827',
          fontWeight: block.props.fontWeight || 'bold',
        }"
        class="leading-tight"
      >
        <component
          :is="block.props.level || 'h1'"
          :class="{
            'text-2xl font-bold': block.props.level === 'h1',
            'text-xl font-bold': block.props.level === 'h2',
            'text-lg font-semibold': block.props.level === 'h3',
          }"
        >
          {{ block.props.text || 'Heading text here' }}
        </component>
      </div>

      <!-- TEXT -->
      <div
        v-else-if="block.type === 'text'"
        :style="{
          padding: block.props.padding || '16px 24px',
          textAlign: block.props.align || 'left',
          color: block.props.color || brand.textColor || '#374151',
          fontSize: block.props.fontSize || '16px',
          lineHeight: block.props.lineHeight || '1.6',
        }"
        v-html="block.props.content || '<p>Text paragraph...</p>'"
      ></div>

      <!-- BUTTON -->
      <div
        v-else-if="block.type === 'button'"
        :style="{
          padding: block.props.padding || '16px 24px',
          textAlign: block.props.align || 'center',
        }"
      >
        <span
          class="inline-block transition-transform"
          :style="{
            backgroundColor: block.props.bgColor || brand.primaryColor || '#4f46e5',
            color: block.props.textColor || '#ffffff',
            borderRadius: block.props.borderRadius || brand.buttonRadius || '6px',
            padding: `${block.props.paddingY || '12px'} ${block.props.paddingX || '24px'}`,
            fontSize: block.props.fontSize || '15px',
            fontWeight: block.props.fontWeight || 'bold',
          }"
        >
          {{ block.props.text || 'Click Here' }}
        </span>
      </div>

      <!-- IMAGE -->
      <div
        v-else-if="block.type === 'image'"
        :style="{
          padding: block.props.padding || '16px 24px',
          textAlign: block.props.align || 'center',
        }"
      >
        <img
          :src="block.props.src || 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=800&auto=format&fit=crop'"
          :alt="block.props.alt || 'Image'"
          class="inline-block max-w-full h-auto object-cover"
          :style="{
            width: block.props.width || '100%',
            maxWidth: block.props.maxWidth || '100%',
            borderRadius: block.props.borderRadius || '0px',
          }"
        />
      </div>

      <!-- DIVIDER -->
      <div
        v-else-if="block.type === 'divider'"
        :style="{ padding: block.props.padding || '16px 24px' }"
      >
        <hr
          :style="{
            borderColor: block.props.color || '#e5e7eb',
            borderTopWidth: block.props.height || '1px',
            borderStyle: block.props.style || 'solid',
          }"
        />
      </div>

      <!-- SPACER -->
      <div
        v-else-if="block.type === 'spacer'"
        :style="{ height: block.props.height || '24px' }"
        class="bg-stripes flex items-center justify-center text-[10px] text-gray-400 select-none opacity-50 hover:opacity-100"
      >
        ↕ {{ block.props.height || '24px' }}
      </div>

      <!-- SOCIAL -->
      <div
        v-else-if="block.type === 'social'"
        :style="{
          padding: block.props.padding || '16px 24px',
          textAlign: block.props.align || 'center',
        }"
      >
        <div class="inline-flex items-center space-x-3">
          <span
            v-for="(item, idx) in (block.props.items || [])"
            :key="idx"
            class="inline-block p-1 bg-gray-50 rounded"
          >
            <img :src="item.icon" :alt="item.platform" class="w-6 h-6 inline-block" />
          </span>
        </div>
      </div>

      <!-- FOOTER -->
      <div
        v-else-if="block.type === 'footer'"
        :style="{
          padding: block.props.padding || '24px',
          textAlign: 'center',
          color: block.props.color || '#9ca3af',
          fontSize: block.props.fontSize || '12px',
        }"
        class="space-y-1"
      >
        <p class="font-medium">&copy; {{ brand.company || 'Company Name' }}. All rights reserved.</p>
        <p>{{ block.props.address || '123 Business Street, Suite 100, City, Country' }}</p>
        <p class="underline text-indigo-400">
          {{ block.props.unsubscribeText || 'Unsubscribe' }}
        </p>
      </div>

      <!-- COLUMNS (2 or 3) -->
      <div
        v-else-if="block.type === 'columns'"
        :style="{ padding: block.props.padding || '16px' }"
        class="grid gap-4"
        :class="{
          'grid-cols-2': (block.props.count || 2) == 2,
          'grid-cols-3': (block.props.count || 2) == 3,
        }"
      >
        <div
          v-for="idx in (block.props.count || 2)"
          :key="idx"
          class="p-4 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50/50 text-center text-xs text-gray-500"
        >
          Column {{ idx }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-stripes {
  background-image: repeating-linear-gradient(
    45deg,
    #f3f4f6,
    #f3f4f6 10px,
    #ffffff 10px,
    #ffffff 20px
  );
}
</style>
