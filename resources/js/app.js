import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateJadwalRealtimeCard(root, payload) {
	if (!root || !payload) {
		return;
	}

	if (payload.deleted) {
		root.remove();
		return;
	}

	const setText = (field, value) => {
		const element = root.querySelector(`[data-jadwal-field="${field}"]`);
		if (element) {
			element.textContent = value;
		}
	};

	setText('tanggal-berangkat', payload.tanggal_berangkat_label ?? '');
	setText('tanggal-kembali', payload.tanggal_kembali_label ?? '');
	setText('harga', payload.harga_label ?? '');
	setText('kuota', `${payload.kuota_terisi ?? 0} / ${payload.kuota_max ?? 0}`);
	setText('sisa-kuota', `${payload.sisa_kuota ?? 0} dari ${payload.kuota_max ?? 0} kursi`);
	setText('status', payload.status ?? '');

	const badge = root.querySelector('[data-jadwal-field="kuota-badge"]');
	if (badge) {
		const sisaKuota = Number(payload.sisa_kuota ?? 0);
		const statusText = sisaKuota > 0 ? 'Tersedia' : 'Penuh';
		const detailText = sisaKuota > 0 ? 'Pilihan siap dipakai' : 'Pilih jadwal lain';

		if (badge.dataset.mode === 'compact') {
			badge.textContent = `${payload.kuota_terisi ?? 0} / ${payload.kuota_max ?? 0}`;
		} else {
			badge.innerHTML = `<span>${statusText}</span><span>${detailText}</span>`;
		}
	}

	const radio = root.querySelector('[data-jadwal-field="radio"]');
	if (radio) {
		radio.disabled = Number(payload.sisa_kuota ?? 0) <= 0;
	}

	root.dataset.jadwalStatus = payload.status ?? '';
	root.dataset.jadwalSisaKuota = String(payload.sisa_kuota ?? 0);
}

async function refreshJadwalRealtimeCard(root) {
	if (!root || root.dataset.jadwalRefreshBusy === '1') {
		return;
	}

	const refreshUrl = root.dataset.jadwalRefreshUrl;
	if (!refreshUrl) {
		return;
	}

	root.dataset.jadwalRefreshBusy = '1';

	try {
		const response = await fetch(refreshUrl, {
			headers: {
				Accept: 'application/json',
			},
			credentials: 'same-origin',
		});

		if (!response.ok) {
			return;
		}

		const payload = await response.json();
		updateJadwalRealtimeCard(root, payload);
	} catch (error) {
		console.warn('Gagal menyegarkan kuota jadwal.', error);
	} finally {
		root.dataset.jadwalRefreshBusy = '0';
	}
}

function bindRealtimeKuota() {
	document.querySelectorAll('[data-kuota-realtime-channel]').forEach((root) => {
		if (root.dataset.kuotaRealtimeBound === '1') {
			return;
		}

		const channelName = root.dataset.kuotaRealtimeChannel;
		if (!channelName) {
			return;
		}

		root.dataset.kuotaRealtimeBound = '1';

		if (window.Echo) {
			window.Echo.channel(channelName).listen('.jadwal.kuota.updated', (payload) => {
				updateJadwalRealtimeCard(root, payload);
			});
		}
	});
}

document.addEventListener('DOMContentLoaded', bindRealtimeKuota);

window.setInterval(() => {
	document.querySelectorAll('[data-jadwal-refresh-url]').forEach((root) => {
		refreshJadwalRealtimeCard(root);
	});
}, 15000);

