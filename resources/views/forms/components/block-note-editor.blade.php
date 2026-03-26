@php
    $statePath = $getStatePath();
    $showFullscreenButton = $field->getHasFullscreenButton();
@endphp

@once
    @php
        $package = 'weave-php/blocknote';
    @endphp
    <link rel="stylesheet" href="{{ asset('fonts/filament/filament/inter/index.css') }}">
    <link rel="stylesheet" href="{{ \Filament\Support\Facades\FilamentAsset::getStyleHref('blocknote-editor', $package) }}">
    <script src="{{ \Filament\Support\Facades\FilamentAsset::getScriptSrc('blocknote-editor', $package) }}" data-navigate-track data-navigate-once></script>
@endonce

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            editor: null,
            isFullscreen: false,
            async toggleFullscreen() {
                const el = this.$refs.fullscreenShell;
                if (!el) {
                    return;
                }
                try {
                    if (!document.fullscreenElement) {
                        if (el.requestFullscreen) {
                            await el.requestFullscreen();
                        } else if (el.webkitRequestFullscreen) {
                            el.webkitRequestFullscreen();
                        }
                    } else if (document.exitFullscreen) {
                        await document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                } catch (e) {
                    console.error(e);
                }
            },
        }"
        x-init="
            document.addEventListener('fullscreenchange', () => {
                isFullscreen = document.fullscreenElement === $refs.fullscreenShell;
            });
            document.addEventListener('webkitfullscreenchange', () => {
                isFullscreen = document.fullscreenElement === $refs.fullscreenShell;
            });
            $nextTick(() => {
                isFullscreen = document.fullscreenElement === $refs.fullscreenShell;
            });
            if (!window.WeaveBlockNote) {
                return;
            }

            window.WeaveBlockNote.mount($refs.editor, {
                minHeight: '{{ $getMinHeight() }}',
                getState: () => this.state,
                setState: (value) => {
                    this.state = value;
                },
                onReady: (editor) => {
                    this.editor = editor;
                },
            });
        "
    >
        <input type="hidden" x-model="state" wire:model="{{ $statePath }}" />

        <div
            x-ref="fullscreenShell"
            class="weave-blocknote-fs-host"
        >
            @if ($showFullscreenButton)
                <button
                    type="button"
                    @click="toggleFullscreen()"
                    class="weave-blocknote-fs-btn"
                    :aria-label="isFullscreen ? '{{ __('weave-blocknote::editor.exit_fullscreen') }}' : '{{ __('weave-blocknote::editor.enter_fullscreen') }}'"
                >
                    @include('weave-blocknote::components.icons.fullscreen-enter')
                    @include('weave-blocknote::components.icons.fullscreen-exit')
                </button>
            @endif

            <div
                x-ref="editor"
                wire:ignore
                @class([
                    'weave-blocknote-mount',
                    'weave-blocknote-mount--fill' => $showFullscreenButton,
                ])
            ></div>
        </div>
    </div>
</x-dynamic-component>
