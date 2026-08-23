@props([
    'name' => 'signature',
    'label' => 'Paraf',
    'width' => 600,
    'height' => 220,
    'required' => false,
])

<div
    x-data="signaturePad('{{ $name }}', {{ $width }}, {{ $height }}, {{ $required ? 'true' : 'false' }})"
    x-on:signature-invalid.window="signatureError = $event.detail.name === '{{ $name }}'"
    class="space-y-3"
>
    <div class="flex items-center justify-between gap-3">

        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            {{ $label }}

            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>

        <button
            type="button"
            @click="clear()"
            class="text-xs font-semibold text-rose-600 transition hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300"
        >
            Bersihkan
        </button>

    </div>

    <div
        class="overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm transition dark:border-gray-600"
        :class="signatureError ? 'border-rose-400 ring-1 ring-rose-400' : ''"
    >
        <canvas
            x-ref="canvas"
            width="{{ $width }}"
            height="{{ $height }}"
            class="block h-auto w-full touch-none cursor-crosshair"
        ></canvas>
    </div>

    <input
        type="hidden"
        name="{{ $name }}"
        x-ref="input"
        value=""
    >

    <p
        x-show="signatureError"
        x-cloak
        class="text-xs font-medium text-rose-600 dark:text-rose-400"
    >
        Silakan buat paraf terlebih dahulu.
    </p>

    <p class="text-xs text-gray-500 dark:text-gray-400">
        Gunakan jari pada HP atau mouse/touchpad pada laptop untuk membuat paraf.
    </p>
</div>

@once
<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data(
            'signaturePad',
            (name, width, height, required) => ({

                canvas: null,
                ctx: null,
                drawing: false,
                hasSignature: false,
                signatureError: false,

                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');

                    this.setupCanvas();
                    this.bindEvents();

                    /*
                    |--------------------------------------------------------------------------
                    | Validasi form sebelum submit
                    |--------------------------------------------------------------------------
                    */
                    const form = this.canvas.closest('form');

                    if (form) {
                        form.addEventListener('submit', (event) => {

                            if (
                                required &&
                                !this.hasSignature
                            ) {
                                event.preventDefault();

                                this.signatureError = true;

                                window.dispatchEvent(
                                    new CustomEvent(
                                        'signature-invalid',
                                        {
                                            detail: {
                                                name: name,
                                            }
                                        }
                                    )
                                );

                                this.canvas.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center',
                                });
                            }
                        });
                    }
                },

                setupCanvas() {
                    this.ctx.lineWidth = 2.2;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.strokeStyle = '#111827';

                    this.ctx.fillStyle = '#ffffff';

                    this.ctx.fillRect(
                        0,
                        0,
                        this.canvas.width,
                        this.canvas.height
                    );
                },

                getPoint(event) {

                    const rect =
                        this.canvas.getBoundingClientRect();

                    return {
                        x:
                            (
                                event.clientX -
                                rect.left
                            ) *
                            (
                                this.canvas.width /
                                rect.width
                            ),

                        y:
                            (
                                event.clientY -
                                rect.top
                            ) *
                            (
                                this.canvas.height /
                                rect.height
                            ),
                    };
                },

                startDrawing(event) {

                    event.preventDefault();

                    this.drawing = true;
                    this.hasSignature = true;
                    this.signatureError = false;

                    this.canvas.setPointerCapture(
                        event.pointerId
                    );

                    const point =
                        this.getPoint(event);

                    this.ctx.beginPath();

                    this.ctx.moveTo(
                        point.x,
                        point.y
                    );
                },

                draw(event) {

                    if (!this.drawing) {
                        return;
                    }

                    event.preventDefault();

                    const point =
                        this.getPoint(event);

                    this.ctx.lineTo(
                        point.x,
                        point.y
                    );

                    this.ctx.stroke();
                },

                stopDrawing(event) {

                    if (!this.drawing) {
                        return;
                    }

                    event.preventDefault();

                    this.drawing = false;

                    this.ctx.closePath();

                    this.updateInput();
                },

                bindEvents() {

                    this.canvas.addEventListener(
                        'pointerdown',
                        event => this.startDrawing(event)
                    );

                    this.canvas.addEventListener(
                        'pointermove',
                        event => this.draw(event)
                    );

                    this.canvas.addEventListener(
                        'pointerup',
                        event => this.stopDrawing(event)
                    );

                    this.canvas.addEventListener(
                        'pointercancel',
                        event => this.stopDrawing(event)
                    );
                },

                updateInput() {

                    if (!this.hasSignature) {
                        this.$refs.input.value = '';
                        return;
                    }

                    this.$refs.input.value =
                        this.canvas.toDataURL(
                            'image/png'
                        );
                },

                clear() {

                    this.ctx.clearRect(
                        0,
                        0,
                        this.canvas.width,
                        this.canvas.height
                    );

                    this.ctx.fillStyle = '#ffffff';

                    this.ctx.fillRect(
                        0,
                        0,
                        this.canvas.width,
                        this.canvas.height
                    );

                    this.hasSignature = false;

                    this.signatureError = false;

                    this.$refs.input.value = '';
                },
            })
        );
    });
</script>
@endonce