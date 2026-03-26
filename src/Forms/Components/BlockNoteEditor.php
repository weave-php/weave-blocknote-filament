<?php

namespace Weave\BlockNote\Forms\Components;

use Filament\Forms\Components\Field;
use InvalidArgumentException;

class BlockNoteEditor extends Field
{
    /**
     * BlockNote default block type keys (see BlockNote embeds / built-in blocks).
     *
     * @var list<string>
     */
    public const BLOCK_TYPES = [
        'audio',
        'bulletListItem',
        'checkListItem',
        'codeBlock',
        'divider',
        'file',
        'heading',
        'image',
        'numberedListItem',
        'paragraph',
        'quote',
        'table',
        'toggleListItem',
        'video',
    ];

    protected string $view = 'weave-blocknote::forms.components.block-note-editor';

    protected string $minHeight = '320px';

    protected bool $hasFullscreenButton = false;

    protected ?string $localeOverride = null;

    protected bool $uploadEnabled = true;

    protected ?string $uploadUrl = null;

    protected string $uploadFieldName = 'file';

    protected string $uploadResponseUrlKey = 'url';

    /**
     * @var list<string>|null
     */
    protected ?array $blocks = null;

    /**
     * @var list<string>|null
     */
    protected ?array $disabledBlocks = null;

    public function fullscreenButton(bool $enabled = true): static
    {
        $this->hasFullscreenButton = $enabled;

        return $this;
    }

    public function getHasFullscreenButton(): bool
    {
        return $this->hasFullscreenButton;
    }

    public function minHeight(int|string $height): static
    {
        $this->minHeight = is_int($height) ? "{$height}px" : $height;

        return $this;
    }

    public function getMinHeight(): string
    {
        return $this->minHeight;
    }

    /**
     * Force BlockNote UI language (e.g. `fr`, `en`, `zh-tw`). When omitted, the application locale is used.
     */
    public function locale(?string $locale): static
    {
        $this->localeOverride = $locale;

        return $this;
    }

    public function getLocaleOverride(): ?string
    {
        return $this->localeOverride;
    }

    /**
     * Disable BlockNote file uploads (no upload tab, no uploadFile handler).
     */
    public function disableUpload(bool $disabled = true): static
    {
        $this->uploadEnabled = ! $disabled;

        return $this;
    }

    public function getUploadEnabled(): bool
    {
        return $this->uploadEnabled;
    }

    /**
     * Override the upload endpoint (defaults to the package route `weave-blocknote.upload`).
     */
    public function uploadUrl(?string $url): static
    {
        $this->uploadUrl = $url;

        return $this;
    }

    public function getUploadUrl(): ?string
    {
        return $this->uploadUrl;
    }

    /**
     * URL passed to the editor: custom {@see uploadUrl()}, else the package route when uploads are enabled.
     */
    public function getResolvedUploadUrl(): ?string
    {
        if (! $this->uploadEnabled) {
            return null;
        }

        if ($this->uploadUrl !== null && $this->uploadUrl !== '') {
            return $this->uploadUrl;
        }

        if (! config('weave-blocknote.uploads.enabled', true)) {
            return null;
        }

        try {
            return route('weave-blocknote.upload', absolute: true);
        } catch (\Throwable) {
            return null;
        }
    }

    public function uploadFieldName(string $name): static
    {
        $this->uploadFieldName = $name;

        return $this;
    }

    public function getUploadFieldName(): string
    {
        return $this->uploadFieldName;
    }

    /**
     * Dot path to the file URL in the JSON upload response (e.g. `url`, `data.url`, `location`).
     */
    public function uploadResponseUrlKey(string $key): static
    {
        $this->uploadResponseUrlKey = $key;

        return $this;
    }

    public function getUploadResponseUrlKey(): string
    {
        return $this->uploadResponseUrlKey;
    }

    /**
     * Only these BlockNote block types (whitelist). Implies {@see withoutBlocks()} is ignored.
     *
     * @param  list<string>  $blocks
     */
    public function blocks(array $blocks): static
    {
        $this->blocks = array_values(array_unique($blocks));
        $this->disabledBlocks = null;

        return $this;
    }

    /**
     * @return list<string>|null
     */
    public function getBlocks(): ?array
    {
        return $this->blocks;
    }

    /**
     * Block types to remove from the default set (blacklist). Ignored if {@see blocks()} was set.
     *
     * @param  list<string>  $blocks
     */
    public function withoutBlocks(array $blocks): static
    {
        $this->disabledBlocks = array_values(array_unique($blocks));
        $this->blocks = null;

        return $this;
    }

    /**
     * @return list<string>|null
     */
    public function getDisabledBlocks(): ?array
    {
        return $this->disabledBlocks;
    }

    /**
     * @return list<string>
     */
    public function getResolvedBlockKeys(): array
    {
        $known = array_flip(self::BLOCK_TYPES);

        if ($this->blocks !== null) {
            $resolved = [];
            foreach ($this->blocks as $key) {
                if (isset($known[$key])) {
                    $resolved[] = $key;
                }
            }

            if ($resolved === []) {
                throw new InvalidArgumentException('BlockNoteEditor::blocks() must include at least one valid block type. See BlockNoteEditor::BLOCK_TYPES.');
            }

            if (! in_array('paragraph', $resolved, true)) {
                $resolved[] = 'paragraph';
            }

            return array_values(array_unique($resolved));
        }

        if ($this->disabledBlocks !== null) {
            $disable = [];
            foreach ($this->disabledBlocks as $key) {
                if ($key === 'paragraph') {
                    continue;
                }
                if (isset($known[$key])) {
                    $disable[$key] = true;
                }
            }

            return array_values(array_filter(
                self::BLOCK_TYPES,
                static fn (string $key): bool => ! isset($disable[$key]),
            ));
        }

        return self::BLOCK_TYPES;
    }

    public function getResolvedLocale(): string
    {
        if ($this->localeOverride !== null && $this->localeOverride !== '') {
            return $this->toBlockNoteLocaleCode($this->localeOverride);
        }

        return $this->toBlockNoteLocaleCode(app()->getLocale());
    }

    protected function normalizeLocaleKey(string $locale): string
    {
        $locale = strtolower(str_replace('_', '-', $locale));

        if (str_starts_with($locale, 'zh')) {
            if (str_contains($locale, 'tw') || str_contains($locale, 'hk') || str_contains($locale, 'mo')) {
                return 'zh-tw';
            }

            return 'zh';
        }

        if (str_contains($locale, '-')) {
            return explode('-', $locale, 2)[0];
        }

        return $locale;
    }

    protected function toBlockNoteLocaleCode(string $locale): string
    {
        $n = $this->normalizeLocaleKey($locale);

        return match ($n) {
            'zh-tw' => 'zh-tw',
            default => $n,
        };
    }
}
