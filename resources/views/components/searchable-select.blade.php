@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Pilih pilihan...',
    'searchPlaceholder' => 'Ketik untuk mencari...',
    'required' => false,
    'disabled' => false,
    'allowClear' => true,
])

@php
    $id = $id ?? $name;
    $selectedVal = old($name, $value);
    
    // Normalize options into array of ['value' => ..., 'label' => ...]
    $normalizedOptions = [];
    foreach ($options as $k => $v) {
        if (is_array($v) || is_object($v)) {
            $val = is_object($v) ? ($v->id ?? $v->value ?? '') : ($v['id'] ?? $v['value'] ?? '');
            $lbl = is_object($v) ? ($v->nama ?? $v->name ?? $v->label ?? $val) : ($v['nama'] ?? $v['name'] ?? $v['label'] ?? $val);
            $sub = is_object($v) ? ($v->keterangan ?? $v->sublabel ?? '') : ($v['keterangan'] ?? $v['sublabel'] ?? '');
        } else {
            $val = $k;
            $lbl = $v;
            $sub = '';
        }
        $normalizedOptions[] = [
            'value' => (string)$val,
            'label' => (string)$lbl,
            'sublabel' => (string)$sub,
        ];
    }
@endphp

<div x-data="{
    open: false,
    search: '',
    selected: '{{ (string)$selectedVal }}',
    options: {{ json_encode($normalizedOptions) }},
    focusedIndex: -1,
    get filteredOptions() {
        if (!this.search) return this.options;
        const q = this.search.toLowerCase();
        return this.options.filter(o => 
            o.label.toLowerCase().includes(q) || 
            (o.sublabel && o.sublabel.toLowerCase().includes(q)) ||
            o.value.toLowerCase().includes(q)
        );
    },
    get selectedLabel() {
        const found = this.options.find(o => String(o.value) === String(this.selected));
        return found ? found.label : '';
    },
    select(val) {
        this.selected = val;
        this.open = false;
        this.search = '';
        this.focusedIndex = -1;
        $nextTick(() => {
            $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            $refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        });
    },
    clear() {
        this.selected = '';
        this.search = '';
        $nextTick(() => {
            $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            $refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        });
    },
    focusNext() {
        if (this.focusedIndex < this.filteredOptions.length - 1) {
            this.focusedIndex++;
            this.scrollToFocused();
        }
    },
    focusPrev() {
        if (this.focusedIndex > 0) {
            this.focusedIndex--;
            this.scrollToFocused();
        }
    },
    selectFocused() {
        if (this.focusedIndex >= 0 && this.focusedIndex < this.filteredOptions.length) {
            this.select(this.filteredOptions[this.focusedIndex].value);
        }
    },
    scrollToFocused() {
        $nextTick(() => {
            const el = document.getElementById('opt-' + this.name + '-' + this.focusedIndex);
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    }
}" 
@click.outside="open = false; search = ''; focusedIndex = -1;" 
class="relative w-full">

    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-slate-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    {{-- Hidden Native Form Input --}}
    <input type="hidden" 
           name="{{ $name }}" 
           id="{{ $id }}" 
           x-ref="hiddenInput"
           x-model="selected" 
           @if($required) required @endif>

    {{-- Trigger Box --}}
    <div @click="if(!{{ $disabled ? 'true' : 'false' }}) { open = !open; if(open) $nextTick(() => $refs.searchInput.focus()); }"
         class="flex items-center justify-between w-full min-h-[42px] px-3.5 py-2 text-sm bg-white border rounded-lg cursor-pointer transition-all shadow-xs select-none"
         :class="{
             'border-indigo-600 ring-2 ring-indigo-100': open,
             'border-slate-300 hover:border-slate-400': !open,
             'bg-slate-50 cursor-not-allowed opacity-75': {{ $disabled ? 'true' : 'false' }}
         }">
        
        <div class="flex-1 truncate">
            <template x-if="selectedLabel">
                <span class="font-medium text-slate-900" x-text="selectedLabel"></span>
            </template>
            <template x-if="!selectedLabel">
                <span class="text-slate-400">{{ $placeholder }}</span>
            </template>
        </div>

        <div class="flex items-center gap-1.5 shrink-0 ml-2">
            @if($allowClear)
                <button type="button" 
                        x-show="selected" 
                        @click.stop="clear()" 
                        class="p-0.5 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors" 
                        title="Hapus Pilihan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            @endif
            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    {{-- Dropdown Container --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" 
         style="display: none;">

        {{-- Search Input Inside Dropdown --}}
        <div class="p-2 border-b border-slate-100 bg-slate-50/70">
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       x-ref="searchInput"
                       x-model="search"
                       @keydown.arrow-down.prevent="focusNext()"
                       @keydown.arrow-up.prevent="focusPrev()"
                       @keydown.enter.prevent="selectFocused()"
                       @keydown.escape.prevent="open = false"
                       placeholder="{{ $searchPlaceholder }}"
                       class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
            </div>
        </div>

        {{-- Options List --}}
        <div class="max-h-60 overflow-y-auto p-1 text-sm space-y-0.5" role="listbox">
            <template x-for="(opt, idx) in filteredOptions" :key="opt.value">
                <div :id="'opt-{{ $name }}-' + idx"
                     @click="select(opt.value)"
                     @mouseenter="focusedIndex = idx"
                     class="flex items-center justify-between px-3 py-2 rounded-lg cursor-pointer transition-colors select-none"
                     :class="{
                         'bg-indigo-50 text-indigo-700 font-semibold': String(opt.value) === String(selected),
                         'bg-slate-100 text-slate-900': focusedIndex === idx && String(opt.value) !== String(selected),
                         'text-slate-700 hover:bg-slate-50': focusedIndex !== idx && String(opt.value) !== String(selected)
                     }">
                    <div>
                        <span x-text="opt.label"></span>
                        <template x-if="opt.sublabel">
                            <span class="block text-[11px] text-slate-400 font-normal" x-text="opt.sublabel"></span>
                        </template>
                    </div>
                    <template x-if="String(opt.value) === String(selected)">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="filteredOptions.length === 0">
                <div class="px-3 py-4 text-center text-xs text-slate-400 italic">
                    <span>Pilihan tidak ditemukan</span>
                </div>
            </template>
        </div>
    </div>
</div>
