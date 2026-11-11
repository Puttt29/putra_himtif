   <script>
       // --- UTILITIES ---
       const $ = s => document.querySelector(s);
       const $$ = s => document.querySelectorAll(s);
       const fields = ['nama', 'nim', 'tanggal', 'pewawancara', 's_motivasi', 's_komitmen', 's_sikap', 's_ketekunan',
           's_kesiapan', 'catatan', 'catatanSkor'
       ];
       const storageKey = 'himtif_interviews_v2'; // Key baru untuk versi ini
       let currentId = null; // Untuk melacak ID entri yang sedang diedit
       let notifyTimer;

       // --- NOTIFICATION ---
       function showNotification(message, type = 'good', duration = 3000) {
           const notif = $('#notification');
           notif.textContent = message;
           notif.className = type === 'bad' ? 'bad' : '';
           notif.classList.add('show');

           clearTimeout(notifyTimer);
           notifyTimer = setTimeout(() => {
               notif.classList.remove('show');
           }, duration);
       }

       // --- DATA LOGIC ---
       function getData() {
           const o = {};
           fields.forEach(f => {
               const el = document.getElementById(f);
               if (el) o[f] = el.value.trim();
           });

           o._id = currentId || `${o.nim || 'N/A'}-${o.tanggal || Date.now()}`;

           const n = [o.s_motivasi, o.s_komitmen, o.s_sikap, o.s_ketekunan, o.s_kesiapan].map(Number);
           o.rerata = (n.reduce((a, b) => a + b, 0) / n.length) || 0;

           if (o.rerata >= 4) o.klas = 'LAYAK';
           else if (o.rerata >= 3) o.klas = 'PERLU PEMBINAAN';
           else o.klas = 'TIDAK LAYAK SEMENTARA';

           return o;
       }

       function setData(o) {
           if (!o) return;
           fields.forEach(f => {
               const el = document.getElementById(f);
               if (el) el.value = (o[f] ?? '');
           });
           currentId = o._id; // Set ID yang sedang diedit
           updatePreview();
       }

       function loadAll() {
           try {
               return JSON.parse(localStorage.getItem(storageKey) || '[]');
           } catch (e) {
               console.error("Gagal memuat localStorage:", e);
               showNotification("Gagal memuat data. (Mode Private?)", 'bad');
               return [];
           }
       }

       function saveAll(list) {
           try {
               localStorage.setItem(storageKey, JSON.stringify(list));
               renderTable();
           } catch (e) {
               console.error("Gagal menyimpan ke localStorage:", e);
               showNotification("Gagal menyimpan. (Penyimpanan penuh?)", 'bad', 5000);
           }
       }

       function saveCurrent() {
           const o = getData();
           if (!o.nama && !o.nim) {
               showNotification("Nama atau NIM tidak boleh kosong", 'bad');
               return;
           }
           let list = loadAll();
           const idx = list.findIndex(x => x._id === o._id);

           if (idx > -1) { // Perbarui
               list[idx] = o;
           } else { // Tambah baru
               list.unshift(o);
           }
           saveAll(list);
           currentId = o._id; // Pastikan ID saat ini disetel
           showNotification('Tersimpan di perangkat ini.');
       }

       // --- RENDERING ---
       function renderTable() {
           const q = $('#search').value.toLowerCase();
           const list = loadAll().filter(x => (x.nama || '').toLowerCase().includes(q) || (x.nim || '').toLowerCase()
               .includes(q));
           const tbl = $('#tbl');

           let html = '<tr><th>Nama</th><th>NIM</th><th>Tanggal</th><th>Rerata</th><th>Klasifikasi</th><th></th></tr>';
           if (list.length === 0) {
               html += '<tr><td colspan="6" style="text-align:center;opacity:0.7;">Belum ada data</td></tr>';
           } else {
               html += list.map(x => `
          <tr>
            <td>${x.nama || ''}</td>
            <td>${x.nim || ''}</td>
            <td>${x.tanggal || ''}</td>
            <td>${(x.rerata || 0).toFixed(2)}</td>
            <td>${x.klas || ''}</td>
            <td><button class="btn slim" data-id="${x._id}">Buka</button></td>
          </tr>`).join('');
           }
           tbl.innerHTML = html;

           tbl.querySelectorAll('button[data-id]').forEach(b => {
               b.onclick = () => {
                   const id = b.getAttribute('data-id');
                   const data = loadAll().find(x => x._id === id);
                   if (data) {
                       setData(data);
                       window.scrollTo(0, 0); // Scroll ke atas
                   }
               }
           });
       }

       function updatePreview() {
           const o = getData();
           $('#p_nama').textContent = o.nama || '-';
           $('#p_nim').textContent = o.nim || '-';
           $('#p_tanggal').textContent = o.tanggal || '-';
           $('#p_pewawancara').textContent = o.pewawancara || '-';
           $('#p_motivasi').textContent = o.s_motivasi;
           $('#p_komitmen').textContent = o.s_komitmen;
           $('#p_sikap').textContent = o.s_sikap;
           $('#p_ketekunan').textContent = o.s_ketekunan;
           $('#p_kesiapan').textContent = o.s_kesiapan;
           $('#p_rerata').textContent = (o.rerata || 0).toFixed(2);
           $('#p_klasifikasi').textContent = o.klas || '-';
           $('#p_alasan').textContent = o.catatanSkor || '';
           $('#p_catatan').textContent = o.catatan || '';
           $('#p_ttd').textContent = o.pewawancara || '';
           $('#p_generated').textContent = new Date().toLocaleString('id-ID');

           const k = $('#kpi');
           k.innerHTML = '';
           const pill = (txt, cls) => {
               const d = document.createElement('div');
               d.className = 'pill ' + cls;
               d.textContent = txt;
               return d;
           };

           const cls = o.rerata >= 4 ? 'good' : (o.rerata >= 3 ? 'warn' : 'bad');
           k.appendChild(pill(`Rerata ${o.rerata.toFixed(2)}`, cls));
           k.appendChild(pill(`Klasifikasi: ${o.klas}`, cls));
       }

       // --- ACTIONS & EXPORTS ---
       async function downloadJPEG() {
           const node = document.getElementById('printArea');
           try {
               const canvas = await html2canvas(node, {
                   scale: 2,
                   useCORS: true,
                   backgroundColor: '#fff'
               });
               const url = canvas.toDataURL('image/jpeg', 0.95);
               const a = document.createElement('a');
               a.href = url;
               a.download = `HIMTIF-Interview-${$('#nama').value || 'noname'}.jpg`;
               a.click();
           } catch (e) {
               console.error("Gagal download JPEG:", e);
               showNotification("Gagal download JPEG", 'bad');
           }
       }

       async function downloadPDF() {
           const node = document.getElementById('printArea');
           try {
               const canvas = await html2canvas(node, {
                   scale: 2,
                   useCORS: true,
                   backgroundColor: '#fff'
               });
               const imgData = canvas.toDataURL('image/jpeg', 0.95);
               const {
                   jsPDF
               } = window.jspdf;
               const pdf = new jsPDF({
                   orientation: 'p',
                   unit: 'pt',
                   format: 'a4'
               });
               const pageWidth = pdf.internal.pageSize.getWidth();
               const pageHeight = pdf.internal.pageSize.getHeight();
               const margin = 36; // 0.5 inch
               const imgWidth = pageWidth - (margin * 2);
               const imgHeight = canvas.height * (imgWidth / canvas.width);

               if (imgHeight > pageHeight - (margin * 2)) {
                   const ratio = (pageHeight - (margin * 2)) / imgHeight;
                   pdf.addImage(imgData, 'JPEG', margin, margin, imgWidth * ratio, imgHeight * ratio);
               } else {
                   pdf.addImage(imgData, 'JPEG', margin, margin, imgWidth, imgHeight);
               }
               pdf.save(`HIMTIF-Interview-${$('#nama').value || 'noname'}.pdf`);
           } catch (e) {
               console.error("Gagal download PDF:", e);
               showNotification("Gagal download PDF", 'bad');
           }
       }

       function exportCSV() {
           const list = loadAll();
           if (list.length === 0) {
               showNotification("Tidak ada data untuk diekspor", 'bad');
               return;
           }
           const cols = ['nama', 'nim', 'tanggal', 'pewawancara', 's_motivasi', 's_komitmen', 's_sikap', 's_ketekunan',
               's_kesiapan', 'rerata', 'klas', 'catatanSkor', 'catatan'
           ];
           const esc = s => ('' + s).replaceAll('"', '""');
           const rows = [cols.join(',')].concat(list.map(o => cols.map(c => `"${esc(o[c] ?? '')}"`).join(',')));

           const blob = new Blob([rows.join('\n')], {
               type: 'text/csv;charset=utf-sh'
           });
           const url = URL.createObjectURL(blob);
           const a = document.createElement('a');
           a.href = url;
           a.download = 'HIMTIF-Interview.csv';
           a.click();
           URL.revokeObjectURL(url);
       }

       function importJSON(file) {
           const r = new FileReader();
           r.onload = () => {
               try {
                   const incoming = JSON.parse(r.result || '[]');
                   if (!Array.isArray(incoming)) throw new Error('Format tidak sesuai (bukan array)');
                   if (incoming.length === 0) throw new Error('File JSON kosong');

                   const all = loadAll();
                   // Cek duplikat sederhana berdasarkan _id
                   const incomingIds = new Set(incoming.map(i => i._id));
                   const nonDuplicates = all.filter(item => !incomingIds.has(item._id));

                   saveAll([...incoming, ...nonDuplicates]);
                   showNotification(`Impor ${incoming.length} data berhasil.`);
               } catch (e) {
                   console.error("Gagal impor:", e);
                   showNotification(`Gagal impor: ${e.message}`, 'bad', 5000);
               }
           };
           r.readAsText(file);
       }

       // --- EVENT LISTENERS ---
       $('#btnUpdatePreview').onclick = updatePreview;
       $('#btnSave').onclick = saveCurrent;
       $('#btnExport').onclick = exportCSV;
       $('#btnJPEG').onclick = downloadJPEG;
       $('#btnPDF').onclick = downloadPDF;
       $('#btnPrint').onclick = () => window.print();
       $('#btnHelp').onclick = () => $('#modalHelp').showModal();

       // INI EVENT LISTENER BARU
       $('#btnPanduan').onclick = () => $('#modalPanduan').showModal();

       $('#btnNew').onclick = () => {
           fields.forEach(f => {
               const el = document.getElementById(f);
               if (el) {
                   if (el.tagName === 'SELECT') el.value = '5'; // Default ke 5
                   else el.value = '';
               }
           });
           const t = new Date().toISOString().slice(0, 10);
           $('#tanggal').value = t; // Set tanggal ke hari ini
           currentId = null; // Reset ID
           updatePreview();
           $('#nama').focus(); // Fokus ke field nama
       };

       $('#fileImport').onchange = e => {
           if (e.target.files && e.target.files[0]) {
               importJSON(e.target.files[0]);
           }
           e.target.value = null; // Reset file input
       };

       $('#search').oninput = renderTable;

       // --- INITIALIZATION ---
       (function init() {
           // Migrasi data lama jika ada
           try {
               const oldKey = 'himtif_interviews';
               const oldData = localStorage.getItem(oldKey);
               if (oldData && !localStorage.getItem(storageKey)) {
                   localStorage.setItem(storageKey, oldData);
                   showNotification("Data lama berhasil dimigrasi.");
               }
           } catch (e) {
               console.warn("Gagal migrasi data lama", e);
           }

           const t = new Date().toISOString().slice(0, 10);
           $('#tanggal').value = t;
           renderTable();
           updatePreview();
       })();

       document.addEventListener('DOMContentLoaded', function() {
           if (window.__LAST_SAVED__) {
               const d = window.__LAST_SAVED__;

               nama.value = d.nama;
               nim.value = d.nim;
               angkatan.value = d.angkatan;
               catatan.value = d.catatan || '';

               if (d.nilai) {
                   document.querySelectorAll('.score-input').forEach(select => {
                       const match = select.name.match(/\[(\d+)\]/);
                       if (!match) return;

                       const kid = match[1];
                       if (d.nilai[kid] !== undefined) {
                           select.value = parseInt(d.nilai[kid]);
                       }
                   });
               }

               hitungRata();

               nama.dispatchEvent(new Event('input'));
               nim.dispatchEvent(new Event('input'));
               catatan.dispatchEvent(new Event('input'));
           }
       });
   </script>
