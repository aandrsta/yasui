# Design System — Yasui E-Commerce

Visual system tokens, typography scales, components and motion rules.

## Theme

- **Warm Light Mode:** Terinspirasi oleh kertas tradisional Jepang yang hangat (*Warm Paper*) dan kayu cedar alami.
- **Physical Mood:** Seperti melangkah masuk ke dalam galeri butik seni independen di gang-gang tenang Kyoto pada sore hari yang sejuk.

## Colors

Sistem warna menggunakan nuansa kertas hangat yang di-tinting lembut, dipadukan dengan aksen Sakura Rose dan Obsidian kokoh.

```css
:root {
    --primary-color: #1e1e1d;        /* Obsidian (Hitam Arang) */
    --primary-hover: #2f2f2e;        /* Charcoal Warm */
    --accent-color: #a2384a;         /* Sakura Rose / Deep Wine (Merah Aksen) */
    --accent-hover: #852b3a;         /* Sakura Rose Deepened */
    --text-main: #2b2a27;            /* Charcoal Utama (Teks utama) */
    --text-muted: #75726a;           /* Warm Gray (Teks sekunder/keterangan) */
    --border-color: #e7e4dc;         /* Soft Hairline (Garis pembatas tipis hangat) */
    --bg-main: #fbfaf7;              /* Warm Paper (Latar belakang utama kertas) */
    --bg-subtle: #f6f4ee;            /* Subtle Cream (Latar belakang sekunder/kartu) */
}
```

*Aturan Warna:* Dilarang menggunakan warna hitam pekat `#000000` atau putih pekat `#ffffff` murni. Semua warna abu-abu dinetralkan dengan rona merah/kuning hangat setebal 0.5% - 1.0% chroma untuk menjaga kehangatan kertas.

## Typography

Sistem tipografi menggabungkan elegansi klasik serif tradisional dengan keterbacaan modern sans-serif.

### Font Families
- **Serif (Headings & Quotes):** `Zen Old Mincho` / `Cormorant Garamond`
- **Sans-serif (UI & Body Teks):** `Instrument Sans` / `Space Grotesk`

### Scale
- **H1 (Display):** `3.8rem` (Line-height: 1.15, Font-weight: 300, Zen Old Mincho)
- **H2 (Section Header):** `2.2rem` (Line-height: 1.25, Font-weight: 500, Zen Old Mincho)
- **H3 (Sub-section):** `1.6rem` (Cormorant Garamond)
- **Body Text:** `0.95rem` (Line-height: 1.6, Instrument Sans)
- **Tracked Labels:** `0.725rem` (Uppercase, Letter-spacing: 0.08em, Font-weight: 700)

## Layout & Spacing

- **Stark Hairlines:** Menggunakan border tipis hangat (`1px solid var(--border-color)`) sebagai pemisah struktur alih-alih menggunakan bayangan tebal.
- **Museum Padding:** Spacing yang longgar (`padding: 4rem - 6rem` pada hero dan section) untuk memberikan ruang bernapas pada konten (whitespace).
- **Asymmetric Grid:** Menyusun tata letak secara asimetris pada visual beranda untuk menciptakan ketertarikan visual yang terkurasi tinggi.

## Components

### Buttons
- **Primary Minimal:** Latar belakang Obsidian, teks warna kertas hangat, sudut tegas (`border-radius: 3px`), letter-spacing tracked.
- **Accent Minimal:** Latar belakang Sakura Rose, teks putih, sudut tegas (`border-radius: 3px`).
- **Secondary Minimal:** Latar belakang transparan, border hairline tipis, teks Charcoal, sudut tegas.

### Cards & Frames
- **Stark Cards:** Tanpa shadow tebal (`box-shadow: none`), menggunakan pembatas tipis (`1px solid var(--border-color)`), dengan sudut tegas `3px`.
- **Museum Frame:** Bingkai luar gambar produk dengan padding inset 1.25rem dan latar belakang `var(--bg-subtle)` untuk memamerkan produk seperti benda seni berharga.

## Motion & Transitions

- **Base Transition:** `all 0.3s cubic-bezier(0.16, 1, 0.3, 1)` (ease-out-expo).
- **Scale Hover:** Gambar pada frame akan membesar perlahan saat di-hover (`scale(1.04)`) dengan durasi transisi `0.8s` yang lambat dan elegan.
- **Fade In Up:** Efek staggered reveal untuk elemen daftar katalog dan kartu beranda saat dimuat.
