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

	const participantInput = document.getElementById('jml_peserta');
	const selectedParticipants = Math.max(1, Number(participantInput?.value || 1));
	const baseSisaKuota = Number(payload.sisa_kuota ?? root.dataset.jadwalBaseSisaKuota ?? root.dataset.jadwalSisaKuota ?? 0);
	const totalKuota = Number(payload.kuota_max ?? root.dataset.jadwalTotalKuota ?? 0);
	const radio = root.querySelector('[data-jadwal-field="radio"]');
	const isSelected = Boolean(radio?.checked || root.dataset.jadwalSelected === '1');
	const displaySisaKuota = isSelected ? Math.max(0, baseSisaKuota - selectedParticipants) : baseSisaKuota;

	root.dataset.jadwalBaseSisaKuota = String(baseSisaKuota);
	root.dataset.jadwalTotalKuota = String(totalKuota);
	root.dataset.jadwalSisaKuota = String(baseSisaKuota);

	setText('tanggal-berangkat', payload.tanggal_berangkat_label ?? '');
	setText('tanggal-kembali', payload.tanggal_kembali_label ?? '');
	setText('harga', payload.harga_label ?? '');
	setText('kuota', `${payload.kuota_terisi ?? 0} / ${payload.kuota_max ?? 0}`);
	setText('sisa-kuota', `${displaySisaKuota} dari ${totalKuota || payload.kuota_max || 0} kursi`);
	setText('status', payload.status ?? '');

	const badge = root.querySelector('[data-jadwal-field="kuota-badge"]');
	if (badge) {
		const statusText = displaySisaKuota > 0 ? 'Tersedia' : 'Penuh';
		const detailText = displaySisaKuota > 0 ? 'Pilihan siap dipakai' : 'Pilih jadwal lain';

		if (badge.dataset.mode === 'compact') {
			badge.textContent = `${displaySisaKuota} / ${totalKuota || payload.kuota_max || 0}`;
		} else {
			badge.innerHTML = `<span>${statusText}</span><span>${detailText}</span>`;
		}
	}

	if (radio) {
		radio.disabled = baseSisaKuota <= 0;
	}

	root.dataset.jadwalStatus = payload.status ?? '';
	root.dataset.jadwalSelected = isSelected ? '1' : '0';
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

