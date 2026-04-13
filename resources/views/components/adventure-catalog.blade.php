<section class="w-full py-24 bg-background-light">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-4">
                <span class="text-primary font-black tracking-[0.4em] uppercase text-xs">Jelajahi Lebih Banyak</span>
                <h2 class="text-5xl font-display font-black text-forest-green uppercase">KATALOG PETUALANGAN</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <button class="px-8 py-3 rounded-full bg-forest-green text-white font-black text-xs uppercase tracking-widest hover:bg-primary transition-all">Semua</button>
                <button class="px-8 py-3 rounded-full bg-white border border-forest-green/10 text-forest-green font-black text-xs uppercase tracking-widest hover:bg-forest-green hover:text-white transition-all">Gunung</button>
                <button class="px-8 py-3 rounded-full bg-white border border-forest-green/10 text-forest-green font-black text-xs uppercase tracking-widest hover:bg-forest-green hover:text-white transition-all">Hutan</button>
                <button class="px-8 py-3 rounded-full bg-white border border-forest-green/10 text-forest-green font-black text-xs uppercase tracking-widest hover:bg-forest-green hover:text-white transition-all">Camping</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
            <x-trip-card 
                id="1"
                image="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&h=400&fit=crop"
                category="CAMPING & HIKING"
                title="Danau Alpin Kemilau"
                description="Rasakan keheningan malam di bawah jutaan bintang pada ketinggian 2.500 mdpl."
                price="IDR 4.5M"
                rating="4.9"
            />

            <x-trip-card 
                id="2"
                image="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500&h=400&fit=crop"
                category="PENDAKIAN"
                title="Jalur Puncak Embun"
                description="Taklukkan medan menantang melalui hutan tropis basah menuju samudera awan yang ikonik."
                price="IDR 1.2M"
                rating="4.8"
            />

            <x-trip-card 
                id="3"
                image="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=500&h=400&fit=crop"
                category="FOTOGRAFI"
                title="Sinar Hutan Emas"
                description="Memburu momen magis 'Ray of Light' di kedalaman hutan purba nusantara bersama fotografer ahli."
                price="IDR 950K"
                rating="5.0"
            />
        </div>

        <div class="text-center">
            <button class="px-16 py-6 rounded-full border-2 border-forest-green text-forest-green hover:bg-forest-green hover:text-white font-black text-sm uppercase tracking-[0.3em] transition-all shadow-xl shadow-forest-green/5">
                LIHAT SEMUA JADWAL TRIP
            </button>
        </div>
    </div>
</section>
