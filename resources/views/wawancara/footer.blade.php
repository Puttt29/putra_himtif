  <footer class="savebar">
      <div class="muted" style="font-size: 13px;">Data disimpan <b>lokal</b> di perangkat ini. Gunakan
          Ekspor/Impor.</div>
      <div class="row">
          <button class="btn slim ghost" id="btnHelp">Cara Embed ke Google Sites</button>
      </div>
  </footer>

  <div id="notification">Pesan notifikasi</div>

  <dialog id="modalHelp">
      <div class="modal-header">
          <span>💡 Cara Embed ke Google Sites</span>
          <button class="btn slim ghost" onclick="modalHelp.close()">&times;</button>
      </div>
      <p>Data formulir ini disimpan <b>lokal</b> di *browser* perangkat Anda. Gunakan fitur "Ekspor" dan "Impor" untuk
          *backup* atau memindahkan data antar perangkat.</p>
      <p>Untuk memasang (embed) formulir ini di Google Sites:</p>
      <ul>
          <li><b>Opsi 1 (Disarankan): Google Apps Script</b>
              <ol>
                  <li>Buat proyek Google Apps Script baru.</li>
                  <li>Tempel seluruh kode HTML ini ke dalam file `index.html`.</li>
                  <li>Buat file `Code.gs` sederhana untuk me-render HTML-nya.</li>
                  <li>*Deploy* sebagai "Web App" dengan akses "Anyone".</li>
                  <li>Salin URL Web App yang Anda dapatkan.</li>
              </ol>
          </li>
          <li><b>Opsi 2: Hosting Statis</b>
              <ol>
                  <li>Unggah file `.html` ini ke layanan hosting statis seperti Netlify, Vercel, GitHub Pages, atau
                      bahkan Google Drive publik (jika masih didukung).</li>
                  <li>Dapatkan URL publik dari file HTML tersebut.</li>
              </ol>
          </li>
      </ul>
      <p><b>Di Google Sites:</b></p>
      <p>Buka editor Google Sites Anda, pilih <b>Sisipkan > Sematkan (Embed)</b>. Pilih tab <b>"Dari URL"</b>, lalu
          tempel URL yang Anda dapatkan dari Opsi 1 atau 2.</p>

      <button class="btn" style="float:right" onclick="modalHelp.close()">Tutup</button>
  </dialog>

  <dialog id="modalPanduan">
      <div class="modal-header">
          <span>💬 Panduan Pertanyaan Interview</span>
          <button type="button" class="btn slim ghost" onclick="document.getElementById('modalPanduan').close()">&times;</button>
      </div>

      <div style="max-height: 60vh; overflow-y: auto; padding-right: 10px;">
          <h4>Motivasi</h4>
          <ul>
              <li>Apa yang membuat Anda tertarik untuk bergabung dengan HIMTIF?</li>
              <li>Dari semua divisi, mengapa Anda memilih divisi [X]? Apa yang Anda harapkan?</li>
              <li>Apa yang Anda ketahui tentang HIMTIF dan apa yang ingin Anda capai di sini?</li>
              <li>Ceritakan satu hal yang paling ingin Anda kontribusikan atau ubah di HIMTIF.</li>
          </ul>

          <h4>Komitmen</h4>
          <ul>
              <li>Bagaimana Anda akan menyeimbangkan waktu antara kuliah, organisasi (HIMTIF), dan pribadi?</li>
              <li>Apa yang akan Anda lakukan jika di tengah jalan Anda merasa jenuh atau *burnout* dengan kegiatan
                  organisasi?</li>
              <li>Jika ada rapat atau acara penting yang bentrok dengan acara pribadi (non-darurat), mana yang akan
                  Anda prioritaskan?</li>
              <li>Apa definisi "komitmen" menurut Anda dalam konteks sebuah organisasi?</li>
          </ul>

          <h4>Sikap & Kerja Sama</h4>
          <ul>
              <li>Ceritakan pengalaman Anda bekerja dalam tim. Apa peran yang biasanya Anda ambil?</li>
              <li>Bagaimana Anda biasanya menghadapi rekan satu tim yang tidak kooperatif atau tidak mengerjakan
                  tugasnya?</li>
              <li>Ceritakan situasi di mana Anda menerima kritik pedas. Bagaimana Anda meresponsnya?</li>
              <li>Apa yang Anda lakukan jika pendapat Anda berbeda-beda dengan mayoritas anggota tim dalam sebuah
                  rapat?</li>
          </ul>

          <h4>Ketekunan</h4>
          <ul>
              <li>Ceritakan satu kegagalan terbesar Anda (akademis/non-akademis) dan apa yang Anda pelajari darinya.
              </li>
              <li>Apa yang akan Anda lakukan jika Anda diberi tugas yang Anda tidak mengerti sama sekali cara
                  mengerjakannya?</li>
              <li>Bagaimana Anda menjaga diri Anda tetap termotivasi untuk menyelesaikan tugas yang panjang dan
                  membosankan?</li>
          </ul>

          <h4>Kesiapan Diri</h4>
          <ul>
              <li>Apa kelebihan utama Anda yang relevan untuk divisi yang Anda pilih?</li>
              <li>Apa satu kelemahan terbesar Anda, dan bagaimana Anda mengatasinya agar tidak mengganggu kinerja tim?
              </li>
              <li>Selain di HIMTIF, kegiatan apa lagi yang Anda ikuti? Bagaimana Anda memastikan kesiapan Anda untuk
                  HIMTIF?</li>
              <li>(Tanya kasus) Jika [sebutkan sebuah masalah umum di divisi tsb], apa langkah pertama yang akan Anda
                  lakukan?</li>
          </ul>
      </div>

      <button type="button" class="btn" style="float:right; margin-top: 16px;" onclick="document.getElementById('modalPanduan').close()">Tutup</button>
  </dialog>
